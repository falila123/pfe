<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Neutralise Vite pendant les tests : les vues utilisant @vite
        // se rendent sans nécessiter un build d'assets (utile en CI).
        $this->withoutVite();
    }
}
