<?php declare(strict_types=1);

namespace app;

use spl\SPL;
use spl\web\Request;
use spl\web\Response;

class PromptAction extends BaseAction {

    public function __invoke( Request $request ): array|string|Response {

        parent::validate($request);

        return SPL::render('login.html', [
            'title' => env('APP_NAME', 'Auth - '. env('AUTH_DOMAIN')),
            'user'  => $this->getCurrentUser($request),
        ]);

    }

}
