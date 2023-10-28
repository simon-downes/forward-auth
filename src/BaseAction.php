<?php declare(strict_types=1);

namespace app;

use spl\web\Request;

class BaseAction {

    protected $users;

    public function __construct() {

        // load user data
        $this->users = new UserList(env('USER_FILE', SPL_ROOT. '/.users'));

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
