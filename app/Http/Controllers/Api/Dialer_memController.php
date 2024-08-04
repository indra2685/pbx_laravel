<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Ramsey\Uuid\Uuid;
// use App\Models\Sippeers;
use Illuminate\Http\Request;
use App\Models\Dialer_member;
// use App\Models\Dialer_group_member;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Dialer_memController extends Controller
{
    public function index($uid = null)
    {
        if (!empty($uid)) {
            $mem = User::where('uid', '=', $uid)->first();
            $user = $mem->id;
        } else {
            $user = auth()->user()->id;
        }
        $member = Dialer_member::where('id_parent', '=', $user)->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => true,
            'data' => $member,
            'message' => "Member Successfully Get.",
        ]);
    }
    public function queue()
    {
        $user = auth()->user()->id;
        $member = Dialer_member::where('id_parent', '=', $user)->where('queue_status', '!=', '1')->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => true,
            'data' => $member,
            'message' => "Member Successfully Get.",
        ]);
    }
    public function show($id)
    {
        $member = Dialer_member::find($id);

        return response()->json([
            'status' => true,
            'data' => $member,
            'message' => "Member Successfully Get.",
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'password' => 'required',
            'username' => 'required',
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

        try {
            $dialer_member                 = new Dialer_member();
            $dialer_member->name           = $request->name;
            $dialer_member->username       = $request->username;
            $dialer_member->password       = $request->password;
            $dialer_member->status         = $request->status;
            $dialer_member->extension      = $request->extension;
            $dialer_member->exte_pass      = $request->exte_pass;
            // $dialer_member->queue_status   = 0;
            // $dialer_member->ring_timeout   = $request->ring_timeout;
            $dialer_member->created_by     = \Auth::user()->created_by;
            $dialer_member->id_parent      = \Auth::user()->id;
            $dialer_member->save();

            $user      = new User();
            $user->name        = $request->name;
            $user->email       = $request->username;
            $user->role        = "MEMBER";
            $user->extension   = $request->extension;
            $user->exte_pass   = $request->exte_pass;
            $user->uid         = Uuid::uuid4()->toString();
            $user->password    = bcrypt($request->password);
            $user->created_by  = \Auth::user()->id;
            $user->save();

            return response()->json([
                'status' => true,
                'data' => $dialer_member,
                'user' => $user,
                'message' => "Member Successfully Created.",
            ]);
        } catch (Exception $ex) {
            $rorf = Log::error('' . $ex->getMessage());
            return response()->json([
                'status' => true,
                'message' => $ex,
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $member = Dialer_member::find($id);
        $user = User::where('email', $member->username)->first();
        if (!empty($member)) {
                $rules = [
                    // 'name' => 'required',
                    // 'username' => 'required',
                    // 'password' => 'required',
                    // 'extension' =>'required|max:5|min:5',
                    // 'email' => 'required|email',

                ];

                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return response()->json([
                        'status' => true,
                        'message' => "All fields are required.",
                        'error' => $messages,
                    ]);
                }
                try {
                    $member->name           = $request->name;
                    $member->username       = $request->username;
                    $member->password       = $request->password;
                    $member->status         = $request->status;
                    $member->extension      = $request->extension;
                    $member->exte_pass      = $request->exte_pass;
                    // $member->ring_timeout   = $request->ring_timeout;
                    $member->save();

                    $user->name        = $request->name;
                    $user->email       = $request->username;
                    $user->role        = "MEMBER";
                    $user->extension   = $request->extension;
                    $user->exte_pass   = $request->exte_pass;
                    $user->password    = bcrypt($request->password);
                    $user->save();

                    return response()->json([
                        'status' => true,
                        'data' => @$member,
                        'user' => @$user,
                        'message' => "Member Successfully updated.",
                    ]);
                } catch (Exception $ex) {
                    $rorf = Log::error('' . $ex->getMessage());
                    return response()->json([
                        'status' => true,
                        'message' => $ex,
                    ], 500);
                }
        } else {
            return response()->json([
                'status' => true,
                'message' => "Dialer member id not found",
            ], 500);
        }
    }

    public function delete($id)
    {
        $member = Dialer_member::find($id);
        if (!empty($member)) {
        $user = User::where('email', $member->username)->first();
        $queue_id = 'SIP/'.$member->extension;
            $member->delete();
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => "Dialer Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Dialer member id not found.",
            ], 500);
        }
    }

    public function multi_delete(Request $request)
    {
        $user_is = $request->id;
        foreach ($user_is as $id) {
            $member = Dialer_member::find($id);
            if (!empty($member)) {
            $user = User::where('email', $member->username)->first();
                $member->delete();
                $user->delete();
            }
        }
        return response()->json([
            'status' => true,
            'message' => "Dialer Successfully deleted.",
        ]);
    }

    public function change_status($id)
    {
        $member = Dialer_member::find($id);
        if (!empty($member)) {
            if ($member->status === 'Active') {
                $member->status = "Inactive";
                $member->save();
                return response()->json([
                    'status' => true,
                    'message' => "Member Status Successfully chenge.",
                ]);
            } else {
                $member->status = "Active";
                $member->save();
                return response()->json([
                    'status' => true,
                    'message' => "Member Status Successfully chenge.",
                ]);
            }
        }
    }
}
