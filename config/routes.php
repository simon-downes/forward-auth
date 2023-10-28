<?php declare(strict_types=1);

use app\PromptAction;
use app\AuthenticationAction;
use app\VerificationAction;
use app\SignoutAction;

return [

    'GET:/'  => PromptAction::class,
    'POST:/' => AuthenticationAction::class,

    'GET:/verify' => VerificationAction::class,

    'GET:/signout' => SignoutAction::class,

];
