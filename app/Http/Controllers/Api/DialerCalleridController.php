<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dialer_callerid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DialerCalleridController extends Controller
{
    public function index($uid = null)
    {
        if (!empty($uid)) {
            $caller = User::where('uid', '=', $uid)->first();
            $user = $caller->id;
        } else {
            $user = auth()->user()->id;
        }
        $callers = Dialer_callerid::where('id_parent', '=', $user)->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => true,
            'data' => $callers,
            'message' => "caller-id Successfully Get.",
        ]);
    }
    public function show($id)
    {
        $caller = Dialer_callerid::find($id);

        return response()->json([
            'status' => true,
            'data' => $caller,
            'message' => "caller-id Successfully Get.",
        ]);
    }
    public function store(Request $request)
    {
        $rules = [
            // 'user_id' => 'required',
            // 'number' =>'required',
            // 'country_code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return response()->json([
                'status' => true,
                'message' => "All fields are required.",
                'error' => $messages,
            ], 500);
        }
        $caller_id                = new Dialer_callerid();
        $caller_id->user_id       = $request->user_id;
        $caller_id->number        = $request->number;
        $caller_id->country_code  = $request->country_code;
        $caller_id->country_name  = $request->country_name;
        $caller_id->area_code     = $request->area_code;
        $caller_id->status        = $request->status;
        $caller_id->assign_to     = $request->assign_to;
        $caller_id->f_num         = $request->f_num;
        $caller_id->type          = $request->type;
        $caller_id->action        = $request->action;
        $caller_id->ivr           = $request->ivr;
        $caller_id->que           = $request->que;
        $caller_id->id_parent     = \Auth::user()->id;
        $caller_id->created_by    = \Auth::user()->created_by;

        $caller_id->save();

        return response()->json([
            'status' => true,
            'data' => $caller_id,
            'message' => "caller-id Successfully Created.",
        ]);
    }
    public function update(Request $request, $id)
    {
        $caller_id = Dialer_callerid::find($id);
        if (!empty($caller_id)) {
            $rules = [
                // 'user_id' => 'required',
                // 'number' =>'required',
                // 'country_code' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => true,
                    'message' => "All fields are required.",
                    'error' => $messages,
                ], 500);
            }
            $caller_id->user_id       = $request->user_id;
            $caller_id->number        = $request->number;
            $caller_id->country_code  = $request->country_code;
            $caller_id->country_name  = $request->country_name;
            $caller_id->area_code     = $request->area_code;
            $caller_id->status        = $request->status;
            $caller_id->assign_to     = $request->assign_to;
            $caller_id->f_num         = $request->f_num;
            $caller_id->type          = $request->type;
            $caller_id->action        = $request->action;
            $caller_id->ivr           = $request->ivr;
            $caller_id->que           = $request->que;
            $caller_id->save();

            return response()->json([
                'status' => true,
                'data' => $caller_id,
                'message' => "caller-id Successfully Updated.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "caller-id Not Found.",
            ], 500);
        }
    }
    public function delete($id)
    {
        $caller_id = Dialer_callerid::find($id);
        if (!empty($caller_id)) {
            $caller_id->delete();
            return response()->json([
                'status' => true,
                'message' => "caller-id Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "caller-id not found.",
            ], 500);
        }
    }
}
