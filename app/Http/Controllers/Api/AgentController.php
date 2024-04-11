<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dialer_member;
use App\Models\Dialer_cdr;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function get_class()
    {
        $user = auth()->user()->id;

        $exte = Dialer_member::where('id_parent', '=', $user)->get();

        $dur = [];
        $exx = [];
        foreach ($exte as $es) {
            $extension = $es->extension;
            $status = $es->status;
            $es['eeee'] = $extension;
            $es['sssss'] = $status;
            $dur = 0;
            $cdr = Dialer_cdr::where('accountcode', '=', $extension)->get();
            foreach ($cdr as $du) {
                $duraction = $du->duration;
                $dur += $duraction;
            }
            $es['dddddd'] = $dur;
            $exx[] = $es;
        }
        $ww = [];
        foreach ($exx as $tt) {
            $ww[] = [
                'Extaction' => $tt->eeee,
                'status' => $tt->sssss,
                'Duration' => $tt->dddddd,
                'Group' => null,
                'User_status' => null
            ];
        }
        return  $ww;
    }
}
