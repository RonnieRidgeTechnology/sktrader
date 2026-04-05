<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public static function keys(): array
    {
        return [
            'header_logo',
            'footer_logo',
            'favicon',
            'primary_color',
            'secondary_color',
            'contact_phone',
            'contact_email',
            'contact_website',
            'contact_instagram',
            'contact_facebook',
            'contact_linkedin',
            'contact_twitter',
            'address_office',
            'address_manufacturing',
            'whatsapp_primary',
            'whatsapp_secondary',
            'whatsapp_enabled',
            'site_name',
            'tagline',
            'map_embed_code',
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_encryption',
            'smtp_from_address',
            'smtp_from_name',
            'maintenance_mode',
            'footer_copyright',
            'google_analytics_id',
            'custom_head_scripts',
            'hero_cta_primary',
            'hero_cta_secondary',
            'contact_form_title',
            'newsletter_enabled',
            'meta_title_default',
            'meta_description_default',
            'og_image',
            'bank_account_details',
            'jazzcash_enabled',
            'jazzcash_sandbox',
            'jazzcash_merchant_id',
            'jazzcash_password',
            'jazzcash_salt',
            'store_currency',
        ];
    }

    public function index()
    {
        $keys = self::keys();
        $settings = [];
        $all = Setting::getAll();
        foreach ($keys as $key) {
            $settings[$key] = $all[$key] ?? '';
        }

        // Merge config defaults so admin sees the same data as on the public Contact page when DB is empty
        $defaults = [
            'site_name' => config('zuaaz.name', ''),
            'tagline' => config('zuaaz.tagline', ''),
            'contact_email' => config('zuaaz.contact.email', ''),
            'contact_website' => config('zuaaz.contact.website', ''),
            'contact_instagram' => config('zuaaz.contact.instagram', ''),
            'contact_facebook' => config('zuaaz.contact.facebook', ''),
            'contact_linkedin' => config('zuaaz.contact.linkedin', ''),
            'contact_twitter' => config('zuaaz.contact.twitter', ''),
            'address_office' => config('zuaaz.address.office', ''),
            'address_manufacturing' => config('zuaaz.address.manufacturing', ''),
            'whatsapp_primary' => config('zuaaz.whatsapp.primary', ''),
            'whatsapp_secondary' => config('zuaaz.whatsapp.secondary', ''),
            'whatsapp_enabled' => config('zuaaz.whatsapp.enabled', true),
            'store_currency' => 'USD',
        ];
        foreach ($defaults as $key => $default) {
            $current = $settings[$key] ?? '';
            if ($current === '' || $current === null) {
                $settings[$key] = is_bool($default) ? ($default ? '1' : '0') : $default;
            }
        }

        $settings['header_logo_url'] = Setting::getStorageUrl($settings['header_logo']);
        $settings['footer_logo_url'] = Setting::getStorageUrl($settings['footer_logo']);
        $settings['favicon_url'] = Setting::getStorageUrl($settings['favicon']);
        $settings['og_image_url'] = Setting::getStorageUrl($settings['og_image']);
        // Never send JazzCash sensitive values to browser unless needed
        $settings['jazzcash_password'] = '';
        $settings['jazzcash_salt'] = '';
        foreach (['maintenance_mode', 'newsletter_enabled', 'jazzcash_enabled', 'jazzcash_sandbox'] as $boolKey) {
            $v = $settings[$boolKey] ?? '';
            $settings[$boolKey] = ($v === '1' || $v === true || $v === 'true');
        }
        return Inertia::render('Admin/Settings/Index', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $rules = [
            'site_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'header_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'contact_phone' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:255',
            'contact_website' => 'nullable|string|max:255',
            'contact_instagram' => 'nullable|string|max:500',
            'contact_facebook' => 'nullable|string|max:500',
            'contact_linkedin' => 'nullable|string|max:500',
            'contact_twitter' => 'nullable|string|max:500',
            'address_office' => 'nullable|string|max:500',
            'address_manufacturing' => 'nullable|string|max:255',
            'whatsapp_primary' => 'nullable|string|max:30',
            'whatsapp_secondary' => 'nullable|string|max:30',
            'whatsapp_enabled' => 'nullable|boolean',
            'map_embed_code' => 'nullable|string|max:2000',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|string|max:10',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|max:10',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            'maintenance_mode' => 'nullable|boolean',
            'footer_copyright' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
            'custom_head_scripts' => 'nullable|string|max:5000',
            'hero_cta_primary' => 'nullable|string|max:120',
            'hero_cta_secondary' => 'nullable|string|max:120',
            'contact_form_title' => 'nullable|string|max:200',
            'newsletter_enabled' => 'nullable|boolean',
            'meta_title_default' => 'nullable|string|max:70',
            'meta_description_default' => 'nullable|string|max:320',
            'og_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'bank_account_details' => 'nullable|string|max:1000',
            'jazzcash_enabled' => 'nullable|boolean',
            'jazzcash_sandbox' => 'nullable|boolean',
            'jazzcash_merchant_id' => 'nullable|string|max:255',
            'jazzcash_password' => 'nullable|string|max:255',
            'jazzcash_salt' => 'nullable|string|max:255',
            'store_currency' => 'required|in:USD,PKR',
        ];

        $validated = $request->validate($rules);

        $booleanKeys = ['whatsapp_enabled', 'maintenance_mode', 'newsletter_enabled', 'jazzcash_enabled', 'jazzcash_sandbox'];
        $fileKeys = ['header_logo', 'footer_logo', 'favicon', 'og_image'];
        $leaveBlankToKeep = ['jazzcash_password', 'jazzcash_salt'];
        foreach (self::keys() as $key) {
            if (in_array($key, $fileKeys, true)) {
                continue;
            }
            $value = $validated[$key] ?? $request->input($key);
            if (in_array($key, $booleanKeys, true)) {
                $value = $request->boolean($key) ? '1' : '0';
            }
            if (in_array($key, $leaveBlankToKeep, true) && ($value === null || $value === '')) {
                continue; // do not overwrite credentials with empty
            }
            if ($key === 'store_currency' && is_string($value)) {
                $value = strtoupper($value);
                if (! in_array($value, ['USD', 'PKR'], true)) {
                    $value = 'USD';
                }
            }
            Setting::set($key, $value === null ? '' : (is_string($value) ? $value : (string) $value));
        }

        if ($request->hasFile('header_logo')) {
            $path = $request->file('header_logo')->store('settings', 'media');
            Setting::set('header_logo', $path);
        }
        if ($request->hasFile('footer_logo')) {
            $path = $request->file('footer_logo')->store('settings', 'media');
            Setting::set('footer_logo', $path);
        }
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'media');
            Setting::set('favicon', $path);
        }
        if ($request->hasFile('og_image')) {
            $existing = Setting::get('og_image');
            if ($existing && Storage::disk('media')->exists($existing)) {
                Storage::disk('media')->delete($existing);
            }
            $path = $request->file('og_image')->store('settings', 'media');
            Setting::set('og_image', $path);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings saved.');
    }

    public function testSmtp(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        $host = Setting::get('smtp_host');
        $port = Setting::get('smtp_port', '587');
        $username = Setting::get('smtp_username');
        $password = Setting::get('smtp_password');
        $encryption = Setting::get('smtp_encryption', 'tls');
        $fromAddress = Setting::get('smtp_from_address') ?: config('mail.from.address');
        $fromName = Setting::get('smtp_from_name') ?: config('mail.from.name');

        if (!$host || !$username || !$password) {
            return back()->with('error', 'Please configure SMTP host, username and password in Settings first.');
        }

        try {
            Config::set('mail.mailers.smtp_custom', [
                'transport' => 'smtp',
                'host' => $host,
                'port' => (int) $port,
                'encryption' => $encryption ?: null,
                'username' => $username,
                'password' => $password,
                'timeout' => null,
            ]);
            Config::set('mail.from', ['address' => $fromAddress, 'name' => $fromName]);

            Mail::mailer('smtp_custom')->raw(
                'This is a test email from ' . config('app.name') . ' Settings. If you received this, SMTP is working.',
                function ($message) use ($request) {
                    $message->to($request->test_email)->subject('SMTP Test – ' . config('app.name'));
                }
            );
            return back()->with('success', 'Test email sent to ' . $request->test_email);
        } catch (\Throwable $e) {
            return back()->with('error', 'SMTP test failed: ' . $e->getMessage());
        }
    }
}
