<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

/**
 * PATTERN DEMO — teammates isko follow karein:
 * Migration -> Model -> Controller -> Route -> View
 *
 * Controller ka kaam sirf: request handle karna, data fetch karna,
 * view ko pass karna. Business logic models/services mein rakhein.
 */
class PostController extends Controller
{
    public function index(): View
    {
        return view('pages.posts.index', [
            'posts' => Post::query()
                ->published()
                ->latest('published_at')
                ->paginate(9),
        ]);
    }

    public function show(Post $post): View
    {
        return view('pages.posts.show', [
            'post' => $post->load('author'),
        ]);
    }
}
