<?php

return [

    'name' => env('APP_NAME', 'SK Traders'),
    'tagline' => 'Curated Watches · Perfumes · Skin Care Serums',

    'whatsapp' => [
        'primary'   => env('WHATSAPP_PRIMARY', '+260970000000'),
        'secondary' => env('WHATSAPP_SECONDARY', ''),
        'enabled'   => env('WHATSAPP_FLOATING_ENABLED', true),
    ],

    'contact' => [
        'email'     => env('CONTACT_EMAIL', 'info@atlanticgardenfurniture.com'),
        'website'   => env('CONTACT_WEBSITE', 'www.atlanticgardenfurniture.com'),
        'instagram' => env('INSTAGRAM_URL', ''),
        'facebook'  => env('FACEBOOK_URL', ''),
        'linkedin'  => env('LINKEDIN_URL', ''),
        'twitter'   => env('TWITTER_URL', ''),
    ],

    'address' => [
        'office'        => 'Lusaka, Zambia',
        'manufacturing' => '',
    ],

];
