<?php


namespace App\Http\Controllers;


use App\Models\PublicKey;

class PublicKeyController extends Controller
{
    public function getAll()
    {
        return response()->json(PublicKey::query()->orderBy('name')->get());
    }
}
