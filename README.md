# Braze Bundle

Braze REST API integration for Symfony apps.

## Api Endpoints Documentation

Check the `docs/` directory.

## Installation

```bash
composer req lingoda/braze
```

### Bundle configuration

```yaml
# config/packages/braze.yaml

lingoda_braze:
  api_key: '%env(LINGODA__BRAZE_API_KEY)%' // (required)
  base_uri: 'https://rest.iad-01.braze.com' // (optiona) you can override the default api endpoint
  http_client: 'braze.client' // (optional) you can override the default http client with a service
```

## Testing

### Install dev dependencies

```bash
# Install dev dependecies
composer install --dev
```

### Run PHPUnit tests

```bash
vendor/bin/phpunit
```

### Run integration tests

```bash
LINGODA__BRAZE_API_KEY=api_key LINGODA__BRAZE_APP_ID=app_id vendor/bin/phpunit --group braze-integration
```

## TODO

-   Currency validation and ValueObject
-   Price validation and ValueObject
-   More integration test cases
-   Better error handling with ApiResponseProcessor
-   Add test coverage for error responses
-   Add more validation for Properties Object value types
-   Write documentation
-   Better handling for HttpTransportExceptions
