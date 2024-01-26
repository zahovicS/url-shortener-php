<?php

namespace UrlShortenerPhp\Session;

class Session
{
    public static function has($key)
    {
        return (bool) static::get($key);
    }
    public static function all()
    {
        return $_SESSION;
    }
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }
    public static function getFlash($key, $default = null)
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash()
    {
       unset($_SESSION['_flash']);
    }

    public static function flush()
    {
        $_SESSION = [];
    }
}