<?php declare(strict_types=1);

namespace app;

use spl\SPL;
use spl\web\Request;
use spl\web\Response;

class PromptAction extends BaseAction {

    public function __invoke( Request $request ): array|string|Response {

        return SPL::render('login.html', [
            'user' => $this->getCurrentUser($request),
        ]);

    }

}
