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
class Homepage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_page',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments_homepage';

    public $timestamps = false;
}
