<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dialer_member;
use App\Models\User;
use App\Models\Sippeers;
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
        $member = Dialer_member::where('id_parent', '=', $user)->get();
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
            'password' =>'required',
            'username' => 'required'
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
        $dialer_member                 = new Dialer_member();
        $dialer_member->name           = $request->name;
        $dialer_member->username       = $request->username;
        $dialer_member->password       = $request->password;
        $dialer_member->status         = $request->status;
        $dialer_member->extension      = $request->extension;
        $dialer_member->exte_pass      = $request->exte_pass;
        $dialer_member->ring_timeout   = $request->ring_timeout;
        $dialer_member->dial_timeout   = $request->dial_timeout;
        $dialer_member->voice_mail     = $request->voice_mail;
        $dialer_member->vs_pass        = $request->vs_pass;
        $dialer_member->email          = $request->email;
        $dialer_member->created_by     = \Auth::user()->created_by;
        $dialer_member->id_parent      = \Auth::user()->id;
        $dialer_member->save();

        $dialer_member_sippeers              = new Sippeers();
        $dialer_member_sippeers->id_member   = $dialer_member->id;
        $dialer_member_sippeers->id_parent   = \Auth::user()->id;
        $dialer_member_sippeers->name        = $request->extension;
        $dialer_member_sippeers->defaultuser = $request->extension;
        $dialer_member_sippeers->secret      = $request->exte_pass;
        $dialer_member_sippeers->accountcode = $request->extension;
        $dialer_member_sippeers->callerid    = $request->extension;
        $dialer_member_sippeers->mailbox     = $request->extension;
        $dialer_member_sippeers->fromuser    = $request->extension;
        $dialer_member_sippeers->vmexten     = $request->extension;
        $dialer_member_sippeers->created_by  = \Auth::user()->created_by;
        $dialer_member_sippeers->save();

        return response()->json([
            'status' => true,
            'data' => $dialer_member,
            'Sippeers' => $dialer_member_sippeers,
            'message' => "Member Successfully Created.",
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = Dialer_member::find($id);
        $sippeers = Sippeers::where('id_member', $id)->first();

        if (!empty($member)) {
            if (!empty($sippeers)) {
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

                $member->name           = $request->name;
                $member->username       = $request->username;
                $member->password       = $request->password;
                $member->status         = $request->status;
                $member->extension      = $request->extension;
                $member->exte_pass      = $request->exte_pass;
                $member->ring_timeout   = $request->ring_timeout;
                $member->dial_timeout   = $request->dial_timeout;
                $member->voice_mail     = $request->voice_mail;
                $member->vs_pass        = $request->vs_pass;
                $member->email          = $request->email;
                $member->save();

                $sippeers->name           = $request->extension;
                $sippeers->secret         = $request->exte_pass;
                $sippeers->defaultuser    = $request->extension;
                $sippeers->accountcode    = $request->extension;
                $sippeers->callerid       = $request->extension;
                $sippeers->mailbox        = $request->extension;
                $sippeers->fromuser       = $request->extension;
                $sippeers->vmexten        = $request->extension;
                $sippeers->save();

                return response()->json([
                    'status' => true,
                    'data' => @$member,
                    'Sippeers' => @$sippeers,
                    'message' => "Member Successfully updated.",
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "sippeers id not found",
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
        $sippeers = Sippeers::where('id_member', $id)->first();
        if (!empty($member)) {
            $member->delete();
            $sippeers->delete();

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
    public function sippeers_get($uid = null)
    {
        if (!empty($uid)) {
            $sippeers = User::where('uid', '=', $uid)->first();
            $user = $sippeers->id;
        } else {
            $user = auth()->user()->id;
        }
        $sippeers_get = Sippeers::where('id_parent', '=', $user)->with('member_name')->get();
        return response()->json([
            'status' => true,
            'data' => $sippeers_get,
            'message' => "Sippeers Successfully Get.",
        ]);
    }

    public function sippeers_show($id)
    {
        $sippeers = Sippeers::find($id);
        return response()->json([
            'status' => true,
            'data' => $sippeers,
            'message' => "Sippeers Successfully Get.",
        ]);
    }
}
