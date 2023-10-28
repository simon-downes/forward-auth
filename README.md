# A Simple Forward-Auth Implementation for Caddy

My toy [`forward-auth`](https://caddyserver.com/docs/caddyfile/directives/forward_auth) implementation for Caddy - don't actually use this!

## Configuration

`.env`

- `AUTH_DOMAIN` - the domain name being secured
- `COOKIE_NAME` - name of the authentication cookie, defaults to `auth`
- `APP_SECRET` - random string used when generating auth tokens
- `APP_LOG_FILE` - location of log file

`.users`

A simple text file listing username and hashed passwords.
```
<username><whitespace><password>
```

- `<username>` - usernames begin with a letter and contain alphanumeric characters and hyphens
- `<whitespace>` - any amount of whitespace
- `<password>` - passwords are [hashes](https://www.php.net/manual/en/function.password-hash.php) and begin with a `$`

Lines beginning with `#` are considered comments (and ignored)
Lines not matching the desired format are ignored

## Caddy Configuration

```
forward_auth <app_location> {
    uri /verify?
    copy_headers Remote-User

    header_up Host {upstream_hostport}
}
```

`<app_location>` is the address of wherever the app is hosted.

## Routes

- `GET /` - show current user; or a sign in page
- `POST /` - attempt to sign in with username and password from login form
- `GET /verify` - verify the auth cookie; redirects to sign-in page if no valid cookie
- `GET /signout` - removes current auth cookie
