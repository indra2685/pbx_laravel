<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getuser($uid = null)
    {
        if (!empty($uid)) {
            $user = $uid;
        } else {
            $user = auth()->user()->uid;
        }

        $user_details = User::where('uid', '=', $user)->first();

        return response()->json([
            'status' => true,
            'data' => $user_details,
            'message' => "Successfully get user details."
        ]);
    }

    public function update(Request $request, $uid)
    {
        $user = User::where('uid', '=', $uid)->first();

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->dob   = $request->dob;
        $user->save();

        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => "Successfully user update."
        ]);
    }
    public function updatePassword(Request $request)
    {
        if (Auth::Check()) {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                ]
            );

            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;

            if (Hash::check($request_data['old_password'], $current_password)) {

                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['password']);
                $obj_user->save();

                return response()->json([
                    'status' => true,
                    'message' => "Password successfully updated.",
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "Please enter current password.",
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => "Something is wrong.",
            ]);
        }
    }
    public function delete($uid)
    {
        $user = User::where('uid', '=', $uid)->first();

        if (auth()->user()->role == 'ADMIN') {
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => "Successfully user Delete."
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Permission denied"
            ]);
        }
    }
}
