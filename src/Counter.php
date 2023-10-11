<?php

namespace Change\Me;

use Bottledcode\SwytchFramework\Hooks\Common\Headers;
use Bottledcode\SwytchFramework\Router\Attributes\Route;
use Bottledcode\SwytchFramework\Router\Method;
use Bottledcode\SwytchFramework\Template\Attributes\Component;
use Bottledcode\SwytchFramework\Template\Parser\StreamingCompiler;
use Bottledcode\SwytchFramework\Template\Traits\Htmx;
use Bottledcode\SwytchFramework\Template\Traits\RegularPHP;

#[Component('counter')]
readonly class Counter
{
    use RegularPHP;
    use Htmx;

    public function __construct(private Headers $headers, private StreamingCompiler $compiler)
    {
    }

    /**
     * In real life, you would probably do a lot more in here. But this just show how it works.
     *
     * @param int $count The current count
     * @return string The rendered HTML
     */
    #[Route(Method::POST, '/api/count/add')]
    public function add(int $count): string
    {
        // we want to place the fragment in the #count div
        $this->retarget('#count');
        // now rerender the component but only the fragment with the id count-from
        return $this->renderFragment('count-form', $this->render($count + 1));
    }

    public function render(int $count = 0)
    {
        $this->begin();
        ?>
        <div id="count"
             xmlns:swytch="file://../vendor/bottledcode/swytch-framework/swytch.xsd">
            <!-- note: the fragment tag is NOT rendered in the client -->
            <swytch:fragment id="count-form">
                <form hx-post="/api/count">
                    <input type="hidden" name="count" value="<?= $count ?>">
                    <h1>{<?= __('Current count:') ?>} {<?= $count ?>}</h1>
                    <button type="submit" hx-post="/api/count/add"> +</button>
                    <button type="submit" hx-post="/api/count/sub"> -</button>
                </form>
            </swytch:fragment>
        </div>
        <?php
        return $this->end();
    }

    #[Route(Method::POST, '/api/count/sub')]
    public function sub(int $count): string
    {
        $this->retarget('#count');
        return $this->renderFragment('count-form', $this->render($count - 1));
    }
}
