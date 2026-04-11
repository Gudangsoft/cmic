@extends('layouts.admin')
@section('title','Pengaturan Akun') @section('page-title','Pengaturan Akun')
@section('breadcrumb')<li class="breadcrumb-item active">Pengaturan Akun</li>@endsection

@push('styles')
<style>
.acc-hero {
    background: linear-gradient(135deg, var(--blue, #0057A8) 0%, var(--dark-blue, #003A78) 100%);
    border-radius: 16px;
    padding: 32px 28px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.acc-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.acc-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -30px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.acc-avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: var(--yellow, #F5C518);
    color: #1a2a4a;
    font-size: 28px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
    border: 3px solid rgba(255,255,255,.3);
}
.acc-hero .user-name { color: #fff; font-size: 20px; font-weight: 700; margin: 0; }
.acc-hero .user-email { color: rgba(255,255,255,.65); font-size: 13px; margin: 2px 0 0; }
.acc-hero .acc-badge {
    background: rgba(245,197,24,.18);
    border: 1px solid rgba(245,197,24,.35);
    color: var(--yellow, #F5C518);
    font-size: 11px; font-weight: 600;
    padding: 3px 12px; border-radius: 20px;
    display: inline-flex; align-items: center; gap: 5px;
}
.acc-tab-btn {
    flex: 1;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 13px 10px;
    font-size: 13.5px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: 7px;
}
.acc-tab-btn.active {
    color: var(--blue, #0057A8);
    border-bottom-color: var(--blue, #0057A8);
    background: rgba(0,87,168,.04);
}
.acc-tab-btn:hover:not(.active) { color: #334155; background: #f8fafc; }
.acc-tab-pane { display: none; animation: fadeIn .25s ease; }
.acc-tab-pane.active { display: block; }
@keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

.field-icon-wrap { position: relative; }
.field-icon-wrap .field-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--blue, #0057A8); font-size: 14px; pointer-events: none; z-index: 5;
}
.field-icon-wrap .form-control { padding-left: 36px; }

.strength-bar { height: 5px; border-radius: 3px; background: #e2e8f0; margin-top: 6px; overflow: hidden; }
.strength-bar-inner { height: 100%; border-radius: 3px; transition: width .3s, background .3s; width: 0; }
.strength-label { font-size: 11.5px; margin-top: 4px; font-weight: 500; }

.acc-info-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}
.acc-info-item:last-child { border-bottom: none; }
.acc-info-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: rgba(0,87,168,.08);
    color: var(--blue,#0057A8);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.acc-info-label { font-size: 11px; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }
.acc-info-val { font-size: 14px; color: #1e293b; font-weight: 500; }
</style>
@endpush

@section('content')

{{-- Hero / Profile Card --}}
<div class="acc-hero">
    <div class="d-flex align-items-center gap-4 position-relative" style="z-index:1;">
        <div class="acc-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="flex-grow-1">
            <p class="acc-hero .user-name mb-1" style="color:#fff;font-size:20px;font-weight:700;">{{ auth()->user()->name }}</p>
            <p class="acc-hero .user-email mb-2" style="color:rgba(255,255,255,.65);font-size:13px;">{{ auth()->user()->email }}</p>
            <span class="acc-badge"><i class="fas fa-shield-alt"></i> Administrator</span>
        </div>
        <div class="text-end d-none d-md-block" style="z-index:1;">
            <div style="color:rgba(255,255,255,.45);font-size:11px;">Bergabung sejak</div>
            <div style="color:#fff;font-weight:600;font-size:13px;">{{ auth()->user()->created_at->format('d M Y') }}</div>
        </div>
    </div>
</div>

<div class="row g-4 justify-content-center">
<div class="col-lg-7">

    {{-- Tab Navigation --}}
    <div class="card form-card mb-4" style="border-radius:14px;overflow:hidden;">
        <div class="d-flex border-bottom" style="background:#fff;">
            <button class="acc-tab-btn active" onclick="switchTab('profile',this)">
                <i class="fas fa-user-edit"></i> Informasi Akun
            </button>
            <button class="acc-tab-btn" onclick="switchTab('password',this)">
                <i class="fas fa-lock"></i> Ganti Password
            </button>
        </div>

        {{-- Tab: Profile --}}
        <div id="tab-profile" class="acc-tab-pane active p-4">
            <form action="{{ route('admin.account.updateProfile') }}" method="POST">
                @csrf @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="field-icon-wrap">
                        <i class="fas fa-user field-icon"></i>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->name) }}"
                               placeholder="Nama lengkap Anda" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                    <div class="field-icon-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', auth()->user()->email) }}"
                               placeholder="email@domain.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold" style="border-radius:10px;">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab: Password --}}
        <div id="tab-password" class="acc-tab-pane p-4">
            <form action="{{ route('admin.account.updatePassword') }}" method="POST" id="formPassword">
                @csrf @method('PUT')

                {{-- Password saat ini --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-right:none;"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="current_password" id="currentPassword"
                               class="form-control border-start-0 @error('current_password') is-invalid @enderror"
                               placeholder="••••••••" autocomplete="current-password"
                               style="border-left:none !important;">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('currentPassword',this)">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Password baru --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-right:none;"><i class="fas fa-key text-muted"></i></span>
                        <input type="password" name="password" id="newPassword"
                               class="form-control border-start-0 @error('password') is-invalid @enderror"
                               placeholder="Min. 8 karakter" autocomplete="new-password"
                               oninput="checkStrength(this.value)"
                               style="border-left:none !important;">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('newPassword',this)">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    {{-- Strength indicator --}}
                    <div class="strength-bar mt-2"><div class="strength-bar-inner" id="strengthBar"></div></div>
                    <div class="strength-label text-muted" id="strengthLabel">Masukkan password baru</div>
                </div>

                {{-- Konfirmasi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-right:none;"><i class="fas fa-check-circle text-muted"></i></span>
                        <input type="password" name="password_confirmation" id="confirmPassword"
                               class="form-control border-start-0"
                               placeholder="Ulangi password baru" autocomplete="new-password"
                               style="border-left:none !important;">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('confirmPassword',this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn py-2 fw-semibold text-dark"
                            style="background:var(--yellow,#F5C518);border-radius:10px;border:none;">
                        <i class="fas fa-key me-2"></i>Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info card --}}
    <div class="card form-card" style="border-radius:14px;">
        <div class="fcard-header" style="border-radius:14px 14px 0 0;"><i class="fas fa-info-circle me-2"></i>Ringkasan Akun</div>
        <div class="fcard-body py-2">
            <div class="acc-info-item">
                <div class="acc-info-icon"><i class="fas fa-user"></i></div>
                <div>
                    <div class="acc-info-label">Nama</div>
                    <div class="acc-info-val">{{ auth()->user()->name }}</div>
                </div>
            </div>
            <div class="acc-info-item">
                <div class="acc-info-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="acc-info-label">Email</div>
                    <div class="acc-info-val">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="acc-info-item">
                <div class="acc-info-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="acc-info-label">Terakhir Login</div>
                    <div class="acc-info-val">{{ now()->format('d M Y, H:i') }} WIB</div>
                </div>
            </div>
            <div class="acc-info-item">
                <div class="acc-info-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="acc-info-label">Role</div>
                    <div class="acc-info-val"><span class="badge" style="background:rgba(0,87,168,.12);color:var(--blue,#0057A8);font-size:12px;padding:4px 10px;">Administrator</span></div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
/* Tab switching */
function switchTab(tab, btn) {
    document.querySelectorAll('.acc-tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.acc-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}

/* Auto-open password tab if there are password errors */
@if($errors->has('current_password') || $errors->has('password'))
window.addEventListener('DOMContentLoaded', function() {
    switchTab('password', document.querySelectorAll('.acc-tab-btn')[1]);
});
@endif

/* Toggle password visibility */
function togglePwd(id, btn) {
    var input = document.getElementById(id);
    var icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

/* Password strength meter */
function checkStrength(val) {
    var bar   = document.getElementById('strengthBar');
    var label = document.getElementById('strengthLabel');
    if (!val) { bar.style.width='0'; bar.style.background='#e2e8f0'; label.textContent='Masukkan password baru'; label.style.color='#94a3b8'; return; }
    var score = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    var configs = [
        { w:'20%', color:'#ef4444', text:'Sangat Lemah' },
        { w:'40%', color:'#f97316', text:'Lemah' },
        { w:'60%', color:'#eab308', text:'Cukup' },
        { w:'80%', color:'#22c55e', text:'Kuat' },
        { w:'100%', color:'#16a34a', text:'Sangat Kuat' },
    ];
    var cfg = configs[Math.min(score - 1, 4)] || configs[0];
    bar.style.width = cfg.w;
    bar.style.background = cfg.color;
    label.textContent = cfg.text;
    label.style.color = cfg.color;
}
</script>
@endpush
