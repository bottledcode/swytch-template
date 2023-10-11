<?php

use Bottledcode\SwytchFramework\Language\LanguageAcceptor;
use Bottledcode\SwytchFramework\Template\Interfaces\AuthenticationServiceInterface;
use Bottledcode\SwytchFramework\Template\Interfaces\EscaperInterface;

require_once __DIR__ . '/../vendor/attributes.php';

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getTestContainer(): \DI\Container
{
    $container = new \DI\Container([
        LanguageAcceptor::class => fn() => new LanguageAcceptor('en', ['en'], '.'),
        EscaperInterface::class => \DI\autowire(\Bottledcode\SwytchFramework\Template\Escapers\Variables::class),
        // todo: replace with mock
        AuthenticationServiceInterface::class => \DI\autowire(\Change\Me\AuthenticationService::class),
    ]);
    $container->get(LanguageAcceptor::class)->loadLanguage();

    return $container;
}

/**
 * @param class-string $component
 * @param array<string|int|bool> $props
 * @return string
 */
function renderRoute(string $method, string $path, string $component, array $props = []): string
{
    $container = getTestContainer();
    $container->set(\Psr\Http\Message\ServerRequestInterface::class, new \Nyholm\Psr7\ServerRequest($method, $path));
    $component = $container->make($component);
    $compiler = $container->make(\Bottledcode\SwytchFramework\Template\Parser\StreamingCompiler::class);

    foreach(\olvlvl\ComposerAttributeCollector\Attributes::findTargetClasses(\Bottledcode\SwytchFramework\Template\Attributes\Component::class) as $targetClass) {
        $compiler->registerComponent($targetClass);
    }

    return $compiler->compileShallow($component->render(...$props));
}

function render(string $component, array $props = []): string {
    $container = getTestContainer();
    $component = $container->make($component);
    $compiler = $container->make(\Bottledcode\SwytchFramework\Template\Parser\StreamingCompiler::class);

    foreach(\olvlvl\ComposerAttributeCollector\Attributes::findTargetClasses(\Bottledcode\SwytchFramework\Template\Attributes\Component::class) as $targetClass) {
        $compiler->registerComponent($targetClass);
    }

    return $compiler->compileShallow($component->render(...$props));
}
