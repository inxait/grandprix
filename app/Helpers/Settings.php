<?php

namespace App\Helpers;

use App\Setting;

class Settings
{
    public static function getByKey($key)
    {
        return Setting::where('key', $key)->first();
    }
}
