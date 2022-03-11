<?php

namespace Pedroni\RdStation\Support\Definitions;

class RetrieveTokensResponse
{
    private string $accessToken;
    private string $refreshToken;
    private int $expiresIn;

    public function __construct(
        string $accessToken,
        string $refreshToken,
        int $expiresIn,
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn = $expiresIn;
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function refreshToken(): string
    {
        return $this->refreshToken;
    }

    public function expiresIn(): int
    {
        return $this->expiresIn;
    }
}
