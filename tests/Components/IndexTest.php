<?php

it('renders a counter', function () {
    expect(renderRoute('GET', '/', \Change\Me\Index::class))->toMatchXmlSnapshot();
});

it('renders a 404', function() {
    expect(renderRoute('GET', '/does-not-exist', \Change\Me\Index::class))->toMatchXmlSnapshot();
});
