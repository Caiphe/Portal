<?php

namespace App\Http\Controllers\Admin;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    public function read(Notification $notification)
    {
        $read = '';
        if($notification->read_at == ''){
            $read = date('Y-m-d H:i:s');
        }else{
            $read = null;
        }

        $notification->update(['read_at' => $read]);
		return response()->json(['success' => true], 200);

        // return response()->json(['success' => true, 'code' => 200], 200);
       
    }
}
