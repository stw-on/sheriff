<?php


namespace App\Models;


use App\Models\Casts\Base64Cast;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PublicKey
 * @package App\Models
 * @property string id
 * @property string name
 * @property string key
 */
class PublicKey extends BaseModel
{
    use SoftDeletes;

    protected $visible = [
        'id',
        'name',
    ];

    protected $casts = [
        'key' => Base64Cast::class,
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
