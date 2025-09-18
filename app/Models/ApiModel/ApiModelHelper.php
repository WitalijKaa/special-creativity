<?php

namespace App\Models\ApiModel;

class ApiModelHelper
{
    public static function aClass($class): string
    {
        if (!is_string($class)) {
            if (is_object($class)) {
                $class = $class::class;
            }
            else if (is_array($class)) {
                return 'Array';
            }
            else if (is_callable($class)) {
                return 'Callable';
            }
            else {
                $class = (string)$class;
            }
        }
        if (!$class) {
            return 'NoClass';
        }

        $pos = strrpos($class, '\\');

        if (!$pos) {
            return $class;
        }

        return substr($class, 1 + $pos);
    }

    public static function responseCode($response): int
    {
        if ($response instanceof \Illuminate\Http\Client\Response && method_exists($response, 'code')) {
            return $response->code();
        }
        if (is_object($response) && method_exists($response, 'getStatusCode')) {
            return $response->getStatusCode();
        }
        return 0;
    }

    public static function responseMessage($response): string
    {
        if ($response instanceof \Illuminate\Http\Client\Response && method_exists($response, 'reason')) {
            return $response->reason();
        }
        if (is_object($response) && method_exists($response, 'getReasonPhrase')) {
            return $response->getReasonPhrase();
        }
        return 'NULL';
    }

    public static function logException(\Throwable $ex): array
    {
        try {
            return [
                'code' => method_exists($ex, 'getCode') ? $ex->getCode() : null,
                'msg' => method_exists($ex, 'getMessage') ? preg_replace( '/[\r\n\t]/', '', (string)$ex->getMessage() ) : null,
                'file' => method_exists($ex, 'getFile') ? $ex->getFile() . (method_exists($ex, 'getLine') ? ':' . $ex->getLine() : '') : null,
                'ex' => method_exists($ex, 'getTraceAsString') ? preg_replace( '/[\r\n\t]/', '', (string)$ex->getTraceAsString() ) : null,
            ];
        } catch (\Exception $exEx) {
            \Illuminate\Support\Facades\Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/logCritical.log'),
            ])->critical('exArr', ['ex' => get_class($ex), 'exEx' => get_class($exEx)]);

            return ['ex' => get_class($ex), 'exEx' => get_class($exEx)];
        }
    }

    public static function logResponse($response): array
    {
        if (!$response) {
            return [];
        }

        try {
            if ($response instanceof \Illuminate\Http\Client\Response) {
                return [
                    'code' => method_exists($response, 'code') ? $response->code() : null,
                    'phrase' => method_exists($response, 'reason') ? $response->reason() : null,
                    'body' => method_exists($response, 'body') ? preg_replace( '/[\r\n\t]/', '', (string)$response->body() ) : null,
                ];
            }

            /** @var \Psr\Http\Message\ResponseInterface $response */
            return [
                'code' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
                'phrase' => method_exists($response, 'getReasonPhrase') ? $response->getReasonPhrase() : null,
                'body' => method_exists($response, 'getBody') ? preg_replace( '/[\r\n\t]/', '', (string)$response->getBody() ) : null,
            ];
        } catch (\Exception $exEx) {
            \Illuminate\Support\Facades\Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/logResponse.log'),
            ])->critical('responseArr', ['response' => get_class($response), 'exEx' => get_class($exEx)]);

            return ['response' => get_class($response), 'exEx' => get_class($exEx)];
        }
    }
}
