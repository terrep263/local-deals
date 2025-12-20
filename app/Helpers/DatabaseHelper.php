<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    public static function tableExists($tableName): bool
    {
        try {
            return Schema::hasTable($tableName);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function safeQuery($callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            \Log::warning('Database query failed: ' . $e->getMessage());
            return $default;
        }
    }
}


