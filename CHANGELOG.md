## 0.1.0

- Initial release: PHP client for Mailofly REST API v1.
- Resources: accounts, contacts, templates, segments (incl. membership), campaigns (runs & send), compose, mail logs.
- `Client::discovery()` for unauthenticated `GET /api/v1`.
- `MailoflyException` for API errors.
