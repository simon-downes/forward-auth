<?php declare(strict_types=1);

use spl\SPL;

require_once __DIR__.'/../vendor/autoload.php';

define('AUTH_LOG', realpath(__DIR__.'/../logs'). '/auth.log');

SPL::run(__DIR__. '/../');
