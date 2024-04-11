<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Queue_filter;
use App\Models\Ip_address;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class IpAddressController extends Controller
{

    public function index($uid = null)
    {
        if (!empty($uid)) {
            $ip_address = User::where('uid', '=', $uid)->first();
            $user = $ip_address->id;
        } else {
            $user = auth()->user()->id;
        }
        $ip_address = Ip_address::where('id_parent', '=', $user)->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $ip_address,
            'message' => "IP Address Successfully Get.",
        ]);
    }

    public function show($id)
    {
        $ip_address = Ip_address::find($id);

        return response()->json([
            'status' => true,
            'data' => $ip_address,
            'message' => "IP Address Successfully Get.",
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'ip_address' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return response()->json([
                'status' => true,
                'message' => "All fields are required.",
                'error' => $messages,
            ], 500);
        }

        $ip_address               = new Ip_address();
        $ip_address->ip_address    = $request->ip_address;
        $ip_address->id_parent    = \Auth::user()->id;
        $ip_address->save();


        return response()->json([
            'status' => true,
            'data' => $ip_address,
            'message' => "IP Address Successfully Created.",
        ]);
    }

    public function update(Request $request, $id)
    {
        $ip_addresss = Ip_address::find($id);
        if (!empty($ip_addresss)) {
            $rules = [
                'ip_address' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => true,
                    'message' => "All fields are required.",
                    'error' => $messages,
                ], 500);
            }
            $ip_addresss->ip_address    = $request->ip_address;
            $ip_addresss->save();

            return response()->json([
                'status' => true,
                'data' => $ip_addresss,
                'message' => "IP Address Successfully Updated.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "IP Address id not found.",
            ], 500);
        }
    }

    public function delete($id)
    {
        $ip_address = Ip_address::find($id);
        if (!empty($ip_address)) {
            $ip_address->delete();
            return response()->json([
                'status' => true,
                'message' => "IP Address Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "IP Address id not found.",
            ], 500);
        }
    }
}
