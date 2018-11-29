<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 29.11.18
 * Time: 15:34
 */

namespace Tests\Unit;


use Illuminate\Support\Facades\Artisan;

class TestCase extends \Tests\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite_testing');
        putenv('DB_DATABASE=agile_board_test');

        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}