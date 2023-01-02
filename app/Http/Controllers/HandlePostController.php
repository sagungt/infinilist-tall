<?php

namespace App\Http\Controllers;

use App\Helpers\HttpClient;
use Illuminate\Http\Request;

class HandlePostController extends Controller
{
    private $base_url = 'http://localhost:8001';
    public function createPost(Request $request)
    {
        $payload = $request->all();
        // dd($payload);
        $body = [];
        foreach ($payload as $key => $val) {
            $b['name'] = $key;
            $b['contents'] = $val;
            array_push($body, $b);
        }

        $file = [
            'cover' => $request->file('cover'),
        ];

        $response = HttpClient::fetch(
            'POST',
            $this->base_url . '/api/posts',
            $payload,
            $file,
        );

        if ($response['status']) {
            return response($response);
            // return redirect()->back()->with('success', 'Post created');
        }
        
        return response($response);
        // return redirect()->back()->withErrors($response['data']);
    }

    public function getPost()
    {
        $response = HttpClient::fetch(
            'GET',
            $this->base_url . '/api/posts/asd',
        );

        return response($response);
    }
}
