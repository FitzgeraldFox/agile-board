<?php

namespace Tests\Unit;

use Mockery;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SprintControllerTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Создание спринта
     */
    public function testCreate(): void
    {
        $request = [
            'Week' => 12,
            'Year' => 18
        ];
        $response = $this->post('/api/sprints', $request);
        $this->assertDatabaseHas('sprints', [
            'id' => '18-12'
        ]);
        $this->assertEquals('18-12', $response->getOriginalContent());
        $response = $this->call('POST', '/api/sprints', $request);
        if (isset($response->getOriginalContent()['Errors'])) {
            $this->assertArrayHasKey('Global', $response->getOriginalContent()['Errors']);
            if (isset($response->getOriginalContent()['Errors']['Global'])) {
                $this->assertEquals(
                    'Невозможно создать спринт, т.к. спринт с такими параметрами уже есть',
                    $response->getOriginalContent()['Errors']['Global']
                );
            }
        }
    }

    /*
     * Добавление задачи в Спринт
     */
    public function testAddTask(): void
    {
        $this->post('/api/tasks', [
            'Title' => 'Title1',
            'Description' => 'Description1'
        ]);
        $this->post('/api/sprints', [
            'Week' => 12,
            'Year' => 18
        ]);
        $request = [
            'sprintId' => '18-12',
            'taskId' => 'TASK-1'
        ];
        $this->post('/api/sprints/add-task', $request);
        $this->assertDatabaseHas('tasks', [
            'id' => 1,
            'sprint_id' => '18-12'
        ]);
    }

    /*
     * Запуск спринта
     */
    public function testStart(): void
    {
        $this->post('/api/sprints', [
            'Week' => 12,
            'Year' => 18
        ]);
        $this->post('/api/sprints/start', [
            'sprintId' => '18-12'
        ]);
        $this->assertDatabaseHas('sprints', [
            'id' => '18-12',
            'active' => true
        ]);
    }
}
