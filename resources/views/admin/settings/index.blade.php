@extends('layouts.admin')
@section('title','Pengaturan') @section('page-title','Pengaturan Website')
@section('breadcrumb')<li class="breadcrumb-item active">Pengaturan</li>@endsection
@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">

    {{-- Kiri --}}
    <div class="col-lg-8">
        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-building me-2"></i>Informasi Perusahaan</div>
            <div class="fcard-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $settings['company_name'] ?? 'PT. Citra Muda Indo Consultant') }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Tagline / Slogan</label>
                        <input type="text" name="company_tagline" class="form-control" value="{{ old('company_tagline', $settings['company_tagline'] ?? '') }}" placeholder="Tagline perusahaan">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Kantor</label>
                        <textarea name="company_address" rows="2" class="form-control" placeholder="Alamat lengkap...">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. Telepon / Fax</label>
                        <div class="input-group"><span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}" placeholder="(021) 000-0000"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-whatsapp text-success me-1"></i>No. WhatsApp <span class="badge bg-success" style="font-size:10px;">Tombol Floating</span></label>
                        <div class="input-group"><span class="input-group-text" style="background:#25D366;border-color:#25D366;color:#fff;"><i class="fab fa-whatsapp"></i></span>
                        <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" placeholder="628123456789">
                        </div>
                        <small class="text-muted">Format internasional tanpa +, cth: <strong>628123456789</strong></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email Perusahaan</label>
                        <div class="input-group"><span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="company_email" class="form-control" value="{{ old('company_email', $settings['company_email'] ?? '') }}" placeholder="info@perusahaan.com"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tentang Perusahaan (Halaman Beranda)</label>
                        <textarea name="company_about" rows="5" class="form-control tinymce-editor" placeholder="Paragraf singkat tentang perusahaan...">{{ old('company_about', $settings['company_about'] ?? '') }}</textarea>
                        <small class="text-muted">Teks ini akan tampil di bagian "Tentang" halaman beranda.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-share-alt me-2"></i>Media Sosial</div>
            <div class="fcard-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-facebook text-primary me-1"></i>Facebook</label>
                        <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $settings['facebook'] ?? '') }}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-instagram text-danger me-1"></i>Instagram</label>
                        <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $settings['instagram'] ?? '') }}" placeholder="https://instagram.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-linkedin text-info me-1"></i>LinkedIn</label>
                        <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}" placeholder="https://linkedin.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-youtube text-danger me-1"></i>YouTube</label>
                        <input type="url" name="youtube" class="form-control" value="{{ old('youtube', $settings['youtube'] ?? '') }}" placeholder="https://youtube.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-twitter text-info me-1"></i>Twitter / X</label>
                        <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $settings['twitter'] ?? '') }}" placeholder="https://twitter.com/...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card form-card">
            <div class="fcard-header"><i class="fas fa-map-marked-alt me-2"></i>Google Maps</div>
            <div class="fcard-body">
                <label class="form-label">Embed Code Google Maps</label>
                <textarea name="maps_embed" rows="3" class="form-control" placeholder='&lt;iframe src="https://maps.google.com/..." ...&gt;&lt;/iframe&gt;'>{{ old('maps_embed', $settings['maps_embed'] ?? '') }}</textarea>
                <small class="text-muted">Dapatkan kode embed dari Google Maps &rarr; Share &rarr; Embed a map.</small>
            </div>
        </div>

        <div class="card form-card mt-4">
            <div class="fcard-header"><i class="fas fa-envelope me-2"></i>Konfigurasi Email (SMTP)</div>
            <div class="fcard-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Mail Driver</label>
                        <select name="mail_driver" class="form-select">
                            <option value="">-- Pilih Driver --</option>
                            <option value="smtp" {{ old('mail_driver', $settings['mail_driver'] ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ old('mail_driver', $settings['mail_driver'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ old('mail_driver', $settings['mail_driver'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mail Host</label>
                        <input type="text" name="mail_host" class="form-control" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" placeholder="smtp.gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mail Port</label>
                        <input type="number" name="mail_port" class="form-control" value="{{ old('mail_port', $settings['mail_port'] ?? '') }}" placeholder="587">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Encryption</label>
                        <select name="mail_encryption" class="form-select">
                            <option value="">-- None --</option>
                            <option value="tls" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username / Email</label>
                        <input type="text" name="mail_username" class="form-control" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}" placeholder="your-email@gmail.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password / App Password</label>
                        <input type="password" name="mail_password" class="form-control" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}" placeholder="••••••••">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Address</label>
                        <input type="email" name="mail_from_address" class="form-control" value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="noreply@perusahaan.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Name</label>
                        <input type="text" name="mail_from_name" class="form-control" value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" placeholder="PT. Citra Muda Indo Consultant">
                    </div>
                </div>
            </div>
        </div>

        <div class="card form-card mt-4">
            <div class="fcard-header"><i class="fas fa-comment-dots me-2"></i>Gateway SMS</div>
            <div class="fcard-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">SMS Gateway Provider</label>
                        <select name="sms_gateway" class="form-select">
                            <option value="">-- Pilih Gateway --</option>
                            <option value="twilio" {{ old('sms_gateway', $settings['sms_gateway'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                            <option value="nexmo" {{ old('sms_gateway', $settings['sms_gateway'] ?? '') === 'nexmo' ? 'selected' : '' }}>Nexmo (Vonage)</option>
                            <option value="infobip" {{ old('sms_gateway', $settings['sms_gateway'] ?? '') === 'infobip' ? 'selected' : '' }}>Infobip</option>
                            <option value="clickatell" {{ old('sms_gateway', $settings['sms_gateway'] ?? '') === 'clickatell' ? 'selected' : '' }}>Clickatell</option>
                            <option value="aws_sns" {{ old('sms_gateway', $settings['sms_gateway'] ?? '') === 'aws_sns' ? 'selected' : '' }}>AWS SNS</option>
                            <option value="custom" {{ old('sms_gateway', $settings['sms_gateway'] ?? '') === 'custom' ? 'selected' : '' }}>Custom API</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">API Key</label>
                        <input type="password" name="sms_api_key" class="form-control" value="{{ old('sms_api_key', $settings['sms_api_key'] ?? '') }}" placeholder="API Key dari provider SMS">
                    </div>
                    <div class="col-12">
                        <label class="form-label">API Secret / Token</label>
                        <input type="password" name="sms_api_secret" class="form-control" value="{{ old('sms_api_secret', $settings['sms_api_secret'] ?? '') }}" placeholder="Secret / Token dari provider SMS">
                    </div>
                </div>
            </div>
        </div>

        <div class="card form-card mt-4">
            <div class="fcard-header"><i class="fab fa-whatsapp me-2"></i>Gateway WhatsApp</div>
            <div class="fcard-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">WhatsApp Gateway Provider</label>
                        <select name="wa_gateway" class="form-select">
                            <option value="">-- Pilih Gateway --</option>
                            <option value="twilio" {{ old('wa_gateway', $settings['wa_gateway'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                            <option value="whatsapp_business" {{ old('wa_gateway', $settings['wa_gateway'] ?? '') === 'whatsapp_business' ? 'selected' : '' }}>WhatsApp Business API</option>
                            <option value="interakt" {{ old('wa_gateway', $settings['wa_gateway'] ?? '') === 'interakt' ? 'selected' : '' }}>Interakt</option>
                            <option value="fonnte" {{ old('wa_gateway', $settings['wa_gateway'] ?? '') === 'fonnte' ? 'selected' : '' }}>Fonnte</option>
                            <option value="wuzapi" {{ old('wa_gateway', $settings['wa_gateway'] ?? '') === 'wuzapi' ? 'selected' : '' }}>WuzAPI</option>
                            <option value="custom" {{ old('wa_gateway', $settings['wa_gateway'] ?? '') === 'custom' ? 'selected' : '' }}>Custom API</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">API URL</label>
                        <input type="url" name="wa_api_url" class="form-control" value="{{ old('wa_api_url', $settings['wa_api_url'] ?? '') }}" placeholder="https://api.example.com/send">
                    </div>
                    <div class="col-12">
                        <label class="form-label">API Key / Token</label>
                        <input type="password" name="wa_api_key" class="form-control" value="{{ old('wa_api_key', $settings['wa_api_key'] ?? '') }}" placeholder="API Key dari provider WhatsApp">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kanan --}}
    <div class="col-lg-4">
        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-image me-2"></i>Logo Perusahaan</div>
            <div class="fcard-body">
                <div class="text-center mb-3">
                    @if(!empty($settings['company_logo']))
                    <img src="{{ asset('storage/'.$settings['company_logo']) }}" style="max-height:80px;object-fit:contain;border:1px solid #e2e8f0;border-radius:8px;padding:8px;background:#fff;" id="logoPreviewImg">
                    @else
                    <div style="height:80px;display:flex;align-items:center;justify-content:center;background:#f8fafc;border-radius:8px;border:2px dashed #d1d5db;" id="logoPreviewImg">
                        <span style="font-size:12px;color:#94a3b8;">Belum ada logo</span>
                    </div>
                    @endif
                </div>
                <label class="form-label">Upload Logo Baru</label>
                <input type="file" name="company_logo" class="form-control" accept="image/*" onchange="previewLogoFile(this)">
                <small class="text-muted d-block mt-1">Format: PNG (transparan) atau JPG. Maks. 2MB.</small>
            </div>
        </div>

        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-globe me-2"></i>Favicon</div>
            <div class="fcard-body">
                <div class="text-center mb-3">
                    @if(!empty($settings['company_favicon']))
                    <img src="{{ asset('storage/'.$settings['company_favicon']) }}" style="width:48px;height:48px;object-fit:contain;border:1px solid #e2e8f0;border-radius:6px;padding:6px;background:#fff;" id="faviconPreviewImg">
                    @else
                    <div style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:#f8fafc;border-radius:6px;border:2px dashed #d1d5db;margin:0 auto;" id="faviconPreviewImg">
                        <i class="fas fa-globe" style="color:#94a3b8;"></i>
                    </div>
                    @endif
                </div>
                <label class="form-label">Upload Favicon</label>
                <input type="file" name="company_favicon" class="form-control" accept="image/x-icon,image/png,image/gif,image/*" onchange="previewFaviconFile(this)">
                <small class="text-muted d-block mt-1">
                    Format: <strong>.ico</strong> atau <strong>.png</strong> (disarankan 32×32 atau 64×64 px). Maks. 512KB.<br>
                    Favicon tampil di tab browser sebagai ikon website.
                </small>
            </div>
        </div>

        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-palette me-2"></i>Tema Warna Website</div>
            <div class="fcard-body">
                <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i>Warna akan diterapkan di seluruh halaman website dan panel admin.</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Warna Utama (Primary)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="theme_color_primary" id="colorPrimary"
                                   class="form-control form-control-color"
                                   value="{{ old('theme_color_primary', $settings['theme_color_primary'] ?? '#0057A8') }}"
                                   style="width:48px;height:38px;padding:2px;cursor:pointer;">
                            <input type="text" id="colorPrimaryText"
                                   class="form-control form-control-sm"
                                   value="{{ old('theme_color_primary', $settings['theme_color_primary'] ?? '#0057A8') }}"
                                   placeholder="#0057A8" maxlength="7"
                                   oninput="syncColor(this,'colorPrimary')">
                        </div>
                        <small class="text-muted">Warna tombol, link, header utama.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Warna Gelap (Secondary)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="theme_color_secondary" id="colorSecondary"
                                   class="form-control form-control-color"
                                   value="{{ old('theme_color_secondary', $settings['theme_color_secondary'] ?? '#003A78') }}"
                                   style="width:48px;height:38px;padding:2px;cursor:pointer;">
                            <input type="text" id="colorSecondaryText"
                                   class="form-control form-control-sm"
                                   value="{{ old('theme_color_secondary', $settings['theme_color_secondary'] ?? '#003A78') }}"
                                   placeholder="#003A78" maxlength="7"
                                   oninput="syncColor(this,'colorSecondary')">
                        </div>
                        <small class="text-muted">Warna sidebar, hover, judul.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Warna Aksen (Accent)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="theme_color_accent" id="colorAccent"
                                   class="form-control form-control-color"
                                   value="{{ old('theme_color_accent', $settings['theme_color_accent'] ?? '#F5C518') }}"
                                   style="width:48px;height:38px;padding:2px;cursor:pointer;">
                            <input type="text" id="colorAccentText"
                                   class="form-control form-control-sm"
                                   value="{{ old('theme_color_accent', $settings['theme_color_accent'] ?? '#F5C518') }}"
                                   placeholder="#F5C518" maxlength="7"
                                   oninput="syncColor(this,'colorAccent')">
                        </div>
                        <small class="text-muted">Warna garis aksen, badge, divider.</small>
                    </div>
                </div>
                <div class="mt-3 p-3 rounded" style="background:#f8faff;border:1px solid #e3eaff;">
                    <div class="small fw-semibold mb-2"><i class="fas fa-eye me-1 text-primary"></i>Preview Warna</div>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <div id="prevPrimary" style="width:80px;height:32px;border-radius:6px;background:{{ $settings['theme_color_primary'] ?? '#0057A8' }};display:flex;align-items:center;justify-content:center;">
                            <span style="color:#fff;font-size:11px;font-weight:600;">Primary</span>
                        </div>
                        <div id="prevSecondary" style="width:80px;height:32px;border-radius:6px;background:{{ $settings['theme_color_secondary'] ?? '#003A78' }};display:flex;align-items:center;justify-content:center;">
                            <span style="color:#fff;font-size:11px;font-weight:600;">Secondary</span>
                        </div>
                        <div id="prevAccent" style="width:80px;height:32px;border-radius:6px;background:{{ $settings['theme_color_accent'] ?? '#F5C518' }};display:flex;align-items:center;justify-content:center;">
                            <span style="color:#333;font-size:11px;font-weight:600;">Accent</span>
                        </div>
                        <div id="prevGradient" style="width:120px;height:32px;border-radius:6px;background:linear-gradient(135deg,{{ $settings['theme_color_secondary'] ?? '#003A78' }},{{ $settings['theme_color_primary'] ?? '#0057A8' }});"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-mouse-pointer me-2"></i>Teks Tombol Beranda</div>
            <div class="fcard-body">
                <div class="mb-3">
                    <label class="form-label">Tombol "Lihat Semua Layanan"</label>
                    <input type="text" name="btn_layanan_text" class="form-control" value="{{ old('btn_layanan_text', $settings['btn_layanan_text'] ?? 'Lihat Semua Layanan') }}" placeholder="Lihat Semua Layanan" maxlength="100">
                    <small class="text-muted">Teks tombol di bagian Layanan pada halaman beranda.</small>
                </div>
                <div>
                    <label class="form-label">Tombol "Lihat Semua Klien"</label>
                    <input type="text" name="btn_klien_text" class="form-control" value="{{ old('btn_klien_text', $settings['btn_klien_text'] ?? 'Lihat Semua Klien') }}" placeholder="Lihat Semua Klien" maxlength="100">
                    <small class="text-muted">Teks tombol di bagian Klien pada halaman beranda.</small>
                </div>
            </div>
        </div>

        <div class="card form-card mb-4">
            <div class="fcard-header"><i class="fas fa-certificate me-2"></i>SEO & Meta</div>
            <div class="fcard-body">
                <div class="mb-3">
                    <label class="form-label">Meta Deskripsi</label>
                    <textarea name="meta_description" rows="3" class="form-control" placeholder="Deskripsi singkat website untuk mesin pencari...">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" placeholder="konsultan, teknik, sipil, ...">
                </div>
            </div>
        </div>

        <div class="card form-card">
            <div class="fcard-body">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="fas fa-save me-2"></i>Simpan Semua Pengaturan
                </button>
                <p class="text-muted text-center mt-2 mb-0" style="font-size:12px;">Perubahan akan langsung diterapkan ke website.</p>
            </div>
        </div>
    </div>

</div>
</form>

@php $isMaintenance = ($settings['maintenance_mode'] ?? '0') === '1'; @endphp
<form action="{{ route('admin.settings.maintenance') }}" method="POST" class="mt-3">
    @csrf
    <div class="card form-card mb-4 border-0 shadow-sm" style="border-left:4px solid {{ $isMaintenance ? '#ef4444' : '#22c55e' }}!important;">
        <div class="fcard-header d-flex align-items-center gap-2" style="background:{{ $isMaintenance ? '#fef2f2' : '#f0fdf4' }};border-radius:10px 10px 0 0;">
            <span class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                  style="width:34px;height:34px;background:{{ $isMaintenance ? '#ef4444' : '#22c55e' }};">
                <i class="fas {{ $isMaintenance ? 'fa-hard-hat' : 'fa-globe' }} text-white" style="font-size:15px;"></i>
            </span>
            <span class="fw-bold" style="color:{{ $isMaintenance ? '#b91c1c' : '#15803d' }};font-size:14px;">
                Mode Maintenance
            </span>
            @if($isMaintenance)
                <span class="badge bg-danger ms-auto">AKTIF</span>
            @else
                <span class="badge bg-success ms-auto">Normal</span>
            @endif
        </div>
        <div class="fcard-body">
            @if($isMaintenance)
            <div class="alert alert-danger d-flex gap-2 align-items-start py-2 mb-3" style="font-size:12.5px;border-radius:8px;">
                <i class="fas fa-exclamation-triangle mt-1 flex-shrink-0"></i>
                <span><strong>Website dalam mode maintenance!</strong> Pengunjung umum melihat halaman pemeliharaan.</span>
            </div>
            @else
            <div class="alert alert-success d-flex gap-2 align-items-start py-2 mb-3" style="font-size:12.5px;border-radius:8px;">
                <i class="fas fa-check-circle mt-1 flex-shrink-0"></i>
                <span>Website berjalan normal. Aktifkan maintenance saat melakukan update besar.</span>
            </div>
            @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Judul Halaman Maintenance</label>
                    <input type="text" name="maintenance_title" class="form-control form-control-sm"
                           value="{{ old('maintenance_title', $settings['maintenance_title'] ?? 'Sedang Dalam Pemeliharaan') }}"
                           placeholder="Judul yang ditampilkan ke pengunjung">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Pesan untuk Pengunjung</label>
                    <textarea name="maintenance_message" rows="2" class="form-control form-control-sm"
                              placeholder="Pesan penjelasan...">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'Website sedang dalam pemeliharaan. Silakan kunjungi kembali beberapa saat lagi.') }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">IP yang Diizinkan <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="maintenance_allowed_ips" class="form-control form-control-sm"
                           value="{{ old('maintenance_allowed_ips', $settings['maintenance_allowed_ips'] ?? '') }}"
                           placeholder="127.0.0.1, 192.168.1.1">
                    <small class="text-muted">Pisahkan dengan koma.</small>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top gap-2">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1"
                           id="maintenanceToggle" role="switch" style="width:3em;height:1.5em;"
                           {{ $isMaintenance ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="maintenanceToggle" style="color:{{ $isMaintenance ? '#b91c1c' : '#334155' }};">
                        {{ $isMaintenance ? 'Maintenance AKTIF' : 'Maintenance OFF' }}
                    </label>
                </div>
                <button type="submit" class="btn btn-sm {{ $isMaintenance ? 'btn-danger' : 'btn-success' }} px-3 fw-semibold">
                    <i class="fas fa-save me-1"></i>Simpan Mode Maintenance
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script>
function previewLogoFile(input) {
    var f = input.files[0];
    if (!f) return;
    var r = new FileReader();
    r.onload = function(e) {
        var el = document.getElementById('logoPreviewImg');
        el.outerHTML = '<img src="' + e.target.result + '" style="max-height:80px;object-fit:contain;border:1px solid #e2e8f0;border-radius:8px;padding:8px;background:#fff;" id="logoPreviewImg">';
    };
    r.readAsDataURL(f);
}
function previewFaviconFile(input) {
    var f = input.files[0];
    if (!f) return;
    var r = new FileReader();
    r.onload = function(e) {
        var el = document.getElementById('faviconPreviewImg');
        el.outerHTML = '<img src="' + e.target.result + '" style="width:48px;height:48px;object-fit:contain;border:1px solid #e2e8f0;border-radius:6px;padding:6px;background:#fff;" id="faviconPreviewImg">';
    };
    r.readAsDataURL(f);
}
function syncColor(textInput, colorId) {
    var val = textInput.value;
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.getElementById(colorId).value = val;
        updatePreview();
    }
}
document.addEventListener('DOMContentLoaded', function() {
    ['colorPrimary','colorSecondary','colorAccent'].forEach(function(id) {
        document.getElementById(id).addEventListener('input', function() {
            document.getElementById(id + 'Text').value = this.value;
            updatePreview();
        });
    });
});
function updatePreview() {
    var p = document.getElementById('colorPrimary').value;
    var s = document.getElementById('colorSecondary').value;
    var a = document.getElementById('colorAccent').value;
    document.getElementById('prevPrimary').style.background = p;
    document.getElementById('prevSecondary').style.background = s;
    document.getElementById('prevAccent').style.background = a;
    document.getElementById('prevGradient').style.background = 'linear-gradient(135deg,' + s + ',' + p + ')';
}
</script>
@endpush
