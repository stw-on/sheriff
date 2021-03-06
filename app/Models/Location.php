<?php


namespace App\Models;

use App\Models\Casts\Base64Cast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Location
 * @package App\Models
 * @property string id
 * @property string name
 * @property PublicKey publicKey
 * @property Visit[]|Collection visits
 * @property string public_key_id
 */
class Location extends BaseModel
{
    use SoftDeletes;

    protected $visible = [
        'id',
        'name',
    ];

    protected $appends = [
        'visits_today',
    ];

    const COLORS = [
        '#d32f2f',
        '#7b1fa2',
        '#303f9f',
        '#0288d1',
        '#00796b',
        '#689f38',
        '#fbc02d',
        '#e64a19',
        '#795548',
        '#607d8b',
    ];

    public function generateQrString()
    {
        return json_encode([
            'host' => config('sheriff.host'),
            'data' => Crypt::encryptString($this->id),
        ]);
    }

    public function getQrUrlAttribute()
    {
        return 'https://' . config('sheriff.host') . '/register?scan=' . base64_encode($this->generateQrString());
    }

    public function getVisitsTodayAttribute()
    {
        return $this->visits()->whereDate('entered_at', today())->count();
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function publicKey()
    {
        return $this->belongsTo(PublicKey::class);
    }

    public function getColorOfTheDay()
    {
        $hash = crc32($this->publicKey->key . Carbon::today()->format('Ymd'));
        return self::COLORS[abs($hash) % count(self::COLORS)];
    }
}
