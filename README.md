# Newsletter (Statamic Addon)

Lightweight newsletter signup + confirmation flow with CP management and a dashboard widget.

## Features
- Signup with confirmation email
- Unsubscribe flow
- CP subscriber list + status management
- Dashboard widget for recent signups
- Eloquent-backed storage (`mailing_list_signups`)

## Installation

### Local path (development)
Add a path repository and require the package:

```json
{
  "repositories": [
    {"type": "path", "url": "addons/stuartpringle/newsletter", "options": {"symlink": true}}
  ],
  "require": {
    "stuartpringle/newsletter": "*@dev"
  }
}
```

Then run:

```bash
composer update stuartpringle/newsletter
```

### GitHub
Add a VCS repository and require the package:

```json
{
  "repositories": [
    {"type": "vcs", "url": "https://github.com/stuartpringle/newsletter"}
  ],
  "require": {
    "stuartpringle/newsletter": "dev-main"
  }
}
```

## Configuration
Publish the config:

```bash
php artisan vendor:publish --tag=newsletter-config
```

Config file: `config/newsletter.php`

- `rate_limit.max_attempts` (default: 5)
- `rate_limit.decay_seconds` (default: 60)
- `honeypot_field` (default: `name`)
- `confirmation_subject` (default: `Confirm Newsletter`)

## Routes

Front-end:
- `POST /newsletter/signup` (`newsletter.signup`)
- `GET /newsletter/confirm/{token}` (`newsletter.confirm`)
- `GET /newsletter/unsubscribe/{token}` (`newsletter.unsubscribe.show`)
- `POST /newsletter/unsubscribe/{token}` (`newsletter.unsubscribe`)

Control Panel:
- `GET /cp/newsletter` (`statamic.cp.newsletter.index`)
- `POST /cp/newsletter/add`
- `POST /cp/newsletter/resend/{subscriber}`
- `POST /cp/newsletter/status/{subscriber}`
- `DELETE /cp/newsletter/{subscriber}`

## Views
This addon ships its own email + CP views. Front-end form UI is expected to be implemented in your site templates and should post to `route('newsletter.signup')`.

## License
Proprietary
