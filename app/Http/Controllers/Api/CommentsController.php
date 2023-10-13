<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentsCollection;
use App\Models\{
    Comment,
    Homepage
};
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CommentsController
 * @package App\Http\Controllers\Api
 */
class CommentsController extends Controller
{
    public function index($parent = null): JsonResource
    {
        return new CommentsCollection(
            Comment::where('parent', $parent)
                ->Child()
                ->orderBy('created_at', 'desc')
                ->paginate(25)
        );
    }

    public function create(CreateCommentRequest $request)
    {
        $rules = ['captcha' => 'required|captcha_api:'. request('key') . ',math'];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid captcha'
            ]);
        } else {
            $comment = new Comment();
            $comment->name = $request->name;
            $comment->email = $request->email;
            $comment->text = strip_tags($request->text, '<i><a><code><strong>');
            $comment = Comment::create($request->validated());
            if ($request->parent > 0) {
                $comment->parent = $request->parent;
                $comment->save();
            }
            if ($request->has('homepage')) {
                $commentHomePage = new Homepage();
                $commentHomePage->comment_id = $comment->id;
                $commentHomePage->home_page = $request->homepage;
                $commentHomePage->save();
            }

        }

        return response()->json([
            'status' => 'ok',
            'request' => $request->all()
        ]);
    }

    public function show($comment): JsonResource
    {
        return new CommentResource(Comment::find((int)$comment));
    }
}
