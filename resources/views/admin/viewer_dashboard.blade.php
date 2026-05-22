@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height:60vh;">
    <div class="text-center" style="max-width:420px;">
        <div style="width:80px;height:80px;border-radius:50%;background:rgba(0,87,168,.1);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <i class="fas fa-globe" style="font-size:32px;color:var(--cmic-blue,#0057A8);"></i>
        </div>
        <h4 style="font-weight:700;color:#1a2a4a;margin-bottom:8px;">
            Selamat datang, {{ auth()->user()->name }}!
        </h4>
        <p style="color:#64748b;font-size:14px;margin-bottom:6px;">
            Akun Anda memiliki akses <strong>Viewer</strong>.
        </p>
        <p style="color:#64748b;font-size:14px;margin-bottom:32px;">
            Anda dapat mengunjungi website CMIC melalui tombol di bawah ini.
        </p>
        <a href="{{ route('home') }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:10px;padding:14px 36px;background:var(--cmic-dark-blue,#003A78);color:#fff;border-radius:10px;font-weight:700;font-size:15px;text-decoration:none;box-shadow:0 4px 16px rgba(0,57,120,.25);transition:opacity .2s;"
           onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
            <i class="fas fa-external-link-alt"></i>
            Lihat Website CMIC
        </a>
    </div>
</div>
@endsection
