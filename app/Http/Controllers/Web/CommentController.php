<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Webinar;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'comment' => 'required|string',
        ]);

        $user = auth()->user();
        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');
        $teacher_id = $request->get('teacher_id');

        $comment = Comment::create([
            $item_name => $item_id,
            'user_id' => $user->id,
            'given_id' => $teacher_id,
            'comment' => $request->input('comment'),
            'reply_id' => $request->input('reply_id'),
            'status' => 'active',
            'created_at' => time()
        ]);

        if ($item_name == 'webinar_id') {
            $webinar = Webinar::FindOrFail($item_id);
            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('new_comment', $notifyOptions, 1);
        } elseif ($item_name == 'product_id') {
            $product = $comment->product;

            $notifyOptions = [
                '[p.title]' => $product->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('product_new_comment', $notifyOptions, 1);
        } elseif ($item_name == 'blog_id') {
            $blog = $comment->blog;

            if(!empty($blog) and !$blog->author->isAdmin()) {
                $notifyOptions = [
                    '[blog_title]' => $blog->title,
                    '[u.name]' => $user->full_name
                ];
                sendNotification('new_comment_for_instructor_blog_post', $notifyOptions, $blog->author->id);

                $buyStoreReward = RewardAccounting::calculateScore(Reward::COMMENT_FOR_INSTRUCTOR_BLOG);
                RewardAccounting::makeRewardAccounting($comment->user_id, $buyStoreReward, Reward::COMMENT_FOR_INSTRUCTOR_BLOG, $comment->id);
            }
        }

        $toastData = [
            'title' => trans('product.comment_success_store'),
            'msg' => trans('product.comment_success_store_msg'),
            'status' => 'success'
        ];
        return redirect()->back()->with(['toast' => $toastData]);
    }

    public function storeReply(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'reply' => 'required|string',
        ]);

        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');
        $teacher_id = $request->get('teacher_id');

        Comment::create([
            $item_name => $item_id,
            'user_id' => auth()->user()->id,
            'given_id' => $teacher_id,
            'comment' => $request->input('reply'),
            'reply_id' => $request->input('comment_id'),
            'status' => $request->input('status') ?? Comment::$pending,
            'created_at' => time()
        ]);

        $toastData = [
            'title' => trans('product.comment_success_store'),
            'msg' => trans('product.comment_success_store_msg'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $this->validate($request, [
            'webinar_id' => 'required',
            'comment' => 'nullable',
        ]);

        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!empty($comment)) {
            $comment->update([
                'webinar_id' => $request->input('webinar_id'),
                'user_id' => $user->id,
                'comment' => $request->input('comment'),
                'reply_id' => $request->input('reply_id'),
                'status' => $request->input('status') ?? Comment::$pending,
                'created_at' => time()
            ]);

            return redirect()->back();
        }

        abort(404);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($comment)) {
            $comment->delete();
        }

        return redirect()->back();
    }

    public function report(Request $request, $id)
    {
        $comment = comment::findOrFail($id);

        $this->validate($request, [
            'item_id' => 'required',
            'message' => 'required',
        ]);

        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');
        $data = $request->all();

        CommentReport::create([
            $item_name => $item_id,
            'user_id' => auth()->id(),
            'comment_id' => $comment->id,
            'message' => $data['message'],
            'created_at' => time()
        ]);

        return response()->json([
            'code' => 200
        ], 200);
    }
}
