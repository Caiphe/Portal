<?php

namespace App\Http\Controllers\Admin;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    public function fetchNotification(){
        $notifications = Notification::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['notifications' => $notifications]);
    }

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
    }

    public function readAll()
    {
        $user = auth()->user()->id;
        $notifications = Notification::where('user_id', $user)->where('read_at', null)->get();

        if($notifications){
            foreach($notifications as $note)
            {
                $note->update(['read_at' => date('Y-m-d H:i:s')]);
            }
            return response()->json(['success' => true], 200);       
        }
    }

    public function clearAll()
    {
        $user = auth()->user()->id;
        $notifications = Notification::where('user_id', $user)->get();

        if($notifications){
            foreach($notifications as $note)
            {
                $note->delete();
            }
            return response()->json(['success' => true], 200);       
        }
    }

    public function notificationsCount()
    {
        $count = Notification::where('user_id', auth()->user()->id)->whereNull('read_at')->count();
        return response()->json(['count' => $count]);
    }
}
