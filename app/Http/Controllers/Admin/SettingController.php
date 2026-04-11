<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'          => 'required|string|max:255',
            'company_tagline'       => 'nullable|string|max:255',
            'company_address'       => 'nullable|string',
            'company_phone'         => 'nullable|string|max:50',
            'company_email'         => 'nullable|email|max:255',
            'company_about'         => 'nullable|string',
            'maps_embed'            => 'nullable|string',
            'facebook'              => 'nullable|url|max:255',
            'instagram'             => 'nullable|url|max:255',
            'linkedin'              => 'nullable|url|max:255',
            'youtube'               => 'nullable|url|max:255',
            'twitter'               => 'nullable|url|max:255',
            'whatsapp'              => 'nullable|string|max:20',
            'meta_description'      => 'nullable|string|max:500',
            'meta_keywords'         => 'nullable|string|max:500',
            'theme_color_primary'   => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_color_secondary' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_color_accent'    => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'company_logo'          => 'nullable|image|max:2048',
            'company_favicon'       => 'nullable|file|max:512|mimetypes:image/x-icon,image/vnd.microsoft.icon,image/png,image/gif,image/jpeg',
            'mail_driver'           => 'nullable|string|max:50',
            'mail_host'             => 'nullable|string|max:255',
            'mail_port'             => 'nullable|numeric|min:1|max:65535',
            'mail_username'         => 'nullable|string|max:255',
            'mail_password'         => 'nullable|string|max:255',
            'mail_encryption'       => 'nullable|string|max:50',
            'mail_from_address'     => 'nullable|email|max:255',
            'mail_from_name'        => 'nullable|string|max:255',
            'sms_gateway'           => 'nullable|string|max:50',
            'sms_api_key'           => 'nullable|string|max:500',
            'sms_api_secret'        => 'nullable|string|max:500',
            'wa_gateway'            => 'nullable|string|max:50',
            'wa_api_key'            => 'nullable|string|max:500',
            'wa_api_url'            => 'nullable|url|max:500',
        ]);

        $textFields = [
            'company_name', 'company_tagline', 'company_address', 'company_phone',
            'company_email', 'company_about', 'maps_embed', 'facebook', 'instagram',
            'linkedin', 'youtube', 'twitter', 'whatsapp', 'meta_description', 'meta_keywords',
            'theme_color_primary', 'theme_color_secondary', 'theme_color_accent',
            'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
            'sms_gateway', 'sms_api_key', 'sms_api_secret',
            'wa_gateway', 'wa_api_key', 'wa_api_url',
        ];

        foreach ($textFields as $key) {
            Setting::set($key, $request->input($key));
        }

        if ($request->hasFile('company_logo')) {
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            Setting::set('company_logo', $request->file('company_logo')->store('settings', 'public'));
        }

        if ($request->hasFile('company_favicon')) {
            $oldFav = Setting::get('company_favicon');
            if ($oldFav) Storage::disk('public')->delete($oldFav);
            Setting::set('company_favicon', $request->file('company_favicon')->store('settings', 'public'));
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
