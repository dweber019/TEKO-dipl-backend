<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Call this template method before each test method is run.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed', [ '--class' => 'SmallSchool' ]);

        $this->withoutMiddleware([
          \Illuminate\Auth\Middleware\Authenticate::class,
        ]);
    }

    protected  function tearDown()
    {
        Storage::deleteDirectory('taskitems');

        parent::tearDown();
    }
}
