<?php

use Illuminate\Support\Facades\Validator;

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