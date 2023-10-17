<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Comment
 * @package App\Models
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'text',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent');
    }

    public function attachment(): HasOne
    {
        return $this->hasOne(Attachment::class, 'comment_id');
    }

    public function scopeChild(Builder $query) : void
    {
        $query->withCount('children');
    }

    public function scopeAttachment(Builder $query): void
    {
        $query->withAggregate('attachment', 'storage_url');
    }
}
