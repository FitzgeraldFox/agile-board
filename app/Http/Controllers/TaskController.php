<?php

namespace App\Http\Controllers;

use App\Facades\TaskService;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Создание задачи
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function create(Request $request): string
    {
        validate_args($request->all(), [
            'Title' => 'required|string',
            'Description' => 'required|string'
        ]);

        $createdTask = Task::firstOrCreate([
            'title' => $request->Title,
            'description' => $request->Description
        ]);
        if ($createdTask->wasRecentlyCreated) {
            return "TASK-{$createdTask->id}";
        } else {
            $ex = new \Exception();
            $ex->global = 'Невозможно создать задачу, т.к. задача с такими параметрами уже есть';
            throw $ex;
        }
    }

    /**
     * Оценка задачи
     * @param Request $request
     * @throws \Exception
     */
    public function setEstimate(Request $request): void
    {
        validate_args($request->all(), [
            'id' => 'required|string',
            'estimation' => 'required|string'
        ]);
        TaskService::checkEstimation($request->estimation);
        $task = TaskService::checkTaskId($request->id, null, 'id');
        $task->estimation = $request->estimation;
        $task->save();
    }

    /**
     * Закрытие задачи
     * @param Request $request
     * @throws \Exception
     */
    public function close(Request $request): void
    {
        validate_args($request->all(), [
            'taskId' => 'required|string'
        ]);
        TaskService::checkTaskId($request->taskId)->delete();
    }
}
