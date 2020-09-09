<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VisitController extends Controller
{
    public function registerVisit()
    {
        $data = $this->validateWith([
            'id_data' => 'string|max:4096',
            'first_name' => 'string|max:128',
            'last_name' => 'string|max:128',
            'street' => 'string|max:128',
            'zip' => 'string|max:5',
            'city' => 'string|max:128',
            'phone' => 'string|numeric|max:32',
        ]);

        try {
            $identificationData = Crypt::decryptString($data['id_data']);
        } catch (DecryptException $exception) {
            throw new BadRequestHttpException();
        }

        /** @var Location $location */
        $location = Location::query()->findOrFail($identificationData);

        $visit = new Visit();
        $visit->location()->associate($location);
        $visit->entered_at = Carbon::now();
        $visit->storeContactDetails(Arr::only($data, [
            'first_name',
            'last_name',
            'street',
            'zip',
            'city',
            'phone',
        ]));
        $visit->save();

        return response()->json([
            'visit_id' => Crypt::encryptString($visit->id),
            'color_of_the_day' => $location->getColorOfTheDay(),
            'location_name' => $location->name,
            'entered_at' => $visit->entered_at->format('H:i'),
        ]);
    }
}
