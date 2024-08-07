<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Dialer_member;
use App\Models\Dialer_queues;
use App\Models\Queue_Member;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Dialer_queues_member;
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
        $queues = Dialer_queues::where('id_parent', '=', $user)->with('audio_name')->orderBy('id', 'DESC')->get();
        $queues_name = '';
        // $member_name = [];
        foreach ($queues as $q) {
            $qu = $q->name;
            $queues_name = Dialer_queues_member::where('queue_name', 'LIKE', $qu)->count();
            $q['member_count'] = $queues_name;
            // $mem_name = json_decode($q->member, true);
            // if (is_array($mem_name)) {
            //     foreach ($mem_name as $q_id) {
            //         $name = Dialer_member::where('id', $q_id)->first();
            //         if ($name) {
            //             $member_name[] = $name->name;
            //         }
            //     }
            //     $q['member_name'] = $member_name;
            // }
        }

        return response()->json([
            'status' => true,
            'data' => $queues,
            'message' => "Queues Successfully Get.",
        ]);
    }
    public function show($name)
    {
        $queues_name = Dialer_queues_member::where('queue_name', 'LIKE', $name)->first();

        return response()->json([
            'status' => true,
            'data' => $queues_name,
            'message' => "Queues Successfully Get.",
        ]);
    }

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

            $queues                 = new Dialer_queues();
            $queues->name           = $request->name;
            $queues['member']       = implode(',', $member);
            // $queues->member         = $request->selectedMemberId;
            $queues->ivr_message    = $request->ivr_message;
            $queues->strategy       = $request->strategy;
            $queues->extension      = $request->extension;
            $queues->moh_pro        = $request->moh_pro;
            $queues->id_parent      = \Auth::user()->id;
            $queues->langugae       = $request->langugae;
            $queues->gree_pro       = $request->gree_pro;
            $queues->timeout        = $request->timeout;
            $queues->recording      = $request->recording;
            $queues->dis_coller     = $request->dis_coller;
            $queues->exit_no_agent  = $request->exit_no_agent;
            $queues->ply_posi       = $request->ply_posi;
            $queues->ply_posi_peri  = $request->ply_posi_peri;
            $queues->auto_ans       = $request->auto_ans;
            $queues->call_back      = $request->call_back;
            $queues->per_ann        = $request->per_ann;
            $queues->per_ann_pro    = $request->per_ann_pro;
            $queues->created_by     = \Auth::user()->created_by;
            $queues->save();

            foreach ($request->selectedMemberId as $member) {
                $dialer_member = Dialer_member::find($member);
                if ($dialer_member != null) {
                    $queues_mem = new Dialer_queues_member;
                    $queues_mem->queue_name = $queues->name;
                    $queues_mem->membername = $dialer_member->name;
                    $queues_mem->interface  = 'SIP/' . $dialer_member->extension;
                    $queues_mem->created_by = \Auth::user()->created_by;
                    $queues_mem->save();
                }
            }
            foreach ($request->selectedMemberId as $que_mem) {
                $qumem = Dialer_member::where('id', $que_mem)->first();
                $qumem->queue_status = 1;
                $qumem->save();

                $queu_mem = new Queue_Member;
                $queu_mem->queue_id = $queues->name;
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
        $queues = Dialer_queues::where('name', 'like', $name)->first();
        $que_mem = Queue_Member::where('queue_id', $queues->name)->get();
        Dialer_queues_member::where('queue_name', 'like', $name)->delete();
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
                $_members = Dialer_member::whereIn('id', $request->selectedMemberId)->pluck('name', 'extension')->toArray();

                $member = [];
                foreach ($_members as $extension => $name) {
                    $member[] = "$extension";
                }
                $queues->name           = $request->name;
                $queues['member']       = implode(',', $member);
                $queues->ivr_message    = $request->ivr_message;
                $queues->strategy       = $request->strategy;
                $queues->moh_pro        = $request->moh_pro;
                $queues->langugae       = $request->langugae;
                $queues->gree_pro       = $request->gree_pro;
                $queues->timeout        = $request->timeout;
                $queues->recording      = $request->recording;
                $queues->dis_coller     = $request->dis_coller;
                $queues->exit_no_agent  = $request->exit_no_agent;
                $queues->ply_posi       = $request->ply_posi;
                $queues->ply_posi_peri  = $request->ply_posi_peri;
                $queues->auto_ans       = $request->auto_ans;
                $queues->call_back      = $request->call_back;
                $queues->per_ann        = $request->per_ann;
                $queues->per_ann_pro    = $request->per_ann_pro;
                $queues->save();

                foreach ($request->selectedMemberId as $member) {
                    $dialer_member = Dialer_member::find($member);
                    if ($dialer_member != null) {
                        $queues_mem = new Dialer_queues_member;
                        $queues_mem->queue_name  = $queues->name;
                        $queues_mem->membername = $dialer_member->name;
                        $queues_mem->interface = 'SIP/' . $dialer_member->extension;
                        $queues_mem->created_by = \Auth::user()->created_by;
                        $queues_mem->save();
                    }
                }
                $memb_id = [];
                foreach ($que_mem as $mem_id) {
                    $memb_id[] = $mem_id->member_id;
                }

                foreach ($memb_id as $aa) {
                    if (in_array($aa, $request->selectedMemberId)) {
                    } else {
                        $qumem = Dialer_member::where('id', $aa)->first();
                        $qumem->queue_status = 0;
                        $qumem->save();

                        $qq = Queue_Member::where('member_id', $aa)->first();
                        $qq->delete();
                    }
                }

                foreach ($request->selectedMemberId as $mem_status) {

                    if (in_array($mem_status, $memb_id)) {
                        $qumems = Queue_Member::where('member_id', $mem_status)->first();
                        $qumems->queue_id = $queues->name;
                        $qumems->save();
                    } else {
                        $qumems = Dialer_member::where('id', $mem_status)->first();
                        $qumems->queue_status = 1;
                        $qumems->save();

                        $queu_mem = new Queue_Member;
                        $queu_mem->queue_id = $queues->name;
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
        $queues = Dialer_queues::where('name', 'LIKE', $name)->first();
        $queues_name = Dialer_queues_member::where('queue_name', 'LIKE', $name)->first();

        if (!empty($queues)) {
            $qumems = Queue_Member::where('queue_id', $queues->name)->get();
            foreach ($qumems as $mem_id) {
                $id = $mem_id->member_id;
                $qumem = Dialer_member::where('id', $id)->first();
                $qumem->queue_status = 0;
                $qumem->save();
 
                $mmm = Queue_Member::where('member_id', $id)->first();
                $mmm->delete();
            }
            $queues->delete();
            if (!empty($queues_name)) {
                $queues_name->delete();
            }
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
        $queues_name = Dialer_queues_member::where('queue_name', 'LIKE', $name)->count();

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
