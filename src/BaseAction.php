<?php declare(strict_types=1);

namespace app;

use spl\web\Request;
use spl\web\Response;
use spl\web\WebException;

abstract class BaseAction {

    protected $users;

    public function __construct() {

        // load user data
        $this->users = new UserList(env('USER_FILE', SPL_ROOT. '/.users'));

    }

    public function validate( Request $request ): void {

        if( !env('APP_SECRET') ) {
            throw new WebException("APP_SECRET is not specified or is empty!", 500);
        }

        if( !env('AUTH_DOMAIN') ) {
            throw new WebException("AUTH_DOMAIN is not specified or is empty!", 500);
        }

    }

    /**
     * Return the username of the currently signed in user, if any
     */
    protected function getCurrentUser( Request $request ): string {

        return $this->users->verifyAuthToken(
            $request->getCookie(env('COOKIE_NAME', 'auth'))
        );

    }

}
