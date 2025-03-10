<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::get();
            return Helper::sendResponse($users, "All User list data");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function store(UserStoreRequest $request){
        try {
            $user = new User();
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->role  = $request->role;
            $user->password = Hash::make($request->password);
            $user->save();

            return Helper::sendResponse($user, "User Created Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::where('id', $id)->first();
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->role  = $request->role;
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return Helper::sendResponse($user, "User Updated Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }

    public function delete($id)
    {
        try {
            $user = User::where('id', $id)->first();
            $user->delete();

            return Helper::sendResponse(null, "User Deleted Successfully");

        } catch (Exception $th) {
            Log::error($th->getMessage());
            return Helper::sendError("Something went wrong");
        }
    }
}
