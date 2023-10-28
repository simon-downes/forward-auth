<?php declare(strict_types=1);

namespace app;

use spl\web\WebException;

class UserList {

    protected array $users = [];

    public function __construct( $source ) {

        $this->loadUsersFromFile($source);

    }

    public function exists( string $username ): bool {
        return isset($this->users[$username]);
    }

    public function authenticate( string $username, string $password ): bool {

        return isset($this->users[$username]) && password_verify($password, $this->users[$username]);

    }

    protected function loadUsersFromFile( string $filename) {

        foreach( file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line ) {
            # usernames begin with a letter and contain alphanumeric characters and hyphens
            # passwords begin with a $ character
            # whitespace is ignored
            if( preg_match("/\\s*([a-z0-9-]+)\\s+(\\$.*)\\s*/i", $line, $matches) ) {
                $this->users[$matches[1]] = $matches[2];
            }
        }

    }

    public function generateAuthToken( string $username ) {

        $secret = env('APP_SECRET');

        if( empty($secret) ) {
            throw new WebException("APP_SECRET is not specified or is empty!", 500);
        }

        if( !$this->exists($username) ) {
            throw new WebException("Can't generate auth token for unknown user: {$username}", 500);
        }

        // include the date so that tokens will automatically expire every day
        return $username. '~'. sha1($username. $this->users[$username]. date('Y-m-d'). $secret);

    }

    public function verifyAuthToken( string $token ): string {

        $username = '';

        if( preg_match('/([a-z0-9-]+)~+.*/', $token, $matches) ) {
            $username = $matches[1];
        }

        // valid if the user exists and the generated token is the same as the one specified
        if( $this->exists($username) && ($this->generateAuthToken($username, $this->users[$username]) == $token) ) {
            return $username;
        }

        return '';

    }

}
