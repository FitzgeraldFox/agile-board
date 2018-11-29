<?php

namespace App\Http\Controllers;

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
        if (!check_estimation($request->estimation)) {
            $ex = new \Exception();
            $ex->fields = [
                'estimation' => 'Неправильный формат оценки'
            ];
            throw $ex;
        }
        $task = check_task_id($request->id, null, 'id');
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
        check_task_id($request->taskId)->delete();
    }
}
