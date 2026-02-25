<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class GuardSessionManager
{
    /**
     * Session key mapping untuk setiap guard
     */
    protected static $sessionKeyMap = [
        'internal' => '_internal_guard_user',
        'web' => '_web_guard_user',
    ];

    /**
     * Store user info dalam session dengan guard-specific key
     */
    public static function storeUser($guard, $userId)
    {
        $key = self::$sessionKeyMap[$guard] ?? '_guard_user';
        Session::put($key, $userId);
    }

    /**
     * Retrieve user info dari session dengan guard-specific key
     */
    public static function getUser($guard)
    {
        $key = self::$sessionKeyMap[$guard] ?? '_guard_user';
        return Session::get($key);
    }

    /**
     * Forget user info dari session
     */
    public static function forgetUser($guard)
    {
        $key = self::$sessionKeyMap[$guard] ?? '_guard_user';
        Session::forget($key);
    }

    /**
     * Check apakah user ada di session untuk guard tertentu
     */
    public static function hasUser($guard)
    {
        $key = self::$sessionKeyMap[$guard] ?? '_guard_user';
        return Session::has($key);
    }
}
