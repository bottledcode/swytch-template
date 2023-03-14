<?php

namespace Change\Me;

use Bottledcode\SwytchFramework\Hooks\Html\HeadTagFilter;
use Bottledcode\SwytchFramework\Language\LanguageAcceptor;
use Bottledcode\SwytchFramework\Template\Attributes\Component;
use Bottledcode\SwytchFramework\Template\Traits\RegularPHP;

#[Component('index')]
readonly class Index
{
	use RegularPHP;

	public function __construct(private LanguageAcceptor $language, private HeadTagFilter $htmlHead)
	{
	}

	public function render()
	{
		$this->htmlHead->setTitle('Hello World');

		$this->begin();
		?>
        <!DOCTYPE html>
        <html lang="{<?= $this->language->currentLanguage ?>}">
        <head>
        </head>
        <body>
        <h1>Hello world</h1>
        </body>
        </html>
		<?php
		return $this->end();
	}
}
