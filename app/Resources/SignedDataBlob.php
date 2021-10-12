<?php

namespace App\Resources;

use App\Models\SigningKey;
use Arr;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use SodiumException;

class SignedDataBlob implements \JsonSerializable
{
    public function __construct(
        private array      $data,
        private SigningKey $key,
    )
    {
    }

    /**
     * @throws JsonException
     * @throws SodiumException
     * @throws InvalidSignatureException
     */
    public static function fromArray(array $data): self
    {
        $rawData = base64_decode($data['blob']);
        $key = SigningKey::findOrFail($data['key_id']);

        \Log::info(strlen(base64_decode($data['signature'])));

        if (!$key->verify($rawData, base64_decode($data['signature']))) {
            throw new InvalidSignatureException();
        }

        return new self(
            json_decode($rawData, true, 512, JSON_THROW_ON_ERROR),
            $key
        );
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @throws JsonException|SodiumException
     */
    #[ArrayShape(['key_id' => "mixed|string", 'blob' => "string", 'signature' => "string"])]
    public function jsonSerialize(): array
    {
        $blob = json_encode($this->data, JSON_THROW_ON_ERROR);

        return [
            'key_id' => $this->key->id,
            'blob' => base64_encode($blob),
            'signature' => base64_encode($this->key->sign($blob)),
        ];
    }
}
