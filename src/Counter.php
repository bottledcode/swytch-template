<?php

namespace Change\Me;

use Bottledcode\SwytchFramework\Hooks\Common\Headers;
use Bottledcode\SwytchFramework\Router\Attributes\Route;
use Bottledcode\SwytchFramework\Router\Method;
use Bottledcode\SwytchFramework\Template\Attributes\Component;
use Bottledcode\SwytchFramework\Template\Compiler;
use Bottledcode\SwytchFramework\Template\Traits\Htmx;
use Bottledcode\SwytchFramework\Template\Traits\RegularPHP;

#[Component('counter')]
readonly class Counter
{
	use RegularPHP;
	use Htmx;

	public function __construct(private Headers $headers, private Compiler $compiler)
	{
	}

	#[Route(Method::POST, '/api/count/add')]
	public function add(array $state, string $target_id): string
	{
		return $this->rerender($target_id, [...$state, 'count' => ($state['count'] ?? 0) + 1]);
	}

	#[Route(Method::POST, '/api/count/sub')]
	public function sub(array $state, string $target_id): string
	{
		return $this->rerender($target_id, [...$state, 'count' => ($state['count'] ?? 0) - 1]);
	}

	public function render(int $count = 0)
	{
		$this->begin();
		?>
        <div>
            <form hx-post="/api/count">
                <h1>{<?= __('Current count:') ?>} {<?= $count ?>}</h1>
                <button type="submit" hx-post="/api/count/add"> + </button>
                <button type="submit" hx-post="/api/count/sub"> - </button>
            </form>
        </div>
		<?php
		return $this->end();
	}
}
