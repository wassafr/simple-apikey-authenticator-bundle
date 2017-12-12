WassaSimpleApiKeyAuthenticatorBundle
==================
The **WassaSimpleApiKeyAuthenticatorBundle** bundle allows you to add a very simple and light way to authenticate HTTP requests with an API key.
It doesn't rely on existing Symfony authentication mechanisms as they require to define some kind of user class to work.

Installation
------------
Require the `wassafr/simple-apikey-authenticator-bundle` package in your composer.json and update
your dependencies.

    $ composer require wassafr/simple-apikey-authenticator-bundle

Register the bundle in `app/AppKernel.php`:

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Wassa\SimpleApiKeyAuthenticatorBundle\WassaSimpleApiKeyAuthenticatorBundle(),
    );
}
```

To enable the configuration, edit the config.yml file as follow:

```yaml
# app/config/config.yml
wassa_simple_api_key_authenticator:
    api_key: <YOUR_API_KEY>
    default_action: check | dont_check
    order: secured,unsecured | unsecured,secured
    secured_patterns:
        - ^\/api\/(?!doc)
    unsecured_patterns:
        - ^\/api\/(?!doc)
        - ^\/admin
        - ^\/bundles
        - ^\/toto
```

Configuration
-------------

* `api_key`: pretty straightforward
* `default_action`: should the bundle allow or deny access if not API key is configured, if the requested URL doesn't match any configured pattern or when no API key is provided
* `order`: the order the request URL will be evaluated against the configured patterns. Pay attention to this as it could lead to unwanted acess or the opposite
* `secured_patterns`: URL patterns that will required a valid API key
* `unsecured_patterns`: URL patterns that will NOT required a valid API key

Most of the time you won't need to configure both `secured_patterns` and `unsecured_patterns`, but who knows.