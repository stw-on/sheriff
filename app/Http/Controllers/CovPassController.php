<?php

namespace App\Http\Controllers;

use App\CovPassCheck\DatabaseTrustStore;
use App\Exceptions\CertificateExpiredException;
use App\Models\SigningKey;
use App\Resources\SignedDataBlob;
use Illuminate\Http\JsonResponse;
use stwon\CovPassCheck\CovPassCheck;
use stwon\CovPassCheck\Exceptions\InvalidSignatureException;
use stwon\CovPassCheck\Exceptions\MissingHC1HeaderException;
use stwon\CovPassCheck\HealthCertificate\HealthCertificate;
use Symfony\Component\HttpFoundation\Response;

class CovPassController extends Controller
{
    /**
     * @param string $certificateData
     * @return HealthCertificate
     * @throws InvalidSignatureException
     * @throws CertificateExpiredException
     * @throws MissingHC1HeaderException
     */
    private function readAndCheckCertificate(string $certificateData): HealthCertificate
    {
        $check = new CovPassCheck(new DatabaseTrustStore());
        $certificate = $check->readCertificate($certificateData);

        if ($certificate->isExpired()) {
            throw new CertificateExpiredException();
        }

        return $certificate;
    }

    public function checkCovPass(): JsonResponse|array
    {
        $this->validateWith([
            'hcert' => 'string|required',
        ]);

        try {
            $certificate = $this->readAndCheckCertificate(request('hcert'));

            if ($certificate->getType() & HealthCertificate::TYPE_VACCINATION && !$certificate->getVaccinationEntries()[0]->isFullyVaccinated()) {
                return response()->json([
                    'error' => 'hcert_vaccination_series_not_complete',
                ], Response::HTTP_BAD_REQUEST);
            }

            return [
                'first_name' => $certificate->getSubject()->getFirstName(),
                'last_name' => $certificate->getSubject()->getLastName(),
                'date_of_birth' => $certificate->getSubject()->getDateOfBirth(),
            ];
        } catch (InvalidSignatureException) {
            return response()->json([
                'error' => 'hcert_invalid_signature',
            ], Response::HTTP_BAD_REQUEST);

        } catch (MissingHC1HeaderException) {
            return response()->json([
                'error' => 'hcert_hc1_header_missing',
            ], Response::HTTP_BAD_REQUEST);

        } catch (CertificateExpiredException) {
            return response()->json([
                'error' => 'hcert_expired',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function signContactDetails(): SignedDataBlob|JsonResponse
    {
        if (config('sheriff.registration_disabled')) {
            return response()->json([
                'error' => 'registration_disabled',
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->validateWith([
            'hcert' => 'string|required',
            'street' => 'string|min:2|max:128|required',
            'zip' => 'string|min:5|max:5|required',
            'city' => 'string|min:3|max:128|required',
            'phone' => 'string|min:5|max:128|required',
        ]);

        try {
            $certificate = $this->readAndCheckCertificate(request('hcert'));
            $key = SigningKey::latest();

            $data = request(['street', 'zip', 'city', 'phone']);
            $data += [
                'expires_at' => $certificate->getExpiresAt()->endOfDay()->toIso8601String(),
                'first_name' => $certificate->getSubject()->getFirstName(),
                'last_name' => $certificate->getSubject()->getLastName(),
                'date_of_birth' => $certificate->getSubject()->getDateOfBirth(),
                'certificate_type' => $certificate->getType(fullyVaccinatedOnly: true),
            ];

            return new SignedDataBlob($data, $key);
        } catch (InvalidSignatureException | MissingHC1HeaderException | CertificateExpiredException) {
            return response()->json([
                'error' => 'hcert_error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
