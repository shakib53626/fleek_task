<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::get();

            return Helper::sendError($users, "All User list data");
        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }
}
