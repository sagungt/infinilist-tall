<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Post;
use App\Models\Shortener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShortenerController extends Controller
{
    private $kinds = ['post', 'series', 'external'];
    private $type = 'web';
    public function index()
    {
        $user_id = auth()->user()->id;
        $shorteners = Shortener::getShortenersByOwner($user_id);
        return response([
            'status' => true,
            'message' => '',
            'data' => $shorteners,
        ]);
    }

    public function redirect($url) {
        $shortener = Shortener::query()
            ->where('alias', $url)
            ->whereNull('deleted_at')
            ->first();

        // $shortener->fill([
        //     'visits' => $shortener->visits + 1,
        // ]);
        // $shortener->save();

        if ($shortener == null) {
            return redirect()->route('error.not-found');
        }

        return redirect($shortener->target);
    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'url' => 'min:3|unique:shorteners,alias',
            'kind' => [
                'required',
                Rule::in($this->kinds),
            ],
        ]);

        if ($validator->fails()) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors($validator->errors());
            }
        }

        if ($payload['kind'] == 'external') {
            $validator = Validator::make($payload, [
                'target' => 'required|url',
            ]);
    
            if ($validator->fails()) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors($validator->errors());
                }
            }
            $shorten = Shortener::query()->create([
                'user_id' => $user_id,
                'kind' => strtoupper($payload['kind']),
                'parent_id' => 0,
                'alias' => $payload['url'],
                'target' => $payload['target'],
            ]);
        } else {
            $child = null;
            if ($payload['kind'] == 'post') {
                $child = Post::query()
                    ->where('id', $payload['parent_id'])
                    ->first();
            }
            if ($payload['kind'] == 'series') {
                $child = Chapter::query()
                    ->where('id', $payload['parent_id'])
                    ->first();
            }
    
            $shorten = Shortener::query()->create([
                'user_id' => $user_id,
                'kind' => strtoupper($payload['kind']),
                'parent_id' => $payload['parent_id'],
                'alias' => $payload['url'],
                'target' => $request->getSchemeAndHttpHost() . '/' . $payload['kind'] . '/' . $child->slug,
            ]);
        }

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Shortener created',
            ]);
        }
        return response([
            'status' => true,
            'message' => '',
            'data' => $shorten,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $shortener = Shortener::query()
            ->where('id', $id)
            ->first();

        if ($shortener == null) {
            return redirect()->route('error.not-found');
        }

        $payload = $request->all();

        $validator = Validator::make($payload, [
            'url' => 'min:3|unique:shorteners,alias',
            'kind' => [
                'required',
                Rule::in($this->kinds),
            ],
        ]);

        if ($validator->fails()) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors($validator->errors());
            }
        }

        $child = null;
        if ($payload['kind'] == 'post') {
            $child = Post::query()
                ->where('id', $payload['parent_id'])
                ->first();

            if ($child == null) {
                return redirect()->back()->withErrors([
                    'error' => 'Post not found',
                ]);
            }
        }

        if ($payload['kind'] == 'series') {
            $child = Chapter::query()
                ->where('id', $payload['parent_id'])
                ->first();

            if ($child == null) {
                return redirect()->back()->withErrors([
                    'error' => 'Series not found',
                ]);
            }
        }

        $payload['target'] = $request->getSchemeAndHttpHost() . '/' . $payload['kind'] . '/' . $child->slug;
        $payload['alias'] = $payload['url'];

        $shortener->fill($payload);
        $shortener->save();

        return redirect()->back()->with([
            'success' => 'Shortener updated',
        ]);
    }

    public function destroy($id)
    {
        $shortener = Shortener::query()
            ->where('id', $id)
            ->first();

        if ($shortener == null) {
            return redirect()->back()->withErrors([
                'error' => 'Shortener not found',
            ]);
        }

        $shortener->delete();

        return redirect()->back()->with([
            'success' => 'Shortener deleted',
        ]);
    }
}
