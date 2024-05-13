<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Dialer_Routing;
use App\Models\Dialer_queues;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DialerRoutingController extends Controller
{
    public function index($uid = null)
    {
        if (!empty($uid)) {
            $routing = User::where('uid', '=', $uid)->first();
            $user = $routing->id;
        } else {
            $user = auth()->user()->id;
        }
        $routings = Dialer_Routing::where('id_parent', '=', $user)->orderBy('id', 'DESC')->get();
        $queue_name = '';
        foreach ($routings as $r) {
            @$queue_id = Dialer_queues::where('id', '=', $r->queuq_id)->first();
            @$queue_name = $queue_id->name;
            $r['queue_name'] = $queue_name;
        }

        return response()->json([
            'status' => true,
            'data' => $routings,
            'message' => "Dialer Route Successfully Get.",
        ]);
    }

    public function show($id)
    {
        $routing = Dialer_Routing::find($id);

        return response()->json([
            'status' => true,
            'data' => $routing,
            'message' => "Dialer Route Successfully Get.",
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'queuq_id' => 'required',
            'prefix' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return response()->json([
                "success" => false,
                "message" => "All field required.$messages",
            ]);
        }

        $routing = new Dialer_Routing();
        $routing->route_name = $request->route_name;
        $routing->queuq_id = $request->queuq_id;
        $routing->prefix = $request->prefix;
        $routing->id_parent = \Auth::user()->id;
        $routing->created_by = \Auth::user()->created_by;
        $routing->save();

        return response()->json([
            'status' => true,
            'data' => $routing,
            'message' => "Dialer Route Successfully Created.",
        ]);
    }
    public function update(Request $request, $id)
    {

        $routing = Dialer_Routing::find($id);


        $rules = [
            'queuq_id' => 'required',
            'prefix' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return response()->json([
                "success" => false,
                "message" => "All field required.$messages",
            ]);
        }
        $routing->route_name = $request->route_name;
        $routing->queuq_id = $request->queuq_id;
        $routing->prefix = $request->prefix;
        $routing->save();

        return response()->json([
            'status' => true,
            'data' => $routing,
            'message' => "Dialer_Routing Successfully updated.",
        ]);
    }
    public function delete($id)
    {
        $routing = Dialer_Routing::find($id);
        if (!empty($routing)) {
            $routing->delete();
            return response()->json([
                'status' => true,
                'message' => "Dialer_Routing Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Dialer_Routing not found.",
            ], 500);
        }
    }

    public function status_update(Request $request, $id)
    {
        $routing = Dialer_Routing::find($id);
        if (!empty($routing)) {
            $routing->status = $request->status;
            $routing->save();
            return response()->json([
                'status' => true,
                'data' => $routing,
                'message' => "Dialer Route Status Successfully updated.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Dialer_Routing not found.",
            ], 500);
        }
    }
}
