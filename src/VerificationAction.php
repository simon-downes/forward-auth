<?php declare(strict_types=1);

namespace app;

use Exception;
use spl\SPL;
use spl\Log;
use spl\web\Request;
use spl\web\Response;
use spl\web\WebException;

class VerificationAction extends BaseAction {

    public function __invoke( Request $request ): array|string|Response {

        parent::validate($request);

        $user = $this->getCurrentUser($request);

        if( $user ) {
            return Response::text('OK')->setHeader('Remote-User', $user);
        }

        // where should we go after authentication succeeds
        $target = "{$request->getHeader('x-forwarded-proto')}://{$request->getHeader('x-forwarded-host')}{$request->getHeader('x-forwarded-uri')}";

        return Response::redirect("https://{$request->getHost()}/?rd={$target}")->setCookie(
            name: env('COOKIE_NAME', 'auth'),
            value: '',
            domain: env('AUTH_DOMAIN'),
            expires: time() - 86400,
        );

    }

}
