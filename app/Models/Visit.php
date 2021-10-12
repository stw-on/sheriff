<?php


namespace App\Models;

use App\Models\Casts\Base64Cast;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use SodiumException;

/**
 * Class Visit
 * @package App\Models
 * @property string id
 * @property Carbon entered_at
 * @property Carbon left_at
 * @property Location location
 * @property string location_id
 * @property string contact_details
 * @property PublicKey publicKey
 * @property string public_key_id
 */
class Visit extends BaseModel
{
    public const PAD_LENGTH = 512;

    protected $visible = [
        'id'
    ];

    protected $casts = [
        'contact_details' => Base64Cast::class,
    ];

    protected $dates = [
        'entered_at',
        'left_at'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function publicKey(): BelongsTo
    {
        return $this->belongsTo(PublicKey::class);
    }

    /**
     * @param array $data
     * @throws SodiumException
     * @throws Exception
     */
    public function storeContactDetails(array $data): void
    {
        if (!$this->location) {
            throw new Exception('Cannot store contact details without an associated location!');
        }

        $this->contact_details = sodium_crypto_box_seal(sodium_pad(json_encode($data, JSON_THROW_ON_ERROR), self::PAD_LENGTH), $this->location->publicKey->key);
        $this->publicKey()->associate($this->location->publicKey);
    }
}
