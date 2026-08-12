<?php

use Inertia\Testing\AssertableInertia as Assert;

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->missing('laravelVersion')
            ->missing('phpVersion'));
});
