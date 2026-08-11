<?php

declare(strict_types=1);

namespace Mailofly;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

final class Http
{
    public const DEFAULT_BASE_URL = 'https://www.mailofly.com';
    public const API_PREFIX = '/api/v1';

    public static function normalizeBaseUrl(?string $baseUrl): string
    {
        return rtrim($baseUrl ?? self::DEFAULT_BASE_URL, '/');
    }

    /**
     * @param array<string, scalar|null>|null $query
     * @return array<string, mixed>|list<mixed>|null
     */
    public static function request(
        string $baseUrl,
        string $path,
        string $method = 'GET',
        ?string $apiKey = null,
        mixed $body = null,
        ?array $query = null,
    ): mixed {
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }

        if ($query !== null) {
            $filtered = [];
            foreach ($query as $k => $v) {
                if ($v !== null) {
                    $filtered[$k] = (string) $v;
                }
            }
            if ($filtered !== []) {
                $path .= (str_contains($path, '?') ? '&' : '?') . http_build_query($filtered);
            }
        }

        $headers = [
            'Accept' => 'application/json',
            'X-Mailofly-Client' => 'sdk/php',
        ];
        if ($apiKey !== null) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $options = ['headers' => $headers, 'http_errors' => false];
        if ($body !== null) {
            $options['headers']['Content-Type'] = 'application/json';
            $options['json'] = $body;
        }

        $client = new GuzzleClient(['base_uri' => rtrim($baseUrl, '/') . '/', 'timeout' => 30.0]);

        try {
            $res = $client->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $e) {
            throw new MailoflyException(0, 'network_error', $e->getMessage());
        }

        $text = (string) $res->getBody();
        $parsed = null;
        if ($text !== '') {
            try {
                $parsed = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                $parsed = $text;
            }
        }

        $status = $res->getStatusCode();
        if ($status < 200 || $status >= 300) {
            $err = $res->getReasonPhrase() ?: 'error';
            $detail = null;
            if (is_array($parsed)) {
                if (isset($parsed['error']) && is_string($parsed['error'])) {
                    $err = $parsed['error'];
                }
                if (isset($parsed['message']) && is_string($parsed['message'])) {
                    $detail = $parsed['message'];
                }
            } elseif (is_string($parsed)) {
                $detail = $parsed;
            }
            throw new MailoflyException($status, $err, $detail, $parsed);
        }

        return $parsed;
    }
}
