# Procore PHP SDK

NOTICE!! This is very much a work in progress.  Only a few endpoints are available and there are no tests.  Use at your own risk.

## Installation

You can install the package via composer:

```bash
composer require moab-tech/procore-php-sdk
```

## Usage
You can authenticate 4 different ways using a configuration array passed to the client.

The first is with an existing valid access token.

``` php
$procore = new MoabTech\Procore\Client([
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET',
            'access_token' => 'YOUR-ACCESS-TOKEN',
            'expires_in' => 'OPTIONAL: Should be in seconds'
        ]);
$procoreUser = $procore->me()->show();
```

If you don't have a valid token but you have a refresh token that hasn't been used yet, you can authenticate with that.  Please don't include an access_token value when using this method or it will try to use that for authentication instead.

``` php
$procore = new MoabTech\Procore\Client([
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET',
            'refresh_token' => 'YOUR-ACCESS-TOKEN'
        ]);
$procoreUser = $procore->me()->show();
```

If your application uses a Client Credentials grant type, then you can simply define the type and pass in your id and secret.  This method of authentication is best for service type requests as opposed to user requests.

``` php
$procore = new MoabTech\Procore\Client([
            'grant_type => 'client_credentials',
            'client_id' => 'YOUR-CLIENT-ID',
            'client_secret' => 'YOUR-CLIENT-SECRET'
        ]);
$procoreUser = $procore->me()->show();
```

Please refer to the Procore documentation for receiving a code to use via the Authorization Code grant type.  This method of authentication is used when you would like the user to authenticate to your app using their Procore username and password.  Any requests made will then be tied to this user.  Please keep in mind that the user will only be able to access endpoints that he/she has permissions for.

``` php
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
