<?php

namespace App\Services;

use Google2FA;
use Storage;

/**
 * This is a helper service to use the Google2FA helpers.
 */
class TwofaService
{
    private static $fileName = 'google2fasecret.key';

    private static $name = config('app.env') === 'staging' ? 'MTN Developer Portal - Pre-Prod' : 'MTN Developer Portal';

    private static $secretKey;

    private static $keySize = 32;

    private static $keyPrefix = '';

    /**
     * @param $key
     * @return mixed
     */
    public static function getInlineUrl($key, $email)
    {
        return Google2FA::getQRCodeInline(
            self::$name,
            $email,
            $key
        );
    }

    /**
     * Gets the secret key.
     *
     * @return     string  The secret key.
     */
    public static function getSecretKey()
    {
        return Google2FA::generateSecretKey(self::$keySize, self::$keyPrefix);
    }

    /**
     * @return mixed
     */
    public static function getStoredKey()
    {
        // No need to read it from disk it again if we already have it
        if (self::$secretKey) {
            return self::$secretKey;
        }

        if (!Storage::exists(self::$fileName)) {
            return null;
        }

        return Storage::get(self::$fileName);
    }

    /**
     * @param $key
     */
    public static function storeKey($key)
    {
        Storage::put(self::$fileName, $key);
    }

    /**
     * @return mixed
     */
    public static function verifyKey($key, $code)
    {
        return Google2FA::verifyKey($key, $code);
    }
}
