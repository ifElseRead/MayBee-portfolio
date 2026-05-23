<?php

use Inertia\Testing\AssertableInertia as Assert;

it('loads the beekeeping home page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('renders the correct inertia component and data', function () {
    $this->get('/')
        ->assertInertia(fn (Assert $page) => $page
            ->component('John') // Verifies resources/js/Pages/John.vue is used
            ->has('auth.user')   // Breeze usually passes auth state by default
        );
});