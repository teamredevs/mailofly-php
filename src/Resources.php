<?php

declare(strict_types=1);

namespace Mailofly;

/** @internal */
final class Identities
{
    public function __construct(private readonly Client $client)
    {
    }

    public function list(): mixed
    {
        return $this->client->request('/identities');
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/identities/' . rawurlencode($id));
    }

    /** @param array<string, mixed> $body */
    public function update(string $id, array $body): mixed
    {
        return $this->client->request('/identities/' . rawurlencode($id), 'PATCH', $body);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('/identities/' . rawurlencode($id), 'DELETE');
    }
}

/** @internal @deprecated Use Identities */
final class Accounts extends Identities
{
}

/** @internal */
final class Contacts
{
    public function __construct(private readonly Client $client)
    {
    }

    public function list(?string $segmentId = null): mixed
    {
        return $this->client->request(
            '/contacts',
            'GET',
            null,
            $segmentId !== null ? ['segment_id' => $segmentId] : null,
        );
    }

    /** @param array<string, mixed> $body */
    public function create(array $body): mixed
    {
        return $this->client->request('/contacts', 'POST', $body);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/contacts/' . rawurlencode($id));
    }

    /** @param array<string, mixed> $body */
    public function update(string $id, array $body): mixed
    {
        return $this->client->request('/contacts/' . rawurlencode($id), 'PATCH', $body);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('/contacts/' . rawurlencode($id), 'DELETE');
    }
}

/** @internal */
final class Templates
{
    public function __construct(private readonly Client $client)
    {
    }

    public function list(): mixed
    {
        return $this->client->request('/templates');
    }

    /** @param array<string, mixed> $body */
    public function create(array $body): mixed
    {
        return $this->client->request('/templates', 'POST', $body);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/templates/' . rawurlencode($id));
    }

    /** @param array<string, mixed> $body */
    public function update(string $id, array $body): mixed
    {
        return $this->client->request('/templates/' . rawurlencode($id), 'PATCH', $body);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('/templates/' . rawurlencode($id), 'DELETE');
    }
}

/** @internal */
final class SegmentContacts
{
    public function __construct(private readonly Client $client)
    {
    }

    public function list(string $segmentId): mixed
    {
        return $this->client->request('/segments/' . rawurlencode($segmentId) . '/contacts');
    }

    /** @param array<string, mixed> $body */
    public function add(string $segmentId, array $body): mixed
    {
        return $this->client->request(
            '/segments/' . rawurlencode($segmentId) . '/contacts',
            'POST',
            $body,
        );
    }

    public function remove(string $segmentId, string $contactId): mixed
    {
        return $this->client->request(
            '/segments/' . rawurlencode($segmentId) . '/contacts/' . rawurlencode($contactId),
            'DELETE',
        );
    }
}

/** @internal */
final class Segments
{
    public readonly SegmentContacts $contacts;

    public function __construct(private readonly Client $client)
    {
        $this->contacts = new SegmentContacts($client);
    }

    public function list(): mixed
    {
        return $this->client->request('/segments');
    }

    /** @param array<string, mixed> $body */
    public function create(array $body): mixed
    {
        return $this->client->request('/segments', 'POST', $body);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/segments/' . rawurlencode($id));
    }

    /** @param array<string, mixed> $body */
    public function update(string $id, array $body): mixed
    {
        return $this->client->request('/segments/' . rawurlencode($id), 'PATCH', $body);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('/segments/' . rawurlencode($id), 'DELETE');
    }
}

/** @internal */
final class Campaigns
{
    public function __construct(private readonly Client $client)
    {
    }

    public function list(): mixed
    {
        return $this->client->request('/campaigns');
    }

    /** @param array<string, mixed> $body */
    public function create(array $body): mixed
    {
        return $this->client->request('/campaigns', 'POST', $body);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/campaigns/' . rawurlencode($id));
    }

    /** @param array<string, mixed> $body */
    public function update(string $id, array $body): mixed
    {
        return $this->client->request('/campaigns/' . rawurlencode($id), 'PATCH', $body);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('/campaigns/' . rawurlencode($id), 'DELETE');
    }

    public function runs(string $id): mixed
    {
        return $this->client->request('/campaigns/' . rawurlencode($id) . '/runs');
    }

