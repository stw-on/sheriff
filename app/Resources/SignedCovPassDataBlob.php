<?php

namespace App\Resources;

use App\Models\SigningKey;
use Carbon\Carbon;

class SignedCovPassDataBlob extends SignedDataBlob
{
    public static function fromData(
        string $firstName,
        string $lastName,
        string $birthDate,
        Carbon $expiresAt,
        SigningKey $key,
    ): SignedCovPassDataBlob
    {
        return new self([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birth_date' => $birthDate,
            'expires_at' => $expiresAt->toIso8601String(),
        ], $key);
    }
}
