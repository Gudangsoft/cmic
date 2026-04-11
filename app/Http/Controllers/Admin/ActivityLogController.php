<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest('created_at')->paginate(50);
        return view('admin.activity_logs.index', compact('logs'));
    }

    public function clear()
    {
        // Keep last 100 records, delete the rest
        $keep = ActivityLog::latest('created_at')->limit(100)->pluck('id');
        ActivityLog::whereNotIn('id', $keep)->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Log lama berhasil dibersihkan.');
    }
}
