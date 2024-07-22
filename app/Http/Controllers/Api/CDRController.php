<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dialer_cdr;
use Carbon\Carbon;

class CDRController extends Controller
{
    public function index(Request $request)
    {
        $start_time = @$request->start_time;
        // $end_time = @$request->end_time;
        // $extenction = @$request->extenction;
        $group_type = @$request->group_type;

        $cdr = Dialer_cdr::query();

        if (!empty($start_time)) {
            $cdr->whereDate('start_time', $start_time);
        }

        if (!empty($end_time)) {
            $cdr->whereDate('end_time', $end_time);
        }

        if (!empty($extenction)) {
            $cdr->where('dst', $extenction);
        }

        $cdrs = $cdr->get();
        // return $cdrs;
        if ($group_type == 'D') {
            $total_calls = 0;
            $total_duraction = 0;
            foreach ($cdrs as $call) {
                if (!empty($call)) {
                    $total_calls++;
                    $total_duraction += $call->duration;
                }
            }
            return response()->json([
                'status' => true,
                'date' =>  $start_time,
                'total_calls' => $total_calls,
                'total_duraction' => $total_duraction,
                'message' => "CDR Report Successfully Retrieved, Grouped by Daily",
            ]);
        }

        if ($group_type == 'H') {

            $one_c = 0;
            $one_d = 0;
            $one = [];
            $two_c = 0;
            $two_d = 0;
            $two = [];
            $three_c = 0;
            $three_d = 0;
            $three = [];
            $four_c = 0;
            $four_d = 0;
            $four = [];
            $five_c = 0;
            $five_d = 0;
            $five = [];
            $six_c = 0;
            $six_d = 0;
            $six = [];
            $seven_c = 0;
            $seven_d = 0;
            $seven = [];
            $eight_c = 0;
            $eight_d = 0;
            $eight = [];
            $nine_c = 0;
            $nine_d = 0;
            $nine = [];
            $ten_c = 0;
            $ten_d = 0;
            $ten = [];
            $eleven_c = 0;
            $eleven_d = 0;
            $eleven = [];
            $twelve_c = 0;
            $twelve_d = 0;
            $twelve = [];
            $thirteen_c = 0;
            $thirteen_d = 0;
            $thirteen = [];
            $fourteen_c = 0;
            $fourteen_d = 0;
            $fourteen = [];
            $fifteen_c = 0;
            $fifteen_d = 0;
            $fifteen = [];
            $sixteen_c = 0;
            $sixteen_d = 0;
            $sixteen = [];
            $seventeen_c = 0;
            $seventeen_d = 0;
            $seventeen = [];
            $eighteen_c = 0;
            $eighteen_d = 0;
            $eighteen = [];
            $nineteen_c = 0;
            $nineteen_d = 0;
            $nineteen = [];
            $twenty_c = 0;
            $twenty_d = 0;
            $twenty = [];
            $twenty_one_c = 0;
            $twenty_one_d = 0;
            $twenty_one = [];
            $twenty_two_c = 0;
            $twenty_two_d = 0;
            $twenty_two = [];
            $twenty_three_c = 0;
            $twenty_three_d = 0;
            $twenty_three = [];
            $twenty_four_c = 0;
            $twenty_four_d = 0;
            $twenty_four = [];

            foreach ($cdrs as $call) {
                if (!empty($call)) {
                    $date = Carbon::parse($call->start_time);
                    $time = $date->format('H:i:s');

                    if ($time <= '12:00:00' && $time > '01:00:00') {
                        $one = [
                            'calls' => ++$one_c,
                            'duraction' => $one_d += $call->duration,
                        ];
                    }
                    if ($time <= '01:00:00' && $time > '02:00:00') {
                        $two = [
                            'calls' => ++$two_c,
                            'duraction' => $two_d += $call->duration,
                        ];
                    }
                    if ($time <= '02:00:00' && $time > '03:00:00') {
                        $three = [
                            'calls' => ++$three_c,
                            'duraction' => $three_d += $call->duration,
                        ];
                    }
                    if ($time <= '03:00:00' && $time > '04:00:00') {
                        $four = [
                            'calls' => ++$four_c,
                            'duraction' => $four_d += $call->duration,
                        ];
                    }
                    if ($time <= '04:00:00' && $time > '05:00:00') {
                        $five = [
                            'calls' => ++$five_c,
                            'duraction' => $five_d += $call->duration,
                        ];
                    }
                    if ($time <= '05:00:00' && $time > '06:00:00') {
                        $six = [
                            'calls' => ++$six_c,
                            'duraction' => $six_d += $call->duration,
                        ];
                    }
                    if ($time <= '06:00:00' && $time > '07:00:00') {
                        $seven = [
                            'calls' => ++$seven_c,
                            'duraction' => $seven_d += $call->duration,
                        ];
                    }
                    if ($time <= '07:00:00' && $time > '08:00:00') {
                        $eight = [
                            'calls' => ++$eight_c,
                            'duraction' => $eight_d += $call->duration,
                        ];
                    }
                    if ($time <= '08:00:00' && $time > '09:00:00') {
                        $nine = [
                            'calls' => ++$nine_c,
                            'duraction' => $nine_d += $call->duration,
                        ];
                    }
                    if ($time <= '09:00:00' && $time > '10:00:00') {
                        $ten = [
                            'calls' => ++$ten_c,
                            'duraction' => $ten_d += $call->duration,
                        ];
                    }
                    if ($time <= '10:00:00' && $time > '11:00:00') {
                        $eleven= [
                            'calls' => ++$eleven_c,
                            'duraction' => $eleven_d += $call->duration,
                        ];
                    }
                    if ($time <= '11:00:00' && $time > '12:00:00') {
                        $twelve= [
                            'calls' => ++$twelve_c,
                            'duraction' => $twelve_d += $call->duration,
                        ];
                    }
                    if ($time <= '12:00:00' && $time > '13:00:00') {
                        $thirteen= [
                            'calls' => ++$thirteen_c,
                            'duraction' => $thirteen_d += $call->duration,
                        ];
                    }
                    if ($time <= '13:00:00' && $time > '14:00:00') {
                        $fourteen= [
                            'calls' => ++$fourteen_c,
                            'duraction' => $fourteen_d += $call->duration,
                        ];
                    }
                    if ($time <= '14:00:00' && $time > '15:00:00') {
                        $fifteen= [
                            'calls' => ++$fifteen_c,
                            'duraction' => $fifteen_d += $call->duration,
                        ];
                    }
                    if ($time <= '15:00:00' && $time > '16:00:00') {
                        $sixteen= [
                            'calls' => ++$sixteen_c,
                            'duraction' => $sixteen_d += $call->duration,
                        ];
                    }
                    if ($time <= '16:00:00' && $time > '17:00:00') {
                        $seventeen= [
                            'calls' => ++$seventeen_c,
                            'duraction' => $seventeen_d += $call->duration,
                        ];
                    }
                    if ($time <= '17:00:00' && $time > '18:00:00') {
                        $eighteen= [
                            'calls' => ++$eighteen_c,
                            'duraction' => $eighteen_d += $call->duration,
                        ];
                    }
                    if ($time <= '18:00:00' && $time > '19:00:00') {
                        $nineteen= [
                            'calls' => ++$nineteen_c,
                            'duraction' => $nineteen_d += $call->duration,
                        ];
                    }
                    if ($time <= '19:00:00' && $time > '20:00:00') {
                        $twenty= [
                            'calls' => ++$twenty_c,
                            'duraction' => $twenty_d += $call->duration,
                        ];
                    }
                    if ($time <= '20:00:00' && $time > '21:00:00') {
                        $twenty_one= [
                            'calls' => ++$twenty_one_c,
                            'duraction' => $twenty_one_d += $call->duration,
                        ];
                    }
                    if ($time <= '21:00:00' && $time > '22:00:00') {
                        $twenty_two= [
                            'calls' => ++$twenty_two_c,
                            'duraction' => $twenty_two_d += $call->duration,
                        ];
                    }
                    if ($time <= '22:00:00' && $time > '23:00:00') {
                        $twenty_three= [
                            'calls' => ++$twenty_three_c,
                            'duraction' => $twenty_three_d += $call->duration,
                        ];
                    }
                    if ($time <= '23:00:00' && $time > '24:00:00') {
                        $twenty_four= [
                            'calls' => ++$twenty_four_c,
                            'duraction' => $twenty_four_d += $call->duration,
                        ];
                    }
                }
            }
            return response()->json([
                'status' => true,
                'date' =>  $start_time,
                '12:00 - 01:00' => $one,
                '01:00 - 02:00' => $two,
                '02:00 - 03:00' => $three,
                '03:00 - 04:00' => $four,
                '04:00 - 05:00' => $five,
                '05:00 - 06:00' => $six,
                '06:00 - 07:00' => $seven,
                '07:00 - 08:00' => $eight,
                '08:00 - 09:00' => $nine,
                '09:00 - 10:00' => $ten,
                '10:00 - 11:00' => $eleven,
                '11:00 - 12:00' => $twelve,
                '12:00 - 13:00' => $thirteen,
                '13:00 - 14:00' => $fourteen,
                '14:00 - 15:00' => $fifteen,
                '15:00 - 16:00' => $sixteen,
                '16:00 - 17:00' => $seventeen,
                '17:00 - 18:00' => $eighteen,
                '18:00 - 19:00' => $nineteen,
                '19:00 - 20:00' => $twenty,
                '20:00 - 21:00' => $twenty_one,
                '21:00 - 22:00' => $twenty_two,
                '22:00 - 23:00' => $twenty_three,
                '23:00 - 24:00' => $twenty_four,
                'message' => "CDR Report Successfully Retrieved, Grouped by Hour",
            ]);
        }
    }
}
