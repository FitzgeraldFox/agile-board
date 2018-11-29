<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/api/tasks', 'TaskController@create');
Route::post('/api/tasks/close', 'TaskController@close');
Route::post('/api/estimate/task', 'TaskController@setEstimate');
Route::post('/api/sprints', 'SprintController@create');
Route::post('/api/sprints/add-task', 'SprintController@addTask');
Route::post('/api/sprints/start', 'SprintController@start');