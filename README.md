# Procore PHP SDK

NOTICE!! This is very much a work in progress.  Only a few endpoints are available and there are no tests.  Use at your own risk.

## Installation

You can install the package via composer:

```bash
composer require moab-tech/procore-php-sdk
```

## Usage

``` php
// with an existing access token
$procore = new MoabTech\Procore\Client([
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET',
            'access_token' => 'YOUR-ACCESS-TOKEN',
            'expires_in' => 'OPTIONAL: Should be in seconds'
        ]);
$procoreUser = $procore->me()->show();
```

``` php
// with a refresh token
$procore = new MoabTech\Procore\Client([
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET',
            'refresh_token' => 'YOUR-ACCESS-TOKEN'
        ]);
$procoreUser = $procore->me()->show();
```

``` php
// using Client Credentials
$procore = new MoabTech\Procore\Client([
            'grant_type => 'client_credentials',
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET'
        ]);
$procoreUser = $procore->me()->show();
```

``` php
// using Authorization Code
$procore = new MoabTech\Procore\Client([
            'grant_type => 'authorization_code',
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET',
            'code' => 'CODE-YOU-RECEIVED-DURING-AUTHORIZATION',
            'redirect_uri' => 'ie https://example.test/procore/callback'
        ]);
$procoreUser = $procore->me()->show();
```

## Testing

Coming Soon!

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Chris Baswell](https://github.com/chrisbaswell)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
