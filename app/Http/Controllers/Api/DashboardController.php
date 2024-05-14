<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Base_Calls;
use App\Models\Dialer_cdr;
use App\Models\Dialer_member;
use App\Models\Dialer_queues;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller as Controller;

class DashboardController extends Controller
{
    public function count()
    {
        $base_call = Base_Calls::count();
        $cdr = Dialer_cdr::count();
        $mem = Dialer_member::where('status', '=', 'Active')->count();
        $queuq = Dialer_queues::count();
        $user = User::where('role', '=', 'MEMBER')->count();
        return response()->json([
            'status' => true,
            'basic_calls' => $base_call,
            'cdr' => $cdr,
            'active_member' => $mem,
            'group' => $queuq,
            'agent' => $user,
            'message' => "Count Successfully Get",
        ]);
    }

    public function donut_chart()
    {
        $cdr = Dialer_cdr::whereDate('start_time', Carbon::now()->format('Y-m-d'))->count();
        $ans = Dialer_cdr::whereDate('start_time', Carbon::now()->format('Y-m-d'))->where('duration', '>', '0')->count();
        return response()->json([
            'status' => true,
            'total' => $cdr,
            'answer' => $ans,
            'message' => "Donut chart data Successfully Get",
        ]);
    }

    public function group_list()
    {
        $group_member = Dialer_queues::all();
        $memb = [];
        foreach ($group_member as $k => $mem) {
            if (!empty($mem->member)) {
                $string = str_replace(' ', '', $mem->member);
                $parts = explode(',', $string);
                $result = [];
                foreach ($parts as $key => $part) {
                    $result[$key] = $part;
                }
                $memb[] = [
                    'group_name'=>$mem->name,
                    'member'=>count($result)
                ];
            } else {
                $memb[] = [
                    'group_name'=>$mem->name,
                    'member'=> 0
                ];
            }
        }
        return response()->json([
            'status' => true,
            'groups' => $memb,
            'message' => "Group list data Successfully Get",
        ]);
    }
}
