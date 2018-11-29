<?php

namespace App\Http\Controllers;

use App\Facades\SprintService;
use App\Facades\TaskService;
use App\Sprint;
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
            'Week' => 'required|integer|max:12',
            'Year' => 'required|integer',
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
        SprintService::checkSprintId($request->sprintId);
        $task = TaskService::checkTaskId($request->taskId, $taskIdParts);
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
        $sprint = SprintService::checkSprintId($request->sprintId);
        $sprint->active = true;
        $sprint->save();
    }
}
