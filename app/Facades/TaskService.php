<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 29.11.18
 * Time: 11:59
 */

namespace App\Facades;

use App\Task;
use Illuminate\Support\Facades\Facade;

class TaskService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'task';
    }

    protected static function checkEstimation(string $est)
    {
        if (!$checkResult = preg_match("/^(\d[hdwmy])+$/", $est)) {
            $ex = new \Exception();
            $ex->fields = [
                'estimation' => 'Неправильный формат оценки'
            ];
            throw $ex;
        }
    }

    protected static function checkTaskId(string $id, array $parts = null, string $fieldName = null)
    {
        $parts = $parts ?? explode('-', $id);
        $fails = false;
        if (
            count($parts) == 0
            || $parts[0] != 'TASK'
            || preg_match('/^(\d)+$/', $parts[1]) == 0
            || !$task = Task::find($parts[1])
        ) {
            $fails = true;
        }
        if ($fails) {
            $ex = new \InvalidArgumentException();
            $ex->fields = [
                $fieldName ?? 'taskId' => 'Неправильный id задачи или задачи с таким id не существует'
            ];
            throw $ex;
        }
        return $task;
    }
}