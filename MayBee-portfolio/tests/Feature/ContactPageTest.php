<?php

use Inertia\Testing\AssertableInertia as Assert;

it('renders the contact page', function () {
    $this->get('/contact')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Contact')
        );
});