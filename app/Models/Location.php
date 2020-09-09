<?php


namespace App\Models;

use App\Models\Casts\Base64Cast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Location
 * @package App\Models
 * @property string id
 * @property string name
 * @property string public_key
 */
class Location extends BaseModel
{
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

    protected $casts = [
        'public_key' => Base64Cast::class,
    ];

    public function generateQrString()
    {
        return json_encode([
            'host' => config('sheriff.host'),
            'data' => Crypt::encryptString($this->id),
        ]);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function getColorOfTheDay()
    {
        $hash = crc32($this->public_key . Carbon::today()->format('Ymd'));
        return self::COLORS[abs($hash) % count(self::COLORS)];
    }
}
