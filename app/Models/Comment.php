<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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

    public function scopeChild(Builder $query) : void
    {
        $query->withCount('children');
//        $query->selectRaw("`comments`.*, count(SELECT * FROM `comments` as `ch` WHERE `ch`.parent = `comments`.id) as `child`");
//        $query->selectRaw("SUM() AS tlm, DATE_FORMAT(attempts, '%Y-%m') as dd, COUNT(tlm) cnt")
//            ->from($this->table)
//            ->where('parent', 1);
    }
}
