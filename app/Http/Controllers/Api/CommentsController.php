<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentsCollection;
use App\Models\{
    Attachment,
    Comment,
    Homepage
};
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

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
                ->Child()->Attachment()
                ->orderBy('created_at', 'desc')
                ->paginate(25)
        );
    }

    public function create(CreateCommentRequest $request)
    {
        // TODO: replace to trait
        $image = null;
        if ($request->hasFile('file')) {
            if ($request->file('file')->extension() === 'txt') {
                if (validator($request->file('file'), ['file' => 'max:100'])->fails()) {
                    return response()->json(['message' => 'max size file 100 kb']);
                }
            } else {
                $image = Image::make($request->file('file'));
                $image->resize(
                    $image->getWidth() > 320 ? 320 : $image->getWidth(),
                    $image->getHeight() > 240 ? 240 : $image->getHeight(),
                    fn ($constraint) => $constraint->aspectRatio()
                );
            }
        }

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

        if ($request->hasFile('file')) {
            $file = $request->file('file')->store('comments', 'public');
            $name = "comments/{$comment->id}-" . time() . ".{$request->file('file')->extension()}";
            $attachment = new Attachment();
            $attachment->comment_id = $comment->id;

            if ($image) {
                $image->save(storage_path("app/public/$name"));
            }

            $attachment->storage_url = $image ? $name : $file;
            $attachment->save();
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
