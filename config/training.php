<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vendor Training Configuration
    |--------------------------------------------------------------------------
    |
    | Configure vendor training courses and requirements
    |
    */

    'enabled' => env('VENDOR_TRAINING_ENABLED', true),
    'required' => env('VENDOR_TRAINING_REQUIRED', true),
    'require_before_deal_creation' => env('VENDOR_TRAINING_REQUIRED', true),

    'courses' => [
        1 => [
            'number' => 1,
            'title' => 'Welcome to Lake County Local Deals - Platform Overview',
            'description' => 'Learn the basics of our platform, how it works, and what makes a successful deal.',
            'duration_minutes' => 10,
            'embed_url' => env('COURSE_1_EMBED_URL', 'https://openelms.ai/embed/e6943502397cae5.67250670/'),
            'required' => true,
            'order' => 1,
        ],
        2 => [
            'number' => 2,
            'title' => 'Creating Your First Deal - Complete Guide',
            'description' => 'Step-by-step guide to creating compelling deals that sell. Learn best practices for pricing, descriptions, and images.',
            'duration_minutes' => 15,
            'embed_url' => env('COURSE_2_EMBED_URL', 'https://openelms.ai/embed/e69434c726edee5.27793139/'),
            'required' => true,
            'order' => 2,
            'prerequisite' => 1, // Must complete course 1 first
        ],
    ],
];


