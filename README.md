# mailofly

Official **PHP** client for the [Mailofly REST API](https://docs.mailofly.com/api).

Requires **PHP 8.1+**.

> **Source of truth:** developed in the [mailofly monorepo](https://github.com/godstark82/mailofly) under `packages/php`. This public repo is mirrored automatically on change.

## Install

```bash
composer require mailofly/mailofly
```

## Usage

```php
<?php

use Mailofly\Client;
use Mailofly\MailoflyException;

$client = new Client(getenv('MAILOFLY_API_KEY'));

try {
    $result = $client->emails->send([
        'from' => 'Acme <onboarding@example.com>',
        'to' => ['you@example.com'],
        'subject' => 'Hello',
        'html' => '<p>Hi from Mailofly</p>',
    ]);
    print_r($result['id']);
} catch (MailoflyException $e) {
    fwrite(STDERR, "{$e->status} {$e->error} {$e->detailMessage}\n");
}

// Batch send
$batch = $client->batch->send([
    ['from' => 'Acme <onboarding@example.com>', 'to' => ['a@b.com'], 'subject' => 'Hi', 'html' => '<p>1</p>'],
    ['from' => 'Acme <onboarding@example.com>', 'to' => ['c@d.com'], 'subject' => 'Hi', 'html' => '<p>2</p>'],
]);
```

## Docs

- [PHP guide](https://docs.mailofly.com/sdks/php)
- [API reference](https://docs.mailofly.com/api)

## Releasing

1. Bump `version` in `composer.json` (and this changelog) in the **monorepo** PR.
2. Merge to `main`/`master` → GitHub Action syncs this folder to `teamredevs/mailofly-php`.
3. Publish workflow creates a `v*` git tag; Packagist picks it up (connect the repo once).

## License

MIT
