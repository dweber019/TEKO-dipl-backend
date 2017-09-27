<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

\Illuminate\Support\Facades\Auth::loginUsingId(2);


/*
 * User related routes
 */
Route::prefix('users')->group(function () {
    Route::get('{user}/groups', 'UserController@groupsIndex');

    Route::get('{user}/subjects', 'UserController@subjectsIndex');

    Route::get('{user}/notifications', 'UserController@notificationsIndex');

    Route::get('{user}/grades', 'UserController@gradesIndex');

    Route::get('{user}/chats', 'UserController@chatsIndex');
    Route::post('{user}/chats', 'UserController@chatsStore');
    Route::delete('{user}/chats/{user2}', 'UserController@chatsDestroy');
});
Route::resource('users', 'UserController', ['except' => [
  'create', 'edit'
]]);

/*
 * Feed & Agenda
 */
Route::get('agenda', 'UserController@agendaIndex');
Route::get('feed/{token}', 'UserController@feedIndex');

/*
 * Group related routes
 */
Route::prefix('groups')->group(function () {
    Route::get('{group}/users', 'GroupController@usersIndex');
    Route::post('{group}/users/{user}', 'GroupController@usersStore');
    Route::delete('{group}/users/{user}', 'GroupController@usersDestroy');

    Route::get('{group}', 'GroupController@show');
});
Route::resource('groups', 'GroupController', ['except' => [
  'create', 'edit'
]]);

/*
 * Subject related routes
 */
Route::prefix('subjects')->group(function () {
    Route::get('{subject}/lessons', 'SubjectController@lessonsIndex');
    Route::post('{subject}/lessons', 'SubjectController@lessonsStore');

    Route::get('{subject}/grades', 'SubjectController@gradesIndex');
    Route::post('{subject}/grades/{user}', 'SubjectController@gradesStore');
    Route::delete('{subject}/grades/{user}', 'SubjectController@gradesDestroy');

    Route::get('{subject}/users', 'SubjectController@usersIndex');
    Route::post('{subject}/users/{user}', 'SubjectController@usersStore');
    Route::delete('{subject}/users/{user}', 'SubjectController@usersDestroy');
});
Route::resource('subjects', 'SubjectController', ['except' => [
  'create', 'edit'
]]);

/*
 * Lessons related routes
 */
Route::prefix('lessons')->group(function () {
    Route::get('{lesson}/tasks', 'LessonController@tasksIndex');
    Route::post('{lesson}/tasks', 'LessonController@tasksStore');

    Route::get('{lesson}/note', 'LessonController@noteIndex');
    Route::put('{lesson}/note', 'LessonController@noteUpdate');

    Route::get('{lesson}/comments', 'LessonController@commentsIndex');
    Route::post('{lesson}/comments', 'LessonController@commentsStore');
});
Route::resource('lessons', 'LessonController', ['except' => [
  'create', 'edit', 'store', 'index'
]]);

/*
 * Tasks related routes
 */
Route::prefix('tasks')->group(function () {
    Route::get('{task}/taskitems', 'TaskController@taskItemsIndex');
    Route::post('{task}/taskitems', 'TaskController@taskItemsStore');

    Route::get('{task}/note', 'TaskController@noteIndex');
    Route::put('{task}/note', 'TaskController@noteUpdate');

    Route::get('{task}/comments', 'TaskController@commentsIndex');
    Route::post('{task}/comments', 'TaskController@commentsStore');

    Route::put('{task}/done', 'TaskController@doneUpdate');
});
Route::resource('tasks', 'TaskController', ['except' => [
  'create', 'edit', 'store', 'index'
]]);

/*
 * Task Items related routes
 */
Route::prefix('taskitems')->group(function () {
    Route::get('{taskItem}/work', 'TaskItemController@workIndex');
    Route::put('{taskItem}/work', 'TaskItemController@workUpdate');
});
Route::resource('taskitems', 'TaskItemController', ['except' => [
  'create', 'edit', 'store', 'index', 'show'
]]);

/*
 * Comments related routes
 */
Route::resource('comments', 'CommentsController', ['except' => [
  'create', 'edit', 'store', 'index', 'show'
]]);