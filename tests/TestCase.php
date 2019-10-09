<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

/**
 * Class TestCase
 * @package Tests
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;
    protected $faker;
    /**
     * Set up the test
     */
    public function setUp():void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }
    /**
     * Reset the migrations
     */
    public function tearDown():void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
