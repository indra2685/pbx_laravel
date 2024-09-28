<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Dialer_member;
use App\Models\Dialer_group;
use App\Models\Group_Member;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DialerQueuesController extends Controller
{
    public function index($uid = null)
    {
        if (!empty($uid)) {
            $queues = User::where('uid', '=', $uid)->first();
            $user = $queues->id;
        } else {
            $user = auth()->user()->id;
        }
        $queues = Dialer_group::where('id_parent', '=', $user)->orderBy('id', 'DESC')->get();
        $queues_name = '';

        foreach ($queues as $k => $q) {
            $qu = $q->id;
            $queues_name = Group_member::where('group_id', '=', $qu)->get();
            $q['member_count'] = $queues_name->count();
            $member_name = [];
            if (!empty($queues_name)) {
                foreach ($queues_name as $q_id) {
                    $ee = $q_id->member_id;
                    $member = Dialer_member::where('id', $ee)->first();
                    $member_name[] = $member->extension;
                }
                $q['member'] = implode(",", $member_name);
            } else {
                $q['member'] = '';
            }

            $queues[$k] = $q;
        }

        return response()->json([
            'status' => true,
            'data' => $queues,

            'message' => "Queues Successfully Get.",
        ]);
    }
    // public function show($name)
    // {
    //     $queues_name = Dialer_group_member::where('queue_name', 'LIKE', $name)->first();

    //     return response()->json([
    //         'status' => true,
    //         'data' => $queues_name,
    //         'message' => "Queues Successfully Get.",
    //     ]);
    // }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
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
        try {
            $_members = Dialer_member::whereIn('id', $request->selectedMemberId)->pluck('name', 'extension')->toArray();

            $member = [];

            foreach ($_members as $extension => $name) {
                $member[] = "$extension";
            }

            $queues                 = new Dialer_group();
            $queues->group_name     = $request->name;
            $queues->id_parent      = \Auth::user()->id;
            $queues->type           =  "group";
            $queues->status         = $request->status;
            $queues->save();

            foreach ($request->selectedMemberId as $que_mem) {
                $qumem = Dialer_member::where('id', $que_mem)->first();
                if (!empty($qumem)) {
                    $qumem->group_id = $queues->id;
                    $qumem->save();
                }

                $queu_mem = new Group_Member;
                $queu_mem->group_id = $queues->id;
                $queu_mem->member_id = $que_mem;
                $queu_mem->save();
            }

            return response()->json([
                'status' => true,
                'data' => $queues,
                'message' => "queues Successfully Created.",
            ]);
        } catch (\Exception $e) {
            $rorf = Log::error('' . $e->getMessage());
            return response()->json([
                'status' => true,
                'message' => $e,
            ], 500);
        }
    }

    public function update(Request $request, $name)
    {
        $queues = Dialer_group::where('group_name', 'like', $name)->first();
        $que_mem = Group_Member::where('group_id', $queues->id)->get();
        if (!empty($queues)) {
            $rules = [
                'name' => 'required',
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
            try {
                // $_members = Dialer_member::whereIn('id', $request->selectedMemberId)->pluck('name', 'extension')->toArray();

                // $member = [];
                // foreach ($_members as $extension => $name) {
                //     $member[] = "$extension";
                // }
                $queues->group_name           = $request->name;
               // $queues->type           =  "group";
                $queues->status               = $request->status;
                $queues->save();

                $memb_id = [];
                foreach ($que_mem as $mem_id) {
                    $memb_id[] = $mem_id->member_id;
                }

                foreach ($memb_id as $aa) {
                    if (in_array($aa, $request->selectedMemberId)) {
                    } else {
                        $qumem = Dialer_member::where('id', $aa)->first();
                        $qumem->group_id = 0;
                        $qumem->save();

                        $qq = Group_Member::where('member_id', $aa)->delete();
                        // $qq->delete();
                    }
                }

                foreach ($request->selectedMemberId as $mem_status) {

                    if (in_array($mem_status, $memb_id)) {
                        $qumems = Group_Member::where('member_id', $mem_status)->first();
                        $qumems->group_id = $queues->id;
                        $qumems->save();
                    } else {
                        $qumems = Dialer_member::where('id', $mem_status)->first();
                        $qumems->group_id = $queues->id;
                        $qumems->save();

                        $queu_mem = new Group_Member;
                        $queu_mem->group_id = $queues->id;
                        $queu_mem->member_id = $mem_status;
                        $queu_mem->save();
                    }
                }
                return response()->json([
                    'status' => true,
                    'data' => $queues,
                    'message' => "queues Successfully Updated.",
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => true,
                    'message' => $e,
                ], 500);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => "queues name not found.",
            ], 500);
        }
    }

    public function delete($name)
    {
        $queues = Dialer_group::where('group_name', 'LIKE', $name)->first();

        if (!empty($queues)) {
            $qumems = Group_Member::where('group_id', $queues->id)->get();
            foreach ($qumems as $mem_id) {
                $id = $mem_id->member_id;
                $qumem = Dialer_member::where('id', $id)->first();
                if (!empty($qumem)) {
                    $qumem->group_id = 0;
                    $qumem->save();
                }

                $mmm = Group_Member::where('member_id', $id)->first();
                $mmm->delete();
            }
            $queues->delete();
            return response()->json([
                'status' => true,
                'message' => "Queues Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Queues not found.",
            ], 500);
        }
    }
    public function count_member($name)
    {
        $group =  Dialer_group::where('group_name', 'LIKE', $name)->first();
        $queues_name = Group_member::where('group_id', $group->id)->count();

        if (!empty($queues_name)) {
            return response()->json([
                'status' => true,
                'data' => $queues_name,
                'message' => "Queues_member Successfully counted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Queues name not found.",
            ], 500);
        }
    }
}
