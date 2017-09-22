<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $attributes = $request->validate([
          'name' => 'required|string',
          'description' => 'string|nullable',
          'due_date' => 'required|date|after:now',
        ]);

        $task = tap($task->fill($attributes))->save();

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $lesson
     * @return \Illuminate\Http\Response
     */
    public function taskItemsIndex(Task $lesson)
    {
        return $lesson->taskItems()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function taskItemsStore(Request $request, Task $task)
    {
        $attributes = $request->validate([
          'title' => 'required|string',
          'description' => 'string|nullable',
          'question_type' => [
            'required',
            Rule::in(['toggle', 'select', 'file', 'input', 'text']),
          ],
          'question' => 'string|nullable',
          'order' => 'integer',
        ]);

        $attributes['task_id'] = $task->id;

        $taskItem = tap(new TaskItem($attributes))->save();

        return $taskItem;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function noteIndex(Task $task)
    {
        // TODO: Need user context
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function noteUpdate(Request $request, Task $task)
    {
        // TODO: Need user context
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function commentsIndex(Task $task)
    {
        // TODO: Need user context
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function commentsStore(Request $request, Task $task)
    {
        // TODO: Need user context
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function doneUpdate(Request $request, Task $task)
    {
        // TODO: Need user context
    }
}
