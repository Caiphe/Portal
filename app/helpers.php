<?php

if (!function_exists('skip_2fa')) {
    function skip_2fa()
    {
        $currentDomain = str_replace('https://', '', config('app.url'));

        return in_array($currentDomain, config('google2fa.domains_to_skip'));
    }
}
