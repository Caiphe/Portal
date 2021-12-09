<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getRecoveryCodes(Request $request)
    {
        $user = $request->user();
        $recoveryCodes = $user->recovery_codes;

        if (is_null($recoveryCodes)) {
            $recoveryCodes = $this->generateCodes();
            $user->update([
                'recovery_codes' => $recoveryCodes
            ]);
        }

        return $recoveryCodes;
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
