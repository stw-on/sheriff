<?php

namespace App\CovPassCheck;

use App\Models\TrustAnchor;
use stwon\CovPassCheck\Trust\TrustAnchorContract;
use stwon\CovPassCheck\Trust\TrustStore;

class DatabaseTrustStore extends TrustStore
{
    /**
     * @return TrustAnchorContract[]
     */
    public function fetchTrustAnchors(): array
    {
        return TrustAnchor::all();
    }

    public function getTrustAnchorsByCountry(string $country): array
    {
        return TrustAnchor::where('country', $country)->get();
    }

    public function getTrustAnchorByKid(string $kid): ?TrustAnchorContract
    {
        return TrustAnchor::where('kid', $kid)->first();
    }
}
