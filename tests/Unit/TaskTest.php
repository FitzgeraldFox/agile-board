<?php

namespace Tests\Unit;

use InvalidArgumentException;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    protected $request;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateIncorrectArguments()
    {
        $this->request = [];
        $response = $this->call('POST', '/api/tasks', []);
        $this->assertArrayHasKey('Errors', $response->getOriginalContent());
        if (isset($response->getOriginalContent()['Errors'])) {
            $this->assertArrayHasKey('Fields', $response->getOriginalContent()['Errors']);
            if (isset($response->getOriginalContent()['Errors']['Fields'])) {
                $this->assertCount(2, $response->getOriginalContent()['Errors']['Fields']);
                if (count($response->getOriginalContent()['Errors']['Fields']) == 2) {
                    $this->assertArrayHasKey('Title', $response->getOriginalContent()['Errors']['Fields']);
                    $this->assertArrayHasKey('Description', $response->getOriginalContent()['Errors']['Fields']);
                }
            }
        }
    }

    public function testCreateWasRecentlyCreated()
    {
        $mock = Mockery::mock('Eloquent', 'Task');
        $mock->shouldReceive()
            ->once();
        $this->app->instance('Post', $mock);
    }
}
