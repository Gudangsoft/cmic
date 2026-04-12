@extends('layouts.app')
@section('title', 'Kontak Kami - PT. CMIC')
@section('content')
<div class="page-header">
    <div class="container">
        <h1>Kontak Kami</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Kontak Kami</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-5">
            <div class="col-lg-4">
                <div class="contact-info-box mb-4">
                    <h4 class="mb-4">Informasi Kontak</h4>
                    <p><i class="fas fa-map-marker-alt me-3" style="color:var(--cmic-yellow)"></i>
                        {{ \App\Models\Setting::get('company_address', 'Jakarta, Indonesia') }}</p>
                    <p><i class="fas fa-phone me-3" style="color:var(--cmic-yellow)"></i>
                        {{ \App\Models\Setting::get('company_phone', '-') }}</p>
                    <p><i class="fas fa-envelope me-3" style="color:var(--cmic-yellow)"></i>
                        {{ \App\Models\Setting::get('company_email', '-') }}</p>
                    <hr style="border-color:rgba(255,255,255,0.2)">
                    <div class="d-flex gap-3 mt-3">
                        @if(\App\Models\Setting::get('facebook'))
                            <a href="{{ \App\Models\Setting::get('facebook') }}" target="_blank" style="color:var(--cmic-yellow); font-size:20px;"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if(\App\Models\Setting::get('instagram'))
                            <a href="{{ \App\Models\Setting::get('instagram') }}" target="_blank" style="color:var(--cmic-yellow); font-size:20px;"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(\App\Models\Setting::get('linkedin'))
                            <a href="{{ \App\Models\Setting::get('linkedin') }}" target="_blank" style="color:var(--cmic-yellow); font-size:20px;"><i class="fab fa-linkedin"></i></a>
                        @endif
                    </div>
                </div>

                @if(\App\Models\Setting::get('maps_embed'))
                <div class="rounded overflow-hidden shadow">
                    {!! \App\Models\Setting::get('maps_embed') !!}
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 p-lg-5">
                    <h4 style="color:var(--cmic-blue); font-weight:700;" class="mb-4">Kirim Pesan</h4>
                    <form action="{{ route('kontak.store') }}" method="POST">
                        @csrf
                        {{-- Honeypot: invisible to humans, bots will fill this --}}
                        <div style="position:absolute;left:-9999px;top:-9999px;opacity:0;height:0;overflow:hidden;" aria-hidden="true" tabindex="-1">
                            <label for="website_url">Website</label>
                            <input type="text" id="website_url" name="website_url" autocomplete="off" tabindex="-1">
                        </div>
                        {{-- Time token: detect submissions faster than 3 seconds --}}
                        <input type="hidden" name="form_token" value="{{ base64_encode(time() . '|' . csrf_token()) }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama Anda">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@contoh.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+62xxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Subjek pesan">
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Tuliskan pesan Anda di sini...">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-cmic px-5 py-2">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
