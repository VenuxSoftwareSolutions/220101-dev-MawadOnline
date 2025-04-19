<?php

namespace App\Security;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Sha3Hasher implements HasherContract
{
    protected $rounds;
    protected $secretKey;

    public function __construct(int $rounds, string $secretKey)
    {
        $this->rounds = $rounds;
        $this->secretKey = $secretKey;
    }

    public function make($value, array $options = [])
    {
        $hash = $value;
        for ($i = 0; $i < $this->rounds; $i++) {
            $hash = hash_hmac('sha3-512', $hash, $this->secretKey, true);
        }

        return base64_encode($hash);
    }

    public function check($value, $storedHash, array $options = [])
    {
        return hash_equals(
            $this->make($value),
            base64_decode($storedHash, true)
                ? $storedHash
                : base64_encode(base64_decode($storedHash))
        );
    }

    public function needsRehash($storedHash, array $options = []): bool
    {
        return $this->rounds !== ($options['rounds'] ?? $this->rounds);
    }

    public function info($hashedValue): array
    {
        return [
            'algo' => 'sha3-512',
            'rounds' => $this->rounds,
        ];
    }
}
