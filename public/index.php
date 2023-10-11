<?php

use Bottledcode\SwytchFramework\App;
use Bottledcode\SwytchFramework\Template\Interfaces\AuthenticationServiceInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/attributes.php';

$app = new App(function_exists('xdebug_break'), \Change\Me\Index::class, [
    \Psr\Log\LoggerInterface::class => function () {
        $logger = new \Monolog\Logger('app');
        $handler = new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Level::Debug);
        $handler->setFormatter(new Monolog\Formatter\JsonFormatter());
        $logger->pushHandler($handler);

        return $logger;
    },
	AuthenticationServiceInterface::class => \DI\autowire(\Change\Me\AuthenticationService::class),
], registerErrorHandler: true);

$app->run();
