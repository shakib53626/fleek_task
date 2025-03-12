<?php

namespace App\Http\Controllers;

use Exception;
use App\Classes\Helper;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notification = Notification::orderBy('created_at', 'desc')->get();
            return Helper::sendResponse($notification, "All Notification list data");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function store(Request $request)
    {
        $notification = Notification::create([
            'user_id' => Auth::user()->id,
            'message' => $request->message,
            'url'     => $request->url,
        ]);

        event(new NewNotification([
            'message' => $notification->message,
            'url'     => $notification->url
        ]));

        return response()->json($notification);
    }

    public function update(Request $request, $id)
    {

        $notification = Notification::find($id);
        $notification->is_read = 1;
        $notification->save();

        return Helper::sendResponse($notification, "Update Notification");
    }
}
