<?php

namespace App\Models\ApiModel;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\Log;

abstract class BaseApiModel extends BaseModel
{
    public const string CONTENT_TYPE_JSON = 'application/json'; // default for requestFormatMode and responseFormatMode
    public const string CONTENT_TYPE_FORM_DATA = 'multipart/form-data'; // default for requestFormatMode and responseFormatMode

    protected const array HEADERS_DEFAULT = [
        'Cache-Control' => 'no-cache, must-revalidate',
        'Pragma' => 'no-cache',
        // 'User-Agent' => CP_HTTP_AGENT,
    ];

    protected const string M_GET = 'GET';
    protected const string M_POST = 'POST';
    protected const string M_PUT = 'PUT';
    protected const string M_DELETE = 'DELETE';
    protected const string M_PATCH = 'PATCH';
    protected const string M_OPTIONS = 'OPTIONS';
    protected const string M_HEAD = 'HEAD';

    protected string $method = self::M_GET;

    private int $lastResponseCode;
    private string $lastErrorMessage;

    abstract public function apiServer(): string;
    abstract public function apiEndPoint(): string;
    abstract public function forcedQuery(): array;

    protected function apiEndPointLogMsg(): string {
        return $this->apiEndPoint();
    }

    protected bool $isQuiteModeOnceForGetNotFoundApiError = false;

    private float $timeoutDefault = 42.0;
    private ?float $timeoutOnce = null;
    protected function withTimeout(float $timeout): static
    {
        $this->timeoutOnce = $timeout > 0.0 ? $timeout : null;
        return $this;
    }

    private ?int $retriesOnceCount = null;
    private ?int $retriesOnceAwaitSecs = null;
    protected function withRetries(int $count, int $await = 2): static
    {
        if ($count <= 0 || $await <= 0.0) {
            $this->retriesOnceCount     = null;
            $this->retriesOnceAwaitSecs = null;
        } else {
            $this->retriesOnceCount     = $count;
            $this->retriesOnceAwaitSecs = $await;
        }
        return $this;
    }

    abstract public function logErrorsStack(): array;
    protected function logErrorsCallback(string $message, array $logArr): void
    {
        // LogHelper::userError($message, $logArr, $this->crmID);
    }

    public function isLastResponseSuccess(): bool
    {
        return !empty($this->lastResponseCode) && $this->lastResponseCode >= 200 && $this->lastResponseCode < 300;
    }

    public function errorMsg(): string
    {
        return !empty($this->lastErrorMessage) ? $this->lastErrorMessage : '';
    }

    public function sendGet(): ?array
    {
        $this->method = self::M_GET;
        $return = $this->getResponse();
        $this->isQuiteModeOnceForGetNotFoundApiError = false;
        return $return;
    }

    public function allowGetNotFountQuietOnce(): static
    {
        $this->isQuiteModeOnceForGetNotFoundApiError = true;
        return $this;
    }

    public function sendPost(array $body): ?array
    {
        $this->method = self::M_POST;
        return $this->getResponse($this->guzzleOptions($body));
    }

    public function sendPostVsCode(array $body): bool
    {
        $this->sendPost($body);
        return $this->isLastResponseSuccess();
    }

    public function sendPut(array $body): ?array
    {
        $this->method = self::M_PUT;
        return $this->getResponse($this->guzzleOptions($body));
    }

    public function sendPatch(array $body): ?array
    {
        $this->method = self::M_PATCH;
        return $this->getResponse($this->guzzleOptions($body));
    }

    public function sendPatchVsCode(array $body): bool
    {
        $this->sendPatch($body);
        return $this->isLastResponseSuccess();
    }

    protected function getResponse(?array $guzzleOptions = null): ?array
    {
        $responseStr = $this->getResponseString($guzzleOptions);

        if (is_null($responseStr)) {
            return null;
        }

        try {
            $response = null;
            if (self::CONTENT_TYPE_JSON == $this->responseFormatMode()) {
                $response = json_decode($responseStr, true, 512, JSON_THROW_ON_ERROR);
            }

            return $response;
        }
        catch (\JsonException) {
            Log::stack($this->logErrorsStack())->error('Bad JSON in ' . ApiModelHelper::aClass($this),  ['response' => $responseStr, 'endpoint' => $this->apiEndPoint()]);
        }
        catch (\Throwable $ex) {
            Log::stack($this->logErrorsStack())->critical('BaseApiModel parse-json ' . ApiModelHelper::aClass($this),  ['ex' => ApiModelHelper::logException($ex)]);
        }
        return null;
    }

