<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dialer_queues;
use App\Models\Dialer_member;
use App\Models\Dialer_queues_member;
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
        $queues = Dialer_queues::where('id_parent', '=', $user)->get();

        return response()->json([
            'status' => true,
            'data' => $queues,
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

        $_members = Dialer_member::whereIn('id', $request->member)->pluck('name')->toArray();

        $member = [];
        $queues                 = new Dialer_queues();
        $queues->name           = $request->name;
        $queues['member']       = implode(',', $_members);
        $queues->ivr_message    = $request->ivr_message;
        $queues->strategy       = $request->strategy;
        $queues->moh_pro        = $request->moh_pro;
        $queues->id_parent      = \Auth::user()->id;
        $queues->langugae       = $request->langugae;
        $queues->gree_pro       = $request->gree_pro;
        $queues->timeout        = $request->timeout;
        $queues->recording      = $request->has('recording') ? 1 : 0;
        $queues->dis_coller     = $request->has('dis_coller') ? 1 : 0;
        $queues->exit_no_agent  = $request->has('exit_no_agent') ? 1 : 0;
        $queues->ply_posi       = $request->has('ply_posi') ? 1 : 0;
        $queues->ply_posi_peri  = $request->has('ply_posi_peri') ? 1 : 0;
        $queues->auto_ans       = $request->has('auto_ans') ? 1 : 0;
        $queues->call_back      = $request->has('call_back') ? 1 : 0;
        $queues->per_ann        = $request->per_ann;
        $queues->per_ann_pro    = $request->per_ann_pro;
        $queues->created_by     = \Auth::user()->created_by;

        $queues->save();

        foreach ($request->member as $member) {

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

        return response()->json([
            'status' => true,
            'data' => $queues,
            'message' => "queues Successfully Created.",
        ]);
    }

    public function update(Request $request, $name)
    {
        $queues = Dialer_queues::where('name', 'like', $name)->first();
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

            $_members = Dialer_member::whereIn('id', $request->member)->pluck('name')->toArray();

            $member = [];
            $queues->name           = $request->name;
            $queues['member']       = implode(',', $_members);
            $queues->ivr_message    = $request->ivr_message;
            $queues->strategy       = $request->strategy;
            $queues->moh_pro        = $request->moh_pro;
            $queues->langugae       = $request->langugae;
            $queues->gree_pro       = $request->gree_pro;
            $queues->timeout        = $request->timeout;
            $queues->recording      = $request->has('recording') ? 1 : 0;
            $queues->dis_coller     = $request->has('dis_coller') ? 1 : 0;
            $queues->exit_no_agent  = $request->has('exit_no_agent') ? 1 : 0;
            $queues->ply_posi       = $request->has('ply_posi') ? 1 : 0;
            $queues->ply_posi_peri  = $request->has('ply_posi_peri') ? 1 : 0;
            $queues->auto_ans       = $request->has('auto_ans') ? 1 : 0;
            $queues->call_back      = $request->has('call_back') ? 1 : 0;
            $queues->per_ann        = $request->per_ann;
            $queues->per_ann_pro    = $request->per_ann_pro;
            $queues->save();


            foreach ($request->member as $member) {
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
            return response()->json([
                'status' => true,
                'data' => $queues,
                'message' => "queues Successfully Updated.",
            ]);
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
            if (!empty($queues_name)) {
                $queues->delete();
                $queues_name->delete();
                return response()->json([
                    'status' => true,
                    'message' => "Queues Successfully deleted.",
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "Queues_member not found.",
                ], 500);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => "Queues not found.",
            ], 500);
        }
    }
    public function count_member($name){
echo "hello";
die;
        $queues_name = Dialer_queues_member::where('queue_name', 'LIKE', $name)->get();

        return response()->json([
            'status' => true,
            'data' => $queues_name,
            'message' => "Queues_member Successfully counted.",
        ]);
    }
}
