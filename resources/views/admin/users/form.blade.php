@extends('layouts.admin')
@section('title', isset($user->id) ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page-title', isset($user->id) ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
<li class="breadcrumb-item active">{{ isset($user->id) ? 'Edit' : 'Tambah' }}</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-xl-6 col-lg-8">
<div class="card form-card">
    <div class="fcard-header">
        <i class="fas fa-user-cog me-2"></i>{{ isset($user->id) ? 'Edit' : 'Tambah' }} Pengguna
    </div>
    <div class="fcard-body">
        <form action="{{ isset($user->id) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
            @csrf @if(isset($user->id)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password {{ isset($user->id) ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-danger">{{ isset($user->id) ? '' : '*' }}</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       {{ isset($user->id) ? '' : 'required' }} autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Minimal 8 karakter</small>
            </div>

            <div class="mb-4">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="admin"  {{ old('role', $user->role ?? 'admin')  === 'admin'  ? 'selected' : '' }}>Administrator — akses penuh ke semua fitur</option>
                    <option value="viewer" {{ old('role', $user->role ?? 'admin') === 'viewer' ? 'selected' : '' }}>Viewer — hanya dapat melihat website</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
