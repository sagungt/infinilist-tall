<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $kind = 'user';
    private $type = 'web';
    public function show() {
        $user_id = auth()->user()->id;
        $user = User::getUser($user_id);

        return response([
            'status' => true,
            'message' => '',
            'data' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::query()->where('id', $user_id)->first();

        $payload = $request->all();

        $username_validator = [];
        $email_validator = [];
        if ($payload['username'] != $user->username) {
            $username_validator = [
                'username' => 'required|min:3|max:20|unique:users',
            ];
        }
        if ($payload['email'] != $user->email) {
            $email_validator = [
                'email' => 'required|email|unique:users',
            ];
        }

        $payload = $request->all();
        $validator = Validator::make($payload, [
            'name' => 'required|min:2',
            ...$username_validator,
            ...$email_validator,
        ]);

        if ($validator->fails()) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors($validator->errors());
            }
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        if (isset($payload['password'])) {
            $validator = Validator::make($payload, [
                'name' => 'required|confirmed|min:8',
            ]);

            if ($this->type == 'web') {
                return redirect()->back()->withErrors($validator->errors());
            }

            if ($validator->fails()) {
                return response([
                    'status' => false,
                    'message' => 'Validation error',
                    'data' => $validator->errors(),
                ], 422);
            }
        }
        if ($payload['password'] == '') {
            unset($payload['password']);
        }

        if ($request->hasFile('profile')) {
            $validator = Validator::make($payload, [
                'profile' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            
            if ($validator->fails()) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors($validator->errors());
                }
                return response([
                    'status' => false,
                    'message' => 'Validation error',
                    'data' => $validator->errors(),
                ], 422);
            }

            $profile = $request->file('profile');
            $filename = $profile->hashName();
            $profile->storeAs('profile', $filename);
            $path = $request->getSchemeAndHttpHost() . "/storage/profile/" . $filename;

            $attachment = Attachment::query()
                ->where('kind', strtoupper($this->kind))
                ->where('parent_id', $user->id)
                ->first();

            if ($attachment != null) {
                $previous_file = str_replace($request->getSchemeAndHttpHost() . '/storage', '', $attachment->path);
                Storage::delete($previous_file);
            }

            $attachment = Attachment::query()->create([
                'kind' => strtoupper($this->kind),
                'parent_id' => $user->id,
                'path' => $path,
            ]);
        }

        $user->fill($payload);
        $user->save();

        $user['profile_url'] = $attachment;

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Profile updated',
            ]);
        }
        
        return response([
            'status' => true,
            'message' => '',
            'data' => $validator->errors(),
        ], 422);
    }

    public function profile($username)
    {
        $user = User::getUsers()
            ->where('username', $username)
            ->first();

        return view('profile.index', ['user' => $user]);
    }
}
