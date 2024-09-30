<?php

namespace App\Http\Controllers\Api;
// rrrr
use App\Models\User;
use Illuminate\Http\File;
use App\Models\Dialer_audio;
use App\Models\Audio_Group;
use App\Models\Group_Audio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Foreach_;

class DialerAudioController extends Controller
{
    public function index($uid = null)
    {
        if (!empty($uid)) {
            $audio = User::where('uid', '=', $uid)->first();
            $user = $audio->id;
        } else {
            $user = auth()->user()->id;
        }
        $audio = Dialer_audio::where('id_parent', '=', $user)->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $audio,
            'message' => "Audio Successfully Get.",
        ]);
    }
    public function show($id)
    {
        $audio = Dialer_audio::find($id);

        return response()->json([
            'status' => true,
            'data' => $audio,
            'message' => "Audio Successfully Get.",
        ]);
    }
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
          'file_name' => 'required|file|mimes:mp3,wav',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return response()->json([
                "success" => false,
                "message" => "All field required.$messages",
            ]);
        }
        $audio = new Dialer_audio();
        $audio->name = $request->name;
        if (!empty($request->file_name)) {
            $fileName = time() . '_' . $request->file_name->getClientOriginalName();
            $filePath = $request->file('file_name')->storeAs('/', $fileName, 'audio');
            $audio->file_name = url('/audio/' . $filePath);
        }
        $audio->id_parent = \Auth::user()->id;
        $audio->type =   "audio";
        $audio->created_by = \Auth::user()->created_by;
        $audio->save();

        return response()->json([
            'status' => true,
            'data' => $audio,
            'message' => "Audio Successfully uploaded.",
        ]);
    }
    public function update(Request $request, $id)
    {

        $audio = Dialer_audio::find($id);

        if (!empty($request->file_name)) {
            $rules = [
                'file_name' => 'required|file|mimes:mp3,wav|mimetypes:audio/mpeg,audio/wav',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    "success" => false,
                    "message" => "All field required.$messages",
                ]);
            }
            $fileName = time() . '_' . $request->file_name->getClientOriginalName();
            $filePath = $request->file('file_name')->storeAs('/', $fileName, 'audio');

            $dd = $audio->file_name;
            $trimmedUrl = str_replace(url('/'), '', $dd);
            $trimmedUrls = ltrim($trimmedUrl, '/');
            $file_exists =  \File::exists(public_path($trimmedUrls));
            if (!empty($file_exists)) {
                \File::delete(public_path($trimmedUrls));
            }

            $audio->file_name = url('/audio/' . $filePath);
        }
        $audio->name = $request->name;
        $audio->save();

        return response()->json([
            'status' => true,
            'data' => $audio,
            'message' => "Audio Successfully updated.",
        ]);
    }
    public function delete($id)
    {
        $audio = Dialer_audio::find($id);
        if (!empty($audio)) {
            $dd = $audio->file_name;
            $trimmedUrl = str_replace(url('/'), '', $dd);
            $trimmedUrls = ltrim($trimmedUrl, '/');
            $file_exists =  \File::exists(public_path($trimmedUrls));
            if (!empty($file_exists)) {
                \File::delete(public_path($trimmedUrls));
            }
            $audio->delete();
            return response()->json([
                'status' => true,
                'message' => "Audio Successfully deleted.",
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => "Audio not found.",
            ], 500);
        }
    }



    public function get_groups($uid = null)
    {
        if (!empty($uid)) {
            $audio = User::where('uid', '=', $uid)->first();
            $user = $audio->id;
        } else {
            $user = auth()->user()->id;
        }
        $audio = Audio_Group::where('user_id', '=', $user)->with('audios.group_name')->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $audio,
            'message' => "Audio_groups Successfully Get.",
        ]);
    }

    public function show_group($id)
    {
        $audio = Audio_Group::with('audios.group_name')->find($id);

        return response()->json([
            'status' => true,
            'data' => $audio,
            'message' => "Audio_group Successfully Get.",
        ]);
    }

    public function store_audio_group(Request $request)
    {
        $user_id = Auth::user()->id;
        $group_name = $request->group_name;
        $audios = $request->audios;

        $au = Audio_Group::create([
            'user_id' => $user_id,
            'group_name' => $group_name
        ]);

        foreach ($audios as $k => $audio) {

            Group_Audio::create([
                'group_id' => $au->id,
                'audio_id' => $audio,
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $au,
            'message' => "Audio_group Successfully store.",
        ]);
    }
    public function update_audio_group(Request $request, $id)
    {
        $group_id = Audio_Group::find($id);
        $group_name = $request->group_name;
        $audios = $request->audios;

        $group_id->group_name = $group_name;
        $group_id->save();

        $old_audios = Group_Audio::where('group_id', $id)->pluck('audio_id')->toArray();

        $toDelete = array_diff($old_audios, $audios);

        $toAdd = array_diff($audios, $old_audios);

        Group_Audio::where('group_id', $id)
            ->whereIn('audio_id', $toDelete)
            ->delete();
        foreach ($toAdd as $new_audio_id) {
            $newAudio = new Group_Audio();
            $newAudio->group_id = $id;
            $newAudio->audio_id = $new_audio_id;
            $newAudio->save();
        }

        return response()->json([
            'status' => true,
            'data' => $group_id,
            'message' => "Audio_group Successfully Updated.",
        ]);
    }

    public function delete_group($id)
    {
        $audio = Audio_Group::find($id);

        if ($audio) {
            Group_Audio::where('group_id', $id)->delete();

            $audio->delete();

            return response()->json([
                'status' => true,
                'message' => "Audio group successfully deleted.",
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Audio group not found.",
        ], 404);
    }

    public function delete_single_audio($id)
    {
        $audio = Group_Audio::find($id);
        if ($audio) {
            $audio->delete();
            return response()->json([
                'status' => true,
                'message' => "Audio successfully deleted from group.",
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Audio group not found.",
        ], 404);
    }
}
