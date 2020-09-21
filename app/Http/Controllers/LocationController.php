<?php


namespace App\Http\Controllers;


use App\Models\Location;
use App\Models\PublicKey;

class LocationController extends Controller
{
    public function getAll()
    {
        return response()->json(Location::query()->orderBy('name')->get());
    }

    public function get(string $id)
    {
        return response()->json(
            Location::query()
                ->findOrFail($id)
                ->append('qr_url')
                ->makeVisible('public_key_id', 'qr_url')
        );
    }

    public function createOrUpdate()
    {
        $this->authorize('create', Location::class);

        $data = $this->validateWith([
            'id' => 'string|sometimes',
            'name' => 'string|required',
            'public_key_id' => 'string|required',
        ]);

        $publicKey = PublicKey::query()->findOrFail($data['public_key_id']);

        if (request()->has('id')) {
            $location = Location::query()->findOrFail($data['id']);
        } else {
            $location = new Location();
        }

        $location->name = $data['name'];
        $location->publicKey()->associate($publicKey);
        $location->save();

        return response()->json($location->makeVisible('public_key_id'));
    }
}
