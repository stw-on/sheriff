<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\SigningKey;
use App\Models\Visit;
use App\Resources\SignedDataBlob;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VisitController extends Controller
{
    public function registerVisit()
    {
        $data = $this->validateWith([
            'id_data' => 'string|max:4096|required',
            'signed_contact_details' => 'array|required',
        ]);

        try {
            $identificationData = Crypt::decryptString($data['id_data']);
        } catch (DecryptException $exception) {
            report($exception);
            throw new BadRequestHttpException();
        }

        /** @var Location $location */
        $location = Location::query()->findOrFail($identificationData);

        try {
            $signedDataBlob = SignedDataBlob::fromArray(request('signed_contact_details'));
        } catch (Exception $exception) {
            report($exception);
            throw new BadRequestHttpException('Could not verify signature');
        }

        if (!((int)$signedDataBlob->get('certificate_type') & $location->allowed_certifications)) {
            return response()->json([
                'error' => 'hcert_not_covered',
            ], Response::HTTP_BAD_REQUEST);
        }

        $contactDetails = Arr::only($signedDataBlob->getData(), [
            'first_name',
            'last_name',
            'street',
            'zip',
            'city',
            'phone',
        ]);

        $visit = new Visit();
        $visit->location()->associate($location);
        $visit->entered_at = Carbon::now();
        $visit->storeContactDetails($contactDetails);
        $visit->save();

        return response()->json([
            'visit_id' => Crypt::encryptString($visit->id),
            'color_of_the_hour' => $location->getColorOfTheHour($visit->entered_at),
            'icon_of_the_hour' => $location->getIconOfTheHour($visit->entered_at),
            'location_name' => $location->name,
            'entered_at' => $visit->entered_at->format('H:i'),
            'date_of_birth' => $signedDataBlob->get('date_of_birth'),
            'entrance_certificate' => (new SignedDataBlob([
                'entered_at' => $visit->entered_at->format('H:i'),
                'location_name' => $location->name,
                'date_of_birth' => $signedDataBlob->get('date_of_birth'),
            ] + $contactDetails, SigningKey::latest()))->jsonSerialize(),
        ] + $contactDetails);
    }
}
