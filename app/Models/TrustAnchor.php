<?php

namespace App\Models;

use App\Models\Traits\UsesPrimaryUuid;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use stwon\CovPassCheck\Trust\TrustAnchorContract;

class TrustAnchor extends Model implements TrustAnchorContract
{
    use UsesPrimaryUuid;

    protected $dates = [
        'timestamp',
    ];

    public function getCertificateType(): string
    {
        return $this->certificate_type;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getKid(): string
    {
        return $this->kid;
    }

    public function getCertificate(): string
    {
        return $this->certificate;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getThumbprint(): string
    {
        return $this->thumbprint;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }
}
