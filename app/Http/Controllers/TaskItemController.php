<?php

namespace App\Http\Controllers;

use App\Helpers\QuestionTypes;
use App\Models\TaskItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\TaskItem as TaskItemResource;
use App\Http\Resources\Work as WorkResource;

class TaskItemController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(Request $request, TaskItem $taskItem)
    {
        $this->authorize('update', $taskItem);

        $attributes = $request->validate([
          'title' => 'required|string',
          'description' => 'string|nullable',
          'question_type' => [
            'required',
            Rule::in(QuestionTypes::toArray()),
          ],
          'question' => 'string|nullable',
          'order' => 'integer',
        ]);

        $taskItem = tap($taskItem->fill($attributes))->save();

        return new TaskItemResource($taskItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskItem $taskItem)
    {
        $this->authorize('delete', $taskItem);

        $taskItem->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function workIndex(TaskItem $taskItem)
    {
        $this->authorize('view', $taskItem);

        $currentUser = Auth::user();

        $work = $taskItem->users()->where('user_id', $currentUser->id)->first();

        return new WorkResource($work);
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
        $this->authorize('view', $taskItem);

        $attributes = $request->validate([
          'result' => 'required|string'
        ]);

        $currentUser = $request->user();

        $taskItem->users()->attach($currentUser->id, $attributes);

        return redirect('api/taskitems/' . $taskItem->id . '/work');
    }
}