    private function getResponseString(?array $guzzleOptions): ?string
    {
        $guzzleOptions = is_array($guzzleOptions) ? $guzzleOptions : $this->guzzleOptions();

        try {
            $guzzle = new \GuzzleHttp\Client();

            $guzzleResponse = $guzzle->request($this->method, $this->apiUri(), $guzzleOptions);

            $this->timeoutOnce = null;
            $this->retriesOnceCount = null;
            $this->retriesOnceAwaitSecs = null;

            $this->lastResponseCode = $guzzleResponse->getStatusCode();
            return $guzzleResponse->getBody();
        }
        catch (TransferException|InvalidArgumentException $ex) {
            if ($this->isQuiteModeOnceForGetNotFoundApiError && 404 == (int)$ex->getCode()) {
                Log::channel('commonDatadogQuite')->error(ApiModelHelper::aClass($this), [
                    'api' => $this->apiUri(),
                    'queryForced' => $this->forcedQuery(),
                ]);
                return null;
            }

            if ($response = $ex instanceof RequestException ? $ex->getResponse() : null) {
                $this->lastResponseCode = ApiModelHelper::responseCode($response);
                $this->lastErrorMessage = ApiModelHelper::responseMessage($response);
            }
            $logArr = [
                'endpoint' => $this->apiEndPoint(),
                'ex' => ApiModelHelper::logException($ex),
                'response' => ApiModelHelper::logResponse($response),
            ];
            $msg = static::logMsgForGuzzleException($ex);
            Log::stack($this->logErrorsStack())->error($msg, $logArr);
            $this->logErrorsCallback($msg, $logArr);

            if ($this->retriesOnceCount > 0 && $this->retriesOnceAwaitSecs) {
                sleep($this->retriesOnceAwaitSecs);
                $this->retriesOnceCount--;
                return $this->getResponseString($guzzleOptions);
            }
        }
        catch (\Throwable $ex) {
            Log::stack($this->logErrorsStack())->critical('BaseApiModel ' . ApiModelHelper::aClass($this),  ['ex' => ApiModelHelper::logException($ex)]);
        }
        return null;
    }

    public function apiUri(): string
    {
        return $this->apiServer() . $this->apiEndPoint();
    }

    public function apiHeaders(): array
    {
        return [];
    }

    public function apiBearer(): ?string
    {
        return null;
    }

    public function apiBasic(): ?string
    {
        return null;
    }

    protected function requestFormatMode(): string
    {
        return self::CONTENT_TYPE_JSON;
    }

    private function responseFormatMode(): string // make protected when support other formats
    {
        return self::CONTENT_TYPE_JSON;
    }

    protected function guzzleQuery(): array
    {
        return $this->forcedQuery();
    }

    protected function guzzleOptions(?array $body = null): array
    {
        $return = [
            'headers' => $this->guzzleHeaders(),
        ];
        if ($query = $this->guzzleQuery()) {
            $return['query'] = $query;
        }
        if ($body && self::CONTENT_TYPE_JSON == $this->requestFormatMode()) {
            $return['body'] = json_encode($body);
        } else if ($body && self::CONTENT_TYPE_FORM_DATA == $this->requestFormatMode()) {
            $multipart = [];
            foreach ($body as $name => $contents) {
                $multipart[] = [
                    'name' => $name,
                    'contents' => $contents,
                ];
            }
            $return['multipart'] = $multipart;
        }
        if ($this->timeoutOnce) {
            $return['timeout'] = $this->timeoutOnce;
        } else if ($this->timeoutDefault) {
            $return['timeout'] = $this->timeoutDefault;
        }
        return $return;
    }

    protected function guzzleHeaders(): array
    {
        $base = [
            'Accept' => $this->responseFormatMode(),
        ];
        if (self::CONTENT_TYPE_JSON == $this->requestFormatMode()) {
            $base['Content-Type'] = self::CONTENT_TYPE_JSON;
        }
        if ($token = $this->apiBearer()) {
            $base['Authorization'] = 'Bearer ' . $token;
        } else if ($token = $this->apiBasic()) {
            $base['Authorization'] = 'Basic ' . $token;
        }

        return array_merge(static::HEADERS_DEFAULT, $base, $this->apiHeaders());
    }

    private function logMsgForGuzzleException(TransferException|InvalidArgumentException $exception): string
    {
        $code = (int)$exception->getCode();
        $prefix = static::logPrefixForGuzzleException($exception::class, $code);

        if ($exception instanceof RequestException && !empty($exception->getResponse())) {
            return $prefix . $this->apiEndPointLogMsg() . ' ' . $exception->getResponse()->getStatusCode() . ' ' .  $this->apiServer() . ' ' . $exception->getResponse()->getReasonPhrase();
        }
        /** @var $exception \GuzzleHttp\Exception\ConnectException|\GuzzleHttp\Exception\InvalidArgumentException */
        return $prefix . $this->apiEndPointLogMsg() . ' ' . ApiModelHelper::aClass($exception) . ' - ' . $exception->getCode() . ' ' . $exception->getMessage();
    }

    private static function logPrefixForGuzzleException(string $exClass, ?int $code): string
    {
        if (404 == $code && $exClass == ClientException::class) {
            return 'Not-Found-API ';
        }
        return match ($exClass) {
            ClientException::class => 'Fail-API ',
            ServerException::class => 'Critical-Fail-API ',
            ConnectException::class => 'Connection-Fail-API ',
            default => 'Unexpected-Fail-API ',
        };
    }
}
