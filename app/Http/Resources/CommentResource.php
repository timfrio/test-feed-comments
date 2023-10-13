<?php declare(strict_types=1);

namespace App\Http\Resources;

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
            'parent' => Comment::find($this->parent),
            'child' =>
                Comment::where('parent', $this->id)
                    ->Child()
                    ->orderBy('created_at', 'desc')
                    ->paginate(25)
            ,
            'children_count' => Comment::where('parent', $this->id)->count(),
            'created_at' => $this->created_at,
        ];
    }
}
