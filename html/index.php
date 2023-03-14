<?php

use Bottledcode\SwytchFramework\App;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/attributes.php';

$app = new App(function_exists('xdebug_break'), \Change\Me\Index::class, [
	LoggerInterface::class => \DI\create(\Monolog\Logger::class)
		->constructor('change me')
		->method('pushHandler', \DI\get(StreamHandler::class))
		->method('pushProcessor', \DI\get(MemoryUsageProcessor::class))
		->method('pushProcessor', \DI\get(MemoryPeakUsageProcessor::class))
		->method('pushProcessor', \DI\get(WebProcessor::class)),
	StreamHandler::class => \DI\create(StreamHandler::class)
		->constructor('php://stderr', 100),
], registerErrorHandler: true);

$app->run();
