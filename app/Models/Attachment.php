<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'storage_url',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments_attachments';

    public $timestamps = false;
}
