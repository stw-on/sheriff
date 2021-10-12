<?php

namespace App\Models;

use App\Models\Casts\Base64Cast;
use App\Models\Traits\UsesPrimaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeException;
use SodiumException;

class SigningKey extends Model
{
    use UsesPrimaryUuid;
    use SoftDeletes;

    protected $visible = [
        'id',
    ];

    protected $casts = [
        'key' => Base64Cast::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (SigningKey $signingKey) {
            if (!$signingKey->revision) {
                $signingKey->revision = SigningKey::query()->max('revision') + 1;
            }
        });
    }

    /**
     * @throws RuntimeException
     */
    public static function latest(): SigningKey
    {
        $key = self::query()
            ->orderByDesc('revision')
            ->limit(1)
            ->first();

        if ($key === null) {
            throw new RuntimeException('This server has no active signing keypairs. Generate one with "php artisan keypair:generate --sign"');
        }

        return $key;
    }

    /**
     * @throws SodiumException
     */
    public function getPublicKey(): string
    {
        return sodium_crypto_sign_publickey_from_secretkey($this->key);
    }

    /**
     * @throws SodiumException
     */
    public function verify($data, $signature): bool
    {
        return sodium_crypto_sign_verify_detached($signature, $data, $this->getPublicKey());
    }

    /**
     * @throws SodiumException
     */
    public function sign($data): string
    {
        return sodium_crypto_sign_detached($data, $this->key);
    }
}
