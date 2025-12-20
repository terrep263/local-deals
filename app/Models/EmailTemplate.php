<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key', 'name', 'category', 'subject', 'body', 'variables', 'is_active'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get template by key
     */
    public static function getByKey($key)
    {
        return self::where('key', $key)->where('is_active', true)->first();
    }

    /**
     * Render template with variables
     */
    public function render(array $variables = [])
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($variables as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value, $subject);
            $body = str_replace('{' . $key . '}', $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }
}


