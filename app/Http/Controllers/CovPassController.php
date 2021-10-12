<?php

namespace App\Http\Controllers;

use App\CovPassCheck\DatabaseTrustStore;
use App\Exceptions\CertificateNotCoveredException;
use App\Http\Requests\SignedDataBlobRequest;
use App\Models\SigningKey;
use App\Resources\SignedDataBlob;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use stwon\CovPassCheck\CovPassCheck;
use stwon\CovPassCheck\Exceptions\InvalidSignatureException;
use stwon\CovPassCheck\Exceptions\MissingHC1HeaderException;
use stwon\CovPassCheck\HealthCertificate\HealthCertificate;
use stwon\CovPassCheck\HealthCertificate\Target;
use Symfony\Component\HttpFoundation\Response;

class CovPassController extends Controller
{
    private const COVERED_TYPES = HealthCertificate::TYPE_VACCINATION | HealthCertificate::TYPE_RECOVERY;

    /**
     * @param string $certificateData
     * @return HealthCertificate
     * @throws CertificateNotCoveredException
     * @throws InvalidSignatureException
     * @throws MissingHC1HeaderException
     */
    private function readAndCheckCertificate(string $certificateData): HealthCertificate
    {
        $check = new CovPassCheck(new DatabaseTrustStore());
        $certificate = $check->readCertificate($certificateData);
        if (!$certificate->isCovered(Target::COVID19, self::COVERED_TYPES)) {
            throw new CertificateNotCoveredException();
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

        } catch (CertificateNotCoveredException) {
            return response()->json([
                'error' => 'hcert_not_covered',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function signContactDetails(): SignedDataBlob|JsonResponse
    {
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
                'expires_at' => $certificate->getCoverageExpiryDate(Target::COVID19, self::COVERED_TYPES),
                'first_name' => $certificate->getSubject()->getFirstName(),
                'last_name' => $certificate->getSubject()->getLastName(),
                'date_of_birth' => $certificate->getSubject()->getDateOfBirth(),
            ];

            return new SignedDataBlob($data, $key);
        } catch (InvalidSignatureException | MissingHC1HeaderException | CertificateNotCoveredException) {
            return response()->json([
                'error' => 'cert_error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
