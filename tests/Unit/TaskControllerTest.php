<?php

namespace Tests\Unit;

use Mockery;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Создание задачи
     */
    public function testCreate(): void
    {
        $request = [
            'Title' => 'Title1',
            'Description' => 'Description1'
        ];
        $response = $this->post('/api/tasks', $request);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Title1',
            'description' => 'Description1'
        ]);
        $this->assertEquals('TASK-1', $response->getOriginalContent());
        $response = $this->call('POST', '/api/tasks', $request);
        if (isset($response->getOriginalContent()['Errors'])) {
            $this->assertArrayHasKey('Global', $response->getOriginalContent()['Errors']);
            if (isset($response->getOriginalContent()['Errors']['Global'])) {
                $this->assertEquals(
                    'Невозможно создать задачу, т.к. задача с такими параметрами уже есть',
                    $response->getOriginalContent()['Errors']['Global']
                );
            }
        }
    }

    /*
     * Оценка задачи
     */
    public function testSetEstimate(): void
    {
        $this->post('/api/tasks', [
            'Title' => 'Title1',
            'Description' => 'Description1'
        ]);
        $this->post('/api/estimate/task', [
            'id' => 'TASK-1',
            'estimation' => '1d'
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'estimation' => '1d'
        ]);
    }

    /*
     * Закрытие задачи
     */
    public function testTaskClose(): void
    {
        $this->post('/api/tasks', [
            'Title' => 'Title1',
            'Description' => 'Description1'
        ]);
        $this->post('/api/tasks/close', [
            'taskId' => "TASK-1"
        ]);
        $this->assertSoftDeleted('tasks', [
            'id' => 1
        ]);
    }
}
