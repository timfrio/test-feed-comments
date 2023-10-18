<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'text'  => $this->text,
            'parent' => Comment::where('id', $this->parent)->Child()->Attachment()->first(),
            'child' =>
                Comment::where('parent', $this->id)
                    ->Child()->Attachment()
                    ->orderBy('created_at', 'desc')
                    ->paginate(25)
            ,
            'children_count' => Comment::where('parent', $this->id)->count(),
            'attachment_storage_url' => Attachment::where('comment_id', $this->id)
                ->select('storage_url')->first()->storage_url ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
