<?php


namespace App\Models;

use App\Models\Casts\Base64Cast;
use Carbon\Carbon;
use Exception;
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
 * @property string public_key
 */
class Visit extends BaseModel
{
    protected $casts = [
        'contact_details' => Base64Cast::class,
        'public_key' => Base64Cast::class,
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @param array $data
     * @throws SodiumException
     * @throws Exception
     */
    public function storeContactDetails(array $data)
    {
        if (!$this->location) {
            throw new Exception('Cannot store contact details without an associated location!');
        }

        $this->contact_details = sodium_crypto_box_seal(sodium_pad(json_encode($data), 512), $this->location->public_key);
        $this->public_key = $this->location->public_key;
    }
}
