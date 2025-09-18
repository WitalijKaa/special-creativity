<?php

namespace App\Models\ApiModel;

abstract class BaseApiCachedPathModel extends BaseApiModel
{
    private const int DEFAULT_CACHE_HOURS_FOR_QUIET_ERROR = 1;
    public const string QUITE_CACHE_PREFIX = 'Q';

    protected bool $isFreshApiData = false;

    abstract public function requestCrmID(): int;
    abstract public function requestCrmCacheKey(?string $specialCase = null): string;

    /*
    public function sendCachedGet(): ?array
    {
        $response = \HH::cache()->getCRM($this->requestCrmID(), $this->requestCrmCacheKey());

        if ($this->isQuiteModeOnceForGetNotFoundApiError && !is_array($response)) {
            $response = \HH::cache()->getCRM($this->requestCrmID(), $this->requestCrmCacheKey(self::QUITE_CACHE_PREFIX));
        }

        if (is_array($response)) {
            $this->isQuiteModeOnceForGetNotFoundApiError = false;
            return $response;
        } else {
            $hasQuitMode = $this->isQuiteModeOnceForGetNotFoundApiError;

            $response = $this->sendGet();

            if (!is_null($response))
            {
                $this->isFreshApiData = true;
                \HH::cache()->setCRM($response, $this->requestCrmID(), $this->requestCrmCacheKey());
                return $response;
            } else if ($hasQuitMode) {
                \HH::cache()->setCRM([], $this->requestCrmID(), $this->requestCrmCacheKey(self::QUITE_CACHE_PREFIX), self::DEFAULT_CACHE_HOURS_FOR_QUIET_ERROR);
                return [];
            }
        }
        return null;
    }
    */
}
