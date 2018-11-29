<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (
            isset($exception->fields)
            || isset($exception->global)
        ) {
            $errors = [];

            if (isset($exception->fields)) {
                $errors['Fields'] = $exception->fields;
            }
            if (isset($exception->global)) {
                $errors['Global'] = $exception->global;
            }
            return response(
                [
                    'Errors' => $errors
                ],
                422
            );
        }
        return parent::render($request, $exception);
    }
}
