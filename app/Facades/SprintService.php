<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 29.11.18
 * Time: 11:59
 */

namespace App\Facades;

use App\Sprint;
use Illuminate\Support\Facades\Facade;

class SprintService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sprint';
    }

    protected static function checkSprintId(string $id, string $field = null)
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