# Amsterdam Standard FAQ

## Passbolt configuration

In order to run application you need valid gpg key.
Alternatively you can use unsecure keys provided by passbolt,
and use default configuration from passbolt.default.php:124:146

Copy `passbolt.default.php` to `passbolt.php`

To work properly with ldap you need to set this flag to false:
```
'registration' => [
    'public' => false
],
```

## Mailhog

In config/app.php or .env, and config/passbolt.php file set:

```
EMAIL_TRANSPORT_DEFAULT_HOST=mailhog
EMAIL_TRANSPORT_DEFAULT_PORT=1025
```

## SSL

There's `certs/` forlder which contains self-signed certificates,
which are used by `docker-compose.yml`
You should add this to hosts file, and of course configure app url in `config/passbolt.php`:
```
127.0.0.1 passbolt.local
```