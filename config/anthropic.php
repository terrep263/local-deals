<?php

return [
    'api_key' => env('ANTHROPIC_API_KEY'),
    'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
    'max_tokens' => 1024,
    'temperature' => 0.7,
];


