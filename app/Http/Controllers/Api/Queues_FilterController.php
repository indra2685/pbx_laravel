<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Queue_filter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Queues_FilterController extends Controller
{
    public function index($uid = null)
    {
        if (!empty($uid)) {
            $queues_filetr = User::where('uid', '=', $uid)->first();
            $user = $queues_filetr->id;
        } else {
            $user = auth()->user()->id;
        }
        $queues_filetr = Queue_filter::where('id_parent', '=', $user)->with('queue_name')->get();

        return response()->json([
            'status' => true,
            'data' => $queues_filetr,
            'message' => "Queues_filter Successfully Get.",
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'queues_id' => 'required',
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

        $queues_filter               = new Queue_filter();
        $queues_filter->queues_id    = $request->queues_id;
        $queues_filter->filter_by    = $request->filter_by;
        $queues_filter->duration     = $request->duration;
        $queues_filter->id_parent    = \Auth::user()->id;
        $queues_filter->created_by   = \Auth::user()->created_by;
        $queues_filter->save();


        return response()->json([
            'status' => true,
            'data' => $queues_filter,
            'message' => "queues_filter Successfully Created.",
        ]);
    }

    public function update(Request $request, $id)
    {
        $queues_filter = Queue_filter::find($id);
        if (!empty($queues_filter)) {
            $rules = [
                'queues_id' => 'required',
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
            $queues_filter->queues_id    = $request->queues_id;
            $queues_filter->filter_by    = $request->filter_by;
            $queues_filter->duration     = $request->duration;
            $queues_filter->save();

            return response()->json([
                'status' => true,
                'data' => $queues_filter,
                'message' => "queues_filter Successfully Updated.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "queues_filter id not found.",
            ], 500);
        }
    }

    public function delete($id)
    {
        $queues_filter = Queue_filter::find($id);
        if (!empty($queues_filter)) {
            $queues_filter->delete();
            return response()->json([
                'status' => true,
                'message' => "Queues_filter Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Queues_filter id not found.",
            ], 500);
        }
    }
}
