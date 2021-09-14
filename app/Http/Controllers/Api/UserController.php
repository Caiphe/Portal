<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getRecoveryCodes(Request $request)
    {
        return $this->generateCodes();
    }

    protected function generateCodes()
    {
        $codes = [];

        for ($i = 0; $i < 10; $i++) {
            $codes[] = bin2hex(random_bytes(5));
        }

        return $codes;
    }
}
