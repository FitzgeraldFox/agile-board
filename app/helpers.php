<?php

use App\Sprint;
use App\Task;
use Illuminate\Support\Facades\Validator;

if (! function_exists('check_estimation')) {
    function check_estimation(string $est)
    {
        return preg_match("/^(\d[hdwmy])+$/", $est);
    }
}
if (! function_exists('check_task_id')) {
    function check_task_id(string $id, array $parts = null, string $fieldName = null)
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
if (! function_exists('check_sprint_id')) {
    function check_sprint_id(string $id, string $field = null)
    {
        $parts = explode('-', $id);
        $fails = false;
        if (
            count($parts) == 0
            || !(int)$parts[0]
            || !(int)$parts[1]
            || !$sprint = Sprint::find($id)
        ) {
            $fails = true;
        }
        if ($fails) {
            $ex = new \InvalidArgumentException();
            $ex->fields = [
                $field ?? 'sprintId' => 'Неправильный id спринта или спринта с таким id не существует'
            ];
            throw $ex;
        }
        return $sprint;
    }
}
if (! function_exists('validate_args')) {
    function validate_args(array $args, array $rules)
    {
        $validator = Validator::make($args, $rules);
        if ($validator->fails()) {
            $ex = new \InvalidArgumentException();
            $ex->fields = $validator->errors()->getMessages();
            throw $ex;
        }
    }
}