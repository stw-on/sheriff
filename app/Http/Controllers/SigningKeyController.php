<?php

namespace App\Http\Controllers;

use App\Models\SigningKey;

class SigningKeyController extends Controller
{
    public function get()
    {
        return SigningKey::all()->mapWithKeys(fn(SigningKey $key) => [$key->id => base64_encode($key->getPublicKey())]);
    }
}
