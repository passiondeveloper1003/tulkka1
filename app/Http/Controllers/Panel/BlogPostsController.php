<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Translation\BlogTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogPostsController extends Controller
{
    private function handleAuthorize($user)
    {
        if (!$user->isTeacher()) {
            abort(403);
        }
    }

    public function index()
    {
        $user = auth()->user();

        $this->handleAuthorize($user);

        $query = Blog::where('author_id', $user->id);

        $posts = deepClone($query)
            ->withCount([
                'comments'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $blogIds = deepClone($query)->pluck('id')->toArray();

        $postsCount = count($blogIds);
        $commentsCount = Comment::whereIn('blog_id', $blogIds)->count();
        $pendingPublishCount = deepClone($query)->where('status', 'pending')->count();

        $data = [
            'pageTitle' => trans('site.posts'),
            'posts' => $posts,
            'postsCount' => $postsCount,
            'commentsCount' => $commentsCount,
            'pendingPublishCount' => $pendingPublishCount,
        ];

        return view('web.default.panel.blog.posts.lists', $data);
    }

    public function create()
    {
        $user = auth()->user();

        $this->handleAuthorize($user);

        $blogCategories = BlogCategory::all();

        $data = [
            'pageTitle' => trans('update.create_a_post'),
            'blogCategories' => $blogCategories
        ];

        return view('web.default.panel.blog.posts.create', $data);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $this->handleAuthorize($user);

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->all();

        $blog = Blog::create([
            'slug' => Blog::makeSlug($data['title']),
            'category_id' => $data['category_id'],
            'author_id' => $user->id,
            'image' => $data['image'],
            'enable_comment' => true,
            'status' => 'pending',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if ($blog) {
            BlogTranslation::updateOrCreate([
                'blog_id' => $blog->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'meta_description' => strip_tags($data['description']),
                'content' => $data['content'],
            ]);
        }

        return redirect('/panel/blog/posts');
    }

    public function edit(Request $request, $post_id)
    {
        $user = auth()->user();

        $this->handleAuthorize($user);

        $post = Blog::where('id', $post_id)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($post)) {
            $locale = $request->get('locale', app()->getLocale());

            $blogCategories = BlogCategory::all();

            $data = [
                'pageTitle' => trans('public.edit') . ' | ' . $post->title,
                'blogCategories' => $blogCategories,
                'locale' => mb_strtolower($locale),
                'post' => $post,
            ];

            return view('web.default.panel.blog.posts.create', $data);
        }
    }

    public function update(Request $request, $post_id)
    {
        $user = auth()->user();

        $this->handleAuthorize($user);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $post = Blog::where('id', $post_id)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($post)) {
            $data = $request->all();

            $post->update([
                'category_id' => $data['category_id'],
                'image' => $data['image'],
                'status' => 'pending',
                'updated_at' => time(),
            ]);


            BlogTranslation::updateOrCreate([
                'blog_id' => $post->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'meta_description' => strip_tags($data['description']),
                'content' => $data['content'],
            ]);

            return redirect('/panel/blog/posts');
        }

        abort(404);
    }

    public function delete($post_id)
    {
        $user = auth()->user();

        $this->handleAuthorize($user);

        $post = Blog::where('id', $post_id)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($post)) {
            $post->delete();
        }

        return response()->json([
            'code' => 200,
        ]);
    }
}
