<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dialer_audio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

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
        $audio = Dialer_audio::where('id_parent', '=', $user)->get();

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
        // dd($request->all());
        $rules = [
            'name' => 'required',
            'file_name' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac'
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
        $audio = new Dialer_audio();
        $audio->name = $request->name;
        $audio->file_name = url('/audio/' . $filePath);
        $audio->id_parent = \Auth::user()->id;
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
                'name' => 'required',
                'file_name' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac'
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
}
