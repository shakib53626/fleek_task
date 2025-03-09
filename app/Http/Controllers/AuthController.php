<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Classes\Helper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomException;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {

            $user = new User();

            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $token = JWTAuth::fromUser($user);
            $data = [
                "user" => $user,
                "token" => $token,
            ];

            return Helper::sendResponse($data, "User Created Successfully");

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return Helper::sendError("Something Want Wrong");
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if(!$user){
                throw new CustomException("User Not Found");
            }

            if(!Hash::check($request->password, $user->password)){
                throw new CustomException("Credentials are does't match");
            }

            $token = JWTAuth::fromUser($user);
            $data = [
                "user" => $user,
                "token" => $token,
            ];
            return Helper::sendResponse($data, "User Login Successfully");

        }catch(CustomException $ex){
            return Helper::sendError($ex->getMessage());

        }catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return Helper::sendError("Something Want Wrong");
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return Helper::sendResponse(null, "User Logout Successfully", 200);

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return Helper::sendError('Something Went Wrong');
        }
    }

}
