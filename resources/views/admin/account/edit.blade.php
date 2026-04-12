@extends('layouts.admin')
@section('title','Pengaturan Akun') @section('page-title','Pengaturan Akun')
@section('breadcrumb')<li class="breadcrumb-item active">Pengaturan Akun</li>@endsection

@push('styles')
<style>
/* ===== PROFILE SIDEBAR ===== */
.acc-profile-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.10);
    position: sticky;
    top: 80px;
}
.acc-profile-banner {
    background: linear-gradient(135deg, var(--dark-blue, #003A78) 0%, var(--blue, #0057A8) 60%, #1a7bbf 100%);
    height: 100px;
    position: relative;
    overflow: hidden;
}
.acc-profile-banner::before {
    content: '';
    position: absolute; top: -30px; right: -30px;
    width: 140px; height: 140px; border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.acc-profile-banner::after {
    content: '';
    position: absolute; bottom: -50px; left: -20px;
    width: 160px; height: 160px; border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.acc-avatar-wrap {
    display: flex; justify-content: center;
    margin-top: -42px;
    position: relative; z-index: 2;
}
.acc-avatar {
    width: 84px; height: 84px; border-radius: 50%;
    background: linear-gradient(135deg, var(--yellow,#F5C518), #e6a800);
    color: #1a2a4a;
    font-size: 32px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    border: 4px solid #fff;
    box-shadow: 0 6px 20px rgba(0,0,0,.18);
    letter-spacing: -1px;
}
.acc-profile-body { padding: 12px 24px 24px; text-align: center; }
.acc-profile-name { font-size: 17px; font-weight: 700; color: #1e293b; margin: 0; }
.acc-profile-email { font-size: 12.5px; color: #94a3b8; margin: 3px 0 12px; }
.acc-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(0,87,168,.09);
    color: var(--blue, #0057A8);
    font-size: 11px; font-weight: 600;
    padding: 4px 14px; border-radius: 20px;
    border: 1px solid rgba(0,87,168,.15);
}
.acc-divider { border: none; border-top: 1px solid #f1f5f9; margin: 16px 0; }
.acc-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.acc-stat-box {
    background: #f8faff; border-radius: 12px; padding: 12px 10px; text-align: center;
    border: 1px solid #e8eef7;
}
.acc-stat-box .stat-ico {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; margin: 0 auto 6px;
}
.acc-stat-box .stat-val { font-size: 13px; font-weight: 700; color: #1e293b; line-height: 1; }
.acc-stat-box .stat-lbl { font-size: 10.5px; color: #94a3b8; margin-top: 2px; }

.acc-meta-list { list-style: none; padding: 0; margin: 0; text-align: left; }
.acc-meta-list li {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}
.acc-meta-list li:last-child { border-bottom: none; padding-bottom: 0; }
.acc-meta-ico {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; flex-shrink: 0;
}
.acc-meta-list .meta-label { font-size: 10.5px; color: #94a3b8; display: block; }
.acc-meta-list .meta-val { color: #334155; font-weight: 500; font-size: 12.5px; }

.btn-logout-acc {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 9px; border-radius: 10px; border: none;
    background: rgba(239,68,68,.08); color: #ef4444; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all .2s; text-decoration: none;
    margin-top: 16px;
}
.btn-logout-acc:hover { background: #ef4444; color: #fff; }

/* ===== FORM CARD ===== */
.acc-form-card {
    border: none; border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    overflow: hidden;
    background: #fff;
}
.acc-card-header {
    padding: 20px 28px 0;
    background: #fff;
}
.acc-card-header h5 { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
.acc-card-header p { font-size: 12.5px; color: #94a3b8; margin: 3px 0 0; }

.acc-tab-strip {
    display: flex; gap: 0;
    border-bottom: 2px solid #f1f5f9;
    padding: 0 28px;
    margin-top: 16px;
}
.acc-tab-btn {
    background: none; border: none;
    border-bottom: 2.5px solid transparent;
    margin-bottom: -2px;
    padding: 10px 18px 12px;
    font-size: 13px; font-weight: 500;
    color: #94a3b8; cursor: pointer;
    display: flex; align-items: center; gap: 7px;
    transition: all .2s; border-radius: 0;
}
.acc-tab-btn.active { color: var(--blue,#0057A8); border-bottom-color: var(--blue,#0057A8); font-weight: 600; }
.acc-tab-btn:hover:not(.active) { color: #475569; background: #f8fafc; }

.acc-tab-pane { display: none; }
.acc-tab-pane.active { display: block; animation: fadeUp .25s ease; }
@keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

.acc-form-body { padding: 24px 28px 28px; }

/* ===== FIELD STYLING ===== */
.field-group { margin-bottom: 20px; }
.field-group label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
.field-group .form-control, .field-group .form-select {
    border-radius: 10px; border: 1.5px solid #e2e8f0;
    font-size: 13.5px; padding: 10px 14px;
    color: #1e293b; transition: border-color .2s, box-shadow .2s;
}
.field-group .form-control:focus {
    border-color: var(--blue,#0057A8);
    box-shadow: 0 0 0 3.5px rgba(0,87,168,.12);
}
.field-group .form-control.is-invalid { border-color: #ef4444; }
.field-icon-wrap { position: relative; }
.field-icon-wrap .fi { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; pointer-events: none; z-index:5; }
.field-icon-wrap .form-control { padding-left: 38px; }
.field-icon-wrap .btn-eye {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #94a3b8; padding: 4px 6px; z-index:5;
    cursor: pointer; border-radius: 6px; font-size: 13px;
}
.field-icon-wrap .btn-eye:hover { color: var(--blue,#0057A8); background: rgba(0,87,168,.06); }
.field-icon-wrap .form-control.has-eye { padding-right: 40px; }

/* ===== STRENGTH BAR ===== */
.strength-bar { height: 4px; border-radius: 4px; background: #e2e8f0; margin-top: 8px; overflow: hidden; }
.strength-bar-inner { height: 100%; border-radius: 4px; transition: width .3s, background .3s; width: 0; }
.strength-chips { display: flex; gap: 5px; margin-top: 8px; flex-wrap: wrap; }
.strength-chip { font-size: 10.5px; padding: 2px 8px; border-radius: 20px; border: 1px solid transparent; font-weight: 500; color: #94a3b8; background: #f1f5f9; }
.strength-chip.met { background: rgba(34,197,94,.1); color: #16a34a; border-color: rgba(34,197,94,.2); }

/* ===== BUTTONS ===== */
.btn-save {
    width: 100%; padding: 11px; border-radius: 12px; border: none;
    font-size: 14px; font-weight: 700; letter-spacing: .3px;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    cursor: pointer; transition: all .2s;
}
.btn-save-primary { background: var(--blue,#0057A8); color: #fff; }
.btn-save-primary:hover { background: var(--dark-blue,#003A78); }
.btn-save-yellow { background: linear-gradient(135deg,#f5c518,#e6a800); color: #1a2a4a; }
.btn-save-yellow:hover { filter: brightness(.94); }

/* ===== ALERT ===== */
.acc-alert {
    border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
    border: none;
}
.acc-alert-success { background: rgba(34,197,94,.1); color: #166534; }
.acc-alert-danger  { background: rgba(239,68,68,.1); color: #b91c1c; }

/* ===== TIPS BOX ===== */
.tips-box {
    background: linear-gradient(135deg, rgba(0,87,168,.05), rgba(0,58,120,.04));
    border: 1px solid rgba(0,87,168,.12);
    border-radius: 12px; padding: 14px 16px;
    margin-top: 20px;
}
.tips-box h6 { font-size: 12px; font-weight: 700; color: var(--blue,#0057A8); margin: 0 0 8px; text-transform: uppercase; letter-spacing: .5px; }
.tips-box ul { margin: 0; padding-left: 18px; }
.tips-box ul li { font-size: 12px; color: #64748b; margin-bottom: 4px; }
.tips-box ul li:last-child { margin-bottom: 0; }

@media (max-width:991px) {
    .acc-profile-card { position: static; }
}
.acc-hero { display: none; } /* hide old hero if remnant */
</style>
@endpush

@section('content')

<div class="row g-4 align-items-start">

{{-- ============ LEFT: PROFILE SIDEBAR ============ --}}
<div class="col-lg-4 col-xl-3">
    <div class="acc-profile-card">
        {{-- Banner --}}
        <div class="acc-profile-banner">
            <div style="position:absolute;top:12px;right:14px;z-index:2;">
                <span class="acc-badge" style="background:rgba(245,197,24,.2);color:var(--yellow,#F5C518);border-color:rgba(245,197,24,.3);">
                    <i class="fas fa-shield-alt"></i> Administrator
                </span>
            </div>
        </div>

        {{-- Avatar --}}
        <div class="acc-avatar-wrap">
            <div class="acc-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        </div>

        {{-- Info --}}
        <div class="acc-profile-body">
            <p class="acc-profile-name">{{ auth()->user()->name }}</p>
            <p class="acc-profile-email">{{ auth()->user()->email }}</p>

            <hr class="acc-divider">

            {{-- Stats --}}
            <div class="acc-stat-grid">
                <div class="acc-stat-box">
                    <div class="stat-ico" style="background:rgba(0,87,168,.1);color:var(--blue,#0057A8);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-val">{{ auth()->user()->created_at->format('Y') }}</div>
                    <div class="stat-lbl">Tahun Bergabung</div>
                </div>
                <div class="acc-stat-box">
                    <div class="stat-ico" style="background:rgba(34,197,94,.1);color:#16a34a;">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <div class="stat-val">Aktif</div>
                    <div class="stat-lbl">Status Akun</div>
                </div>
            </div>

            <hr class="acc-divider">

            {{-- Meta --}}
            <ul class="acc-meta-list">
                <li>
                    <div class="acc-meta-ico" style="background:rgba(0,87,168,.08);color:var(--blue,#0057A8);">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <span class="meta-label">Nama Lengkap</span>
                        <span class="meta-val">{{ auth()->user()->name }}</span>
                    </div>
                </li>
                <li>
                    <div class="acc-meta-ico" style="background:rgba(245,158,11,.08);color:#d97706;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <span class="meta-label">Email</span>
                        <span class="meta-val" style="word-break:break-all;">{{ auth()->user()->email }}</span>
                    </div>
                </li>
                <li>
                    <div class="acc-meta-ico" style="background:rgba(139,92,246,.08);color:#7c3aed;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <span class="meta-label">Bergabung Sejak</span>
                        <span class="meta-val">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </li>
                <li>
                    <div class="acc-meta-ico" style="background:rgba(34,197,94,.08);color:#16a34a;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <span class="meta-label">Waktu Sekarang</span>
                        <span class="meta-val">{{ now()->format('d M Y, H:i') }} WIB</span>
                    </div>
                </li>
            </ul>

            {{-- Logout --}}
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn-logout-acc">
                    <i class="fas fa-sign-out-alt"></i> Keluar dari Akun
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ============ RIGHT: FORM AREA ============ --}}
<div class="col-lg-8 col-xl-9">
    <div class="acc-form-card">
        <div class="acc-card-header">
            <h5><i class="fas fa-user-cog me-2" style="color:var(--blue,#0057A8);"></i>Pengaturan Akun</h5>
            <p>Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        {{-- Tab Strip --}}
        <div class="acc-tab-strip">
            <button class="acc-tab-btn active" onclick="switchTab('profile',this)">
                <i class="fas fa-id-card"></i> Informasi Profil
            </button>
            <button class="acc-tab-btn" onclick="switchTab('password',this)">
                <i class="fas fa-lock"></i> Ubah Password
            </button>
        </div>

        {{-- ====== TAB: PROFILE ====== --}}
        <div id="tab-profile" class="acc-tab-pane active">
            <div class="acc-form-body">

                @if(session('success') && !$errors->has('current_password') && !$errors->has('password'))
                <div class="acc-alert acc-alert-success">
                    <i class="fas fa-circle-check fa-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->has('name') || $errors->has('email'))
                <div class="acc-alert acc-alert-danger">
                    <i class="fas fa-circle-exclamation fa-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('admin.account.updateProfile') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="field-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="field-icon-wrap">
                                    <i class="fas fa-user fi"></i>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name) }}"
                                           placeholder="Nama lengkap Anda" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group">
                                <label>Alamat Email <span class="text-danger">*</span></label>
                                <div class="field-icon-wrap">
                                    <i class="fas fa-envelope fi"></i>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}"
                                           placeholder="email@domain.com" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group">
                                <label>Role</label>
                                <div class="field-icon-wrap">
                                    <i class="fas fa-shield-alt fi"></i>
                                    <input type="text" class="form-control" value="Administrator" disabled readonly style="background:#f8fafc;color:#94a3b8;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group">
                                <label>Status Akun</label>
                                <div class="field-icon-wrap">
                                    <i class="fas fa-circle-check fi" style="color:#16a34a;"></i>
                                    <input type="text" class="form-control" value="Aktif" disabled readonly style="background:#f0fdf4;color:#16a34a;font-weight:600;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-2">
                        <button type="submit" class="btn-save btn-save-primary" style="width:auto;padding:11px 36px;">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <button type="reset" class="btn" style="border-radius:12px;padding:11px 24px;font-size:14px;border:1.5px solid #e2e8f0;color:#64748b;">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </form>

                <div class="tips-box">
                    <h6><i class="fas fa-lightbulb me-1"></i> Tips</h6>
                    <ul>
                        <li>Gunakan nama lengkap sesuai identitas resmi Anda.</li>
                        <li>Pastikan alamat email aktif karena digunakan untuk login.</li>
                        <li>Perubahan email akan langsung berlaku untuk login berikutnya.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ====== TAB: PASSWORD ====== --}}
        <div id="tab-password" class="acc-tab-pane">
            <div class="acc-form-body">

                @if(session('success') && ($errors->has('current_password') || request()->is('*account*')))
                <div class="acc-alert acc-alert-success">
                    <i class="fas fa-circle-check fa-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->has('current_password') || $errors->has('password'))
                <div class="acc-alert acc-alert-danger">
                    <i class="fas fa-circle-exclamation fa-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('admin.account.updatePassword') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="field-group">
                        <label>Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="field-icon-wrap">
                            <i class="fas fa-lock fi"></i>
                            <input type="password" name="current_password" id="currentPassword"
                                   class="form-control has-eye @error('current_password') is-invalid @enderror"
                                   placeholder="Masukkan password saat ini" autocomplete="current-password">
                            <button type="button" class="btn-eye" onclick="togglePwd('currentPassword',this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="field-group">
                                <label>Password Baru <span class="text-danger">*</span></label>
                                <div class="field-icon-wrap">
                                    <i class="fas fa-key fi"></i>
                                    <input type="password" name="password" id="newPassword"
                                           class="form-control has-eye @error('password') is-invalid @enderror"
                                           placeholder="Min. 8 karakter" autocomplete="new-password"
                                           oninput="checkStrength(this.value)">
                                    <button type="button" class="btn-eye" onclick="togglePwd('newPassword',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="strength-bar"><div class="strength-bar-inner" id="strengthBar"></div></div>
                                <div class="strength-chips" id="strengthChips">
                                    <span class="strength-chip" id="chip-len">Min. 8 karakter</span>
                                    <span class="strength-chip" id="chip-upper">Huruf besar</span>
                                    <span class="strength-chip" id="chip-num">Angka</span>
                                    <span class="strength-chip" id="chip-sym">Simbol</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group">
                                <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                <div class="field-icon-wrap">
                                    <i class="fas fa-check-circle fi"></i>
                                    <input type="password" name="password_confirmation" id="confirmPassword"
                                           class="form-control has-eye"
                                           placeholder="Ulangi password baru" autocomplete="new-password"
                                           oninput="checkMatch()">
                                    <button type="button" class="btn-eye" onclick="togglePwd('confirmPassword',this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="matchMsg" style="font-size:11.5px;margin-top:6px;font-weight:500;display:none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-2">
                        <button type="submit" class="btn-save btn-save-yellow" style="width:auto;padding:11px 36px;">
                            <i class="fas fa-key"></i> Perbarui Password
                        </button>
                        <button type="reset" class="btn" onclick="resetStrength()" style="border-radius:12px;padding:11px 24px;font-size:14px;border:1.5px solid #e2e8f0;color:#64748b;">
                            <i class="fas fa-undo me-1"></i> Bersihkan
                        </button>
                    </div>
                </form>

                <div class="tips-box" style="background:linear-gradient(135deg,rgba(245,197,24,.06),rgba(230,168,0,.04));border-color:rgba(245,197,24,.2);">
                    <h6 style="color:#92710a;"><i class="fas fa-shield-alt me-1"></i> Tips Keamanan Password</h6>
                    <ul>
                        <li>Gunakan minimal 8 karakter dengan kombinasi huruf besar, angka, dan simbol.</li>
                        <li>Hindari menggunakan nama, tanggal lahir, atau kata yang mudah ditebak.</li>
                        <li>Jangan gunakan password yang sama di beberapa akun.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</div>{{-- end row --}}
@endsection

@push('scripts')
<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.acc-tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.acc-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}

@if($errors->has('current_password') || $errors->has('password'))
window.addEventListener('DOMContentLoaded', function() {
    switchTab('password', document.querySelectorAll('.acc-tab-btn')[1]);
});
@endif

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

function checkStrength(val) {
    var bar   = document.getElementById('strengthBar');
    var checks = {
        len:   val.length >= 8,
        upper: /[A-Z]/.test(val),
        num:   /[0-9]/.test(val),
        sym:   /[^A-Za-z0-9]/.test(val),
    };
    ['len','upper','num','sym'].forEach(function(k){
        var el = document.getElementById('chip-'+k);
        if (el) el.classList.toggle('met', checks[k]);
    });
    var score = Object.values(checks).filter(Boolean).length + (val.length >= 12 ? 1 : 0);
    var configs = [
        {w:'20%',color:'#ef4444'},{w:'40%',color:'#f97316'},
        {w:'60%',color:'#eab308'},{w:'80%',color:'#22c55e'},{w:'100%',color:'#16a34a'}
    ];
    if (!val) { bar.style.width='0'; return; }
    var c = configs[Math.min(score-1,4)] || configs[0];
    bar.style.width = c.w; bar.style.background = c.color;
    checkMatch();
}

function checkMatch() {
    var np = document.getElementById('newPassword').value;
    var cp = document.getElementById('confirmPassword').value;
    var msg = document.getElementById('matchMsg');
    if (!cp) { msg.style.display='none'; return; }
    msg.style.display = 'block';
    if (np === cp) {
        msg.innerHTML = '<i class="fas fa-check-circle me-1"></i> Password cocok';
        msg.style.color = '#16a34a';
    } else {
        msg.innerHTML = '<i class="fas fa-times-circle me-1"></i> Password tidak cocok';
        msg.style.color = '#ef4444';
    }
}

function resetStrength() {
    var bar = document.getElementById('strengthBar');
    if (bar) { bar.style.width='0'; }
    ['len','upper','num','sym'].forEach(function(k){
        var el = document.getElementById('chip-'+k);
        if (el) el.classList.remove('met');
    });
    var msg = document.getElementById('matchMsg');
    if (msg) msg.style.display='none';
}
</script>
@endpush
