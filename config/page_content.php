<?php

return [
    'pages' => [
        'about' => [
            'name' => 'About Us',
            'route' => 'about',
            'fields' => [
                ['key' => 'hero_image', 'label' => 'Hero image', 'type' => 'image', 'help' => 'Image shown in the hero section.'],
                ['key' => 'hero_eyebrow', 'label' => 'Hero eyebrow (small label)', 'type' => 'text', 'placeholder' => 'Who we are'],
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text', 'placeholder' => 'About Us'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'placeholder' => 'Short intro under the title'],
                ['key' => 'story_heading', 'label' => '"Our story" section heading', 'type' => 'text', 'placeholder' => 'Our story'],
                ['key' => 'intro', 'label' => 'Our story paragraph', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'meet_owner_heading', 'label' => '"Meet the owner" heading', 'type' => 'text', 'placeholder' => 'Meet the owner'],
                ['key' => 'owner_name', 'label' => 'Owner name', 'type' => 'text', 'placeholder' => 'Mary Daka'],
                ['key' => 'owner_image', 'label' => 'Owner photo', 'type' => 'image', 'help' => 'Photo shown in the Meet the owner section.'],
                ['key' => 'owner_title', 'label' => 'Owner job title (under name)', 'type' => 'text', 'placeholder' => 'Owner, Atlantic Garden Furniture'],
                ['key' => 'owner_bio', 'label' => 'Owner short bio', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'stats_showroom_title', 'label' => 'Stats: Showroom title', 'type' => 'text', 'placeholder' => 'Lusaka showroom'],
                ['key' => 'stats_showroom_desc', 'label' => 'Stats: Showroom description', 'type' => 'text', 'placeholder' => 'Visit us to see and try our furniture'],
                ['key' => 'stats_delivery_title', 'label' => 'Stats: Delivery title', 'type' => 'text', 'placeholder' => 'Nationwide delivery'],
                ['key' => 'stats_delivery_desc', 'label' => 'Stats: Delivery description', 'type' => 'text', 'placeholder' => 'We deliver across Zambia'],
                ['key' => 'stats_quality_title', 'label' => 'Stats: Quality title', 'type' => 'text', 'placeholder' => 'Quality sofas'],
                ['key' => 'stats_quality_desc', 'label' => 'Stats: Quality description', 'type' => 'text', 'placeholder' => 'Durable materials, solid construction'],
                ['key' => 'facilities_heading', 'label' => '"What we offer" heading', 'type' => 'text', 'placeholder' => 'What we offer'],
                ['key' => 'facilities_intro', 'label' => 'What we offer intro line', 'type' => 'textarea', 'rows' => 1, 'placeholder' => 'From our Lusaka base we serve homes and businesses across the country.'],
                ['key' => 'facilities_list', 'label' => 'Locations (one per line: Location: Description)', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'commitment_heading', 'label' => 'Our Commitment heading', 'type' => 'text', 'placeholder' => 'Our Commitment'],
                ['key' => 'commitment_subtitle', 'label' => 'Commitment subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'commitment_items', 'label' => 'Commitment / value items', 'type' => 'steps', 'sub_fields' => ['title', 'body']],
            ],
        ],
        'services' => [
            'name' => 'Collections',
            'route' => 'services',
            'fields' => [
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'steps', 'label' => 'Collection items', 'type' => 'steps', 'sub_fields' => ['label', 'title', 'body', 'bullets']],
            ],
        ],
        'why-choose-us' => [
            'name' => 'Why Choose Us',
            'route' => 'why-choose-us',
            'fields' => [
                ['key' => 'hero_eyebrow', 'label' => 'Hero eyebrow text', 'type' => 'text'],
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'steps', 'label' => 'Reasons / points', 'type' => 'steps', 'sub_fields' => ['label', 'title', 'body', 'bullets']],
            ],
        ],
        'how-it-works' => [
            'name' => 'How It Works',
            'route' => 'how-it-works',
            'fields' => [
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'steps', 'label' => 'Steps', 'type' => 'steps', 'sub_fields' => ['label', 'title', 'body', 'bullets']],
            ],
        ],
        'quality' => [
            'name' => 'Our Quality',
            'route' => 'quality',
            'fields' => [
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'steps', 'label' => 'Quality points', 'type' => 'steps', 'sub_fields' => ['label', 'title', 'body', 'bullets']],
            ],
        ],
        'private-label' => [
            'name' => 'Custom Furniture',
            'route' => 'private-label',
            'fields' => [
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'body', 'label' => 'Main content', 'type' => 'textarea', 'rows' => 8],
            ],
        ],
        'contact' => [
            'name' => 'Contact Us',
            'route' => 'contact',
            'fields' => [
                ['key' => 'hero_eyebrow', 'label' => 'Hero eyebrow', 'type' => 'text', 'placeholder' => 'Get in touch'],
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text', 'placeholder' => 'Contact Us'],
                ['key' => 'hero_subtitle', 'label' => 'Hero subtitle', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'contact_info_heading', 'label' => '"Contact information" heading', 'type' => 'text', 'placeholder' => 'Contact information'],
                ['key' => 'visit_heading', 'label' => '"Visit us" heading', 'type' => 'text', 'placeholder' => 'Visit us'],
                ['key' => 'find_heading', 'label' => '"Find us" heading (map)', 'type' => 'text', 'placeholder' => 'Find us'],
                ['key' => 'quote_heading', 'label' => 'Enquiry form heading', 'type' => 'text', 'placeholder' => 'Send an enquiry'],
                ['key' => 'form_intro_heading', 'label' => 'Form intro heading', 'type' => 'text', 'placeholder' => "Looking for sofas or furniture? We're here to help."],
                ['key' => 'quote_subtitle', 'label' => 'Enquiry form subtitle', 'type' => 'textarea', 'rows' => 2],
            ],
        ],
        'privacy-policy' => [
            'name' => 'Privacy Policy',
            'route' => 'privacy-policy',
            'fields' => [
                ['key' => 'title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'content', 'label' => 'Full policy content (HTML or plain text)', 'type' => 'textarea', 'rows' => 16],
            ],
        ],
        'manufacturing-policy' => [
            'name' => 'Delivery & Returns',
            'route' => 'manufacturing-policy',
            'fields' => [
                ['key' => 'title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'content', 'label' => 'Full content', 'type' => 'textarea', 'rows' => 16],
            ],
        ],
        'terms-and-conditions' => [
            'name' => 'Terms & Conditions',
            'route' => 'terms-and-conditions',
            'fields' => [
                ['key' => 'title', 'label' => 'Page title', 'type' => 'text'],
                ['key' => 'content', 'label' => 'Full terms content', 'type' => 'textarea', 'rows' => 16],
            ],
        ],
        'inquiry-thank-you' => [
            'name' => 'Thank You (after enquiry)',
            'route' => 'inquiry.thank-you',
            'fields' => [
                ['key' => 'title', 'label' => 'Page title', 'type' => 'text', 'placeholder' => 'Thank you for your inquiry'],
                ['key' => 'message', 'label' => 'Thank you message', 'type' => 'textarea', 'rows' => 4],
            ],
        ],
        'faq' => [
            'name' => 'FAQ',
            'route' => 'faq',
            'fields' => [
                ['key' => 'hero_title', 'label' => 'Page title', 'type' => 'text', 'placeholder' => 'Frequently Asked Questions'],
                ['key' => 'hero_subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 1, 'placeholder' => 'Common questions about our furniture, delivery and services.'],
            ],
        ],
    ],
];
