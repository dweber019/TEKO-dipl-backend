<?php

namespace App\Http\Controllers;

use App\Models\TaskItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskItemController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskItem $taskItem)
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

        $taskItem = tap($taskItem->fill($attributes))->save();

        return $taskItem;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskItem $taskItem)
    {
        $taskItem->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function workIndex(Request $request, TaskItem $taskItem)
    {
        $currentUser = $request->user();

        $work = $taskItem->users()->where('user_id', $currentUser->id)->first();

        return $work;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function workUpdate(Request $request, TaskItem $taskItem)
    {
        $attributes = $request->validate([
          'result' => 'required|string'
        ]);

        $currentUser = $request->user();

        $taskItem->users()->attach($currentUser->id, $attributes);
        $work = $taskItem->users()->where('user_id', $currentUser->id)->first();

        return $work;
    }
}
