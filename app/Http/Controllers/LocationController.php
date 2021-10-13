<?php


namespace App\Http\Controllers;


use App\Models\Location;
use App\Models\PublicKey;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function getAll()
    {
        return Location::query()
            ->orderBy('name')
            ->get()
            ->makeVisible(['visits_today', 'allowed_certifications']);
    }

    public function get(string $id)
    {
        return response()->json(
            Location::query()
                ->findOrFail($id)
                ->makeVisible(Location::ADMIN_FIELDS)
        );
    }

    public function createOrUpdate()
    {
        $this->authorize('create', Location::class);

        $data = $this->validateWith([
            'id' => 'string|sometimes',
            'name' => 'string|required',
            'public_key_id' => 'string|required',
            'allowed_certifications' => 'int|required',
        ]);

        $publicKey = PublicKey::query()->findOrFail($data['public_key_id']);

        if (request()->has('id')) {
            $location = Location::query()->findOrFail($data['id']);
        } else {
            $location = new Location();
        }

        $location->name = $data['name'];
        $location->allowed_certifications = $data['allowed_certifications'];
        $location->publicKey()->associate($publicKey);
        $location->save();

        return response()->json($location->makeVisible(Location::ADMIN_FIELDS));
    }

    public function downloadPdf(string $id)
    {
        /** @var Location $location */
        $location = Location::query()->findOrFail($id);

        $response = Http::post('http://pdfrenderer:8082/render', [
            'html' => view('poster', [
                'qr_url' => $location->getQrUrlAttribute(),
                'location' => $location,
            ])->render(),
        ]);

        $pdf = $response->throw()->body();

        return response()->stream(function () use ($pdf) {
            echo $pdf;
        }, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
