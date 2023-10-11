<?php

it('renders a counter', function () {
    expect(render(\Change\Me\Counter::class))->toMatchHtmlSnapshot();
});

it('renders a counter with a value', function() {
    expect(render(\Change\Me\Counter::class, ['count' => 100]))->toMatchHtmlSnapshot();
});
