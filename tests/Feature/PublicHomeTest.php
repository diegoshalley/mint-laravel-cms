<?php

use App\Models\ContentItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the citizen-first homepage', function () {
    $this->get('/')->assertOk()->assertSee('How can we help?')->assertSee('Emergency numbers');
});

it('never displays draft content publicly', function () {
    ContentItem::factory()->create(['title' => 'Secret draft', 'status' => 'draft']);
    $this->get('/')->assertDontSee('Secret draft');
});
