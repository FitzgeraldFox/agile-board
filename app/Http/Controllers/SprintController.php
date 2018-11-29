<?php

namespace App\Http\Controllers;

use App\Sprint;
use App\Task;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    /**
     * Создание спринта
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function create(Request $request): string
    {
        validate_args($request->all(), [
            'Week' => 'required|string|max:2',
            'Year' => 'required|string|max:4',
        ]);
        $createdSprint = Sprint::firstOrCreate([
            'id' => "{$request->Year}-{$request->Week}"
        ]);
        if ($createdSprint->wasRecentlyCreated) {
            return "{$request->Year}-{$request->Week}";
        } else {
            $ex = new \Exception();
            $ex->global = 'Невозможно создать спринт, т.к. спринт с такими параметрами уже есть';
            throw $ex;
        }
    }

    /**
     * Добавление задачи в Спринт
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function addTask(Request $request): void
    {
        validate_args($request->all(), [
            'sprintId' => 'required|string|max:5',
            'taskId' => 'required|string',
        ]);

        $taskIdParts = explode('-', $request->taskId);
        check_sprint_id($request->sprintId);
        $task = check_task_id($request->taskId, $taskIdParts);
        $task->sprint_id = $request->sprintId;
        $task->save();
    }

    /**
     * Запуск спринта
     * @param Request $request
     * @throws \Exception
     */
    public function start(Request $request): void
    {
        validate_args($request->all(), [
            'sprintId' => 'required|string|max:5',
        ]);
        $sprint = check_sprint_id($request->sprintId);
        $sprint->active = true;
        $sprint->save();
    }
}
