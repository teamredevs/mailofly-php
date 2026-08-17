<?php

declare(strict_types=1);

namespace Mailofly;

final class Client
{
    private string $apiKey;
    private string $baseUrl;

    public readonly Accounts $accounts;
    public readonly Contacts $contacts;
    public readonly Templates $templates;
    public readonly Segments $segments;
    public readonly Campaigns $campaigns;
    public readonly Compose $compose;
    public readonly Emails $emails;
    public readonly Batch $batch;
    public readonly MailLogs $mailLogs;

    public function __construct(string $apiKey, ?string $baseUrl = null)
    {
        $key = trim($apiKey);
        if ($key === '') {
            throw new \InvalidArgumentException('Mailofly: apiKey is required');
        }
        $this->apiKey = $key;
        $this->baseUrl = Http::normalizeBaseUrl($baseUrl);
        $this->accounts = new Accounts($this);
        $this->contacts = new Contacts($this);
        $this->templates = new Templates($this);
        $this->segments = new Segments($this);
        $this->campaigns = new Campaigns($this);
        $this->compose = new Compose($this);
        $this->emails = new Emails($this);
        $this->batch = new Batch($this);
        $this->mailLogs = new MailLogs($this);
    }

    /** Unauthenticated discovery (`GET /api/v1`). */
    public static function discovery(?string $baseUrl = null): mixed
    {
        return Http::request(
            Http::normalizeBaseUrl($baseUrl),
            Http::API_PREFIX,
            'GET',
        );
    }

    /**
     * @param array<string, scalar|null>|null $query
     */
    public function request(string $path, string $method = 'GET', mixed $body = null, ?array $query = null): mixed
    {
        $suffix = $path === '' || $path[0] === '/' ? $path : '/' . $path;
        return Http::request(
            $this->baseUrl,
            Http::API_PREFIX . $suffix,
            $method,
            $this->apiKey,
            $body,
            $query,
        );
    }
}
