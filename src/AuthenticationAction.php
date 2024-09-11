<?php declare(strict_types=1);

namespace app;

use spl\SPL;
use spl\Log;
use spl\web\Request;
use spl\web\Response;

class AuthenticationAction extends BaseAction {

    public function __invoke( Request $request ): array|string|Response {

        parent::validate($request);

        $username = $request->lookup('username');
        $password = $request->lookup('password');

        // failed authentication so show login form again
        if( ! $this->users->authenticate($username, $password) ) {

            // log auth failure if user exists
            $this->users->exists($username) && Log::warning("Failed sign-in attempt for {$username}", AUTH_LOG);

            return SPL::render('login.html', [
                'title'    => env('APP_NAME', 'Auth - '. env('AUTH_DOMAIN')),
                'error'    => true,
                'username' => $username,
            ]);

        }

        Log::info("Signed in: {$username}", AUTH_LOG);

        // success! respond with auth cookie
        return Response::redirect($request->lookup('rd', '/'))->setCookie(
            name: env('COOKIE_NAME', 'auth'),
            value: $this->users->generateAuthToken($username),
            domain: env('AUTH_DOMAIN'),
        );

    }

}