    /** @param array<string, mixed>|null $body */
    public function send(string $id, ?array $body = null): mixed
    {
        return $this->client->request(
            '/campaigns/' . rawurlencode($id) . '/send',
            'POST',
            $body ?? ['send_now' => true],
        );
    }
}

/** @internal */
final class Compose
{
    public function __construct(private readonly Client $client)
    {
    }

    /** @param array<string, mixed> $params */
    public function send(array $params): mixed
    {
        return $this->client->request('/emails', 'POST', $params);
    }
}

/** @internal */
final class Emails
{
    public function __construct(private readonly Client $client)
    {
    }

    /** @param array<string, mixed> $params */
    public function send(array $params): mixed
    {
        return $this->client->request('/emails', 'POST', $params);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/emails/' . rawurlencode($id));
    }

    /**
     * @param array{limit?: int, after?: string, before?: string}|null $query
     */
    public function list(?array $query = null): mixed
    {
        /** @var array<string, scalar|null>|null $q */
        $q = $query;
        return $this->client->request('/emails', 'GET', null, $q);
    }
}

/** @internal */
final class Batch
{
    public function __construct(private readonly Client $client)
    {
    }

    /** @param list<array<string, mixed>> $emails */
    public function send(array $emails): mixed
    {
        return $this->client->request('/emails/batch', 'POST', $emails);
    }
}

/** @internal */
final class MailLogs
{
    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @param array{
     *   page?: int,
     *   page_size?: int,
     *   campaign_id?: string,
     *   account_id?: string,
     *   campaign_run_id?: string,
     *   status?: string
     * }|null $query
     */
    public function list(?array $query = null): mixed
    {
        /** @var array<string, scalar|null>|null $q */
        $q = $query;
        return $this->client->request('/mail-logs', 'GET', null, $q);
    }
}

/** @internal */
final class AutomationRuns
{
    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @param array{status?: string, limit?: int}|null $query
     */
    public function list(string $automationId, ?array $query = null): mixed
    {
        /** @var array<string, scalar|null>|null $q */
        $q = $query;
        return $this->client->request(
            '/automations/' . rawurlencode($automationId) . '/runs',
            'GET',
            null,
            $q,
        );
    }

    public function get(string $automationId, string $runId): mixed
    {
        return $this->client->request(
            '/automations/' . rawurlencode($automationId) . '/runs/' . rawurlencode($runId)
        );
    }
}

/** @internal */
final class Automations
{
    public readonly AutomationRuns $runs;

    public function __construct(private readonly Client $client)
    {
        $this->runs = new AutomationRuns($client);
    }

    /**
     * @param array{status?: string, limit?: int}|null $query
     */
    public function list(?array $query = null): mixed
    {
        /** @var array<string, scalar|null>|null $q */
        $q = $query;
        return $this->client->request('/automations', 'GET', null, $q);
    }

    /** @param array<string, mixed> $body */
    public function create(array $body): mixed
    {
        return $this->client->request('/automations', 'POST', $body);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/automations/' . rawurlencode($id));
    }

    /** @param array<string, mixed> $body */
    public function update(string $id, array $body): mixed
    {
        return $this->client->request('/automations/' . rawurlencode($id), 'PATCH', $body);
    }

    public function delete(string $id): mixed
    {
        return $this->client->request('/automations/' . rawurlencode($id), 'DELETE');
    }

    public function stop(string $id): mixed
    {
        return $this->client->request('/automations/' . rawurlencode($id) . '/stop', 'POST');
    }

    public function duplicate(string $id): mixed
    {
        return $this->client->request('/automations/' . rawurlencode($id) . '/duplicate', 'POST');
    }
}

/** @internal */
final class Events
{
    public function __construct(private readonly Client $client)
    {
    }

    /** @param array<string, mixed> $params */
    public function send(array $params): mixed
    {
        return $this->client->request('/events/send', 'POST', $params);
    }

    /**
     * @param array{name?: string, email?: string, limit?: int}|null $query
     */
    public function list(?array $query = null): mixed
    {
        /** @var array<string, scalar|null>|null $q */
        $q = $query;
        return $this->client->request('/events', 'GET', null, $q);
    }

    public function get(string $id): mixed
    {
        return $this->client->request('/events/' . rawurlencode($id));
    }
}

