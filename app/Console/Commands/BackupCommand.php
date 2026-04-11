<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupCommand extends Command
{
    protected $signature   = 'app:backup {--keep=7 : Number of daily backups to retain}';
    protected $description = 'Backup database and storage/app/public to storage/app/backups';

    public function handle(): int
    {
        $date     = now()->format('Y-m-d_H-i-s');
        $backupDir = storage_path("app/backups/{$date}");

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // ── 1. Database dump ────────────────────────────────────────────
        $this->info('Backing up database...');
        $dbSuccess = $this->dumpDatabase("{$backupDir}/database.sql");

        if ($dbSuccess) {
            $this->line('  ✓ database.sql');
        } else {
            $this->warn('  ✗ Database backup skipped (mysqldump not found or failed).');
        }

        // ── 2. Storage zip ──────────────────────────────────────────────
        $this->info('Backing up storage...');
        $storageSuccess = $this->zipStorage("{$backupDir}/storage.zip");

        if ($storageSuccess) {
            $this->line('  ✓ storage.zip');
        } else {
            $this->warn('  ✗ Storage backup skipped (ZipArchive not available).');
        }

        // ── 3. Rotate old backups ───────────────────────────────────────
        $keep = (int) $this->option('keep');
        $this->rotate($keep);

        $this->info("Backup selesai: {$backupDir}");

        return self::SUCCESS;
    }

    private function dumpDatabase(string $outputFile): bool
    {
        $conn = config('database.connections.' . config('database.default'));

        if ($conn['driver'] !== 'mysql') {
            return false; // only MySQL supported
        }

        $host     = escapeshellarg($conn['host']);
        $port     = escapeshellarg($conn['port'] ?? '3306');
        $database = escapeshellarg($conn['database']);
        $user     = escapeshellarg($conn['username']);
        $pass     = $conn['password'];
        $output   = escapeshellarg($outputFile);

        $passArg = $pass ? '-p' . escapeshellarg($pass) : '';
        $cmd = "mysqldump --host={$host} --port={$port} --user={$user} {$passArg} {$database} > {$output} 2>&1";

        exec($cmd, $cmdOutput, $exitCode);

        return $exitCode === 0;
    }

    private function zipStorage(string $zipFile): bool
    {
        if (! class_exists('ZipArchive')) {
            return false;
        }

        $sourceDir = storage_path('app/public');

        if (! is_dir($sourceDir)) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $relativePath = substr($file->getRealPath(), strlen($sourceDir) + 1);
            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }

        $zip->close();

        return true;
    }

    private function rotate(int $keepDays): void
    {
        $backupRoot = storage_path('app/backups');

        if (! is_dir($backupRoot)) {
            return;
        }

        $dirs = glob("{$backupRoot}/*", GLOB_ONLYDIR);

        if (! $dirs || count($dirs) <= $keepDays) {
            return;
        }

        sort($dirs); // oldest first

        $toDelete = array_slice($dirs, 0, count($dirs) - $keepDays);

        foreach ($toDelete as $dir) {
            $this->deleteDirectory($dir);
            $this->line("  Removed old backup: " . basename($dir));
        }
    }

    private function deleteDirectory(string $dir): void
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "{$dir}/{$file}";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
