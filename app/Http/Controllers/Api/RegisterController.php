<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;


class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'ADMIN') {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors());
                }

                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $input['role'] = "MANAGER";
                $input['uid'] = Uuid::uuid4()->toString();
                $input['created_by'] = auth()->user()->id;
                $user = User::create($input);
                // $success['token'] =  $user->createToken('MyApp')->plainTextToken;
                // $success['name'] =  $user->name;

                // return $this->sendResponse($success, 'User register successfully.');

                return response()->json([
                    'status' => true,
                    "data" => $user,
                    'message' => "User register successfully.",
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "Only Admin can create.",
                ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => "Unauthorised",
            ]);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            if($user->role == 'MEMBER'){
                $success['extension'] =  $user->extension;
                $success['exte_pass'] =  $user->exte_pass;
            }

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}
