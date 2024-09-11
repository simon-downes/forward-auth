<?php declare(strict_types=1);

namespace app;

use Exception;
use spl\SPL;
use spl\Log;
use spl\web\Request;
use spl\web\Response;

class SignoutAction extends BaseAction {

    public function __invoke( Request $request ): array|string|Response {

        parent::validate($request);

        // do we already have a user?
        $user = $this->getCurrentUser($request);

        if( $user ) {
            Log::info("Signed out: {$user}", AUTH_LOG);
        }

        // remove auth cookie and redirect to target or /
        return Response::redirect($request->lookup('rd', '/'))->setCookie(
            name: env('COOKIE_NAME', 'auth'),
            value: '',
            domain: env('AUTH_DOMAIN'),
            expires: time() - 86400,
        );

    }

}
