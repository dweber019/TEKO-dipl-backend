<?php

namespace App\Http\Controllers;

use App\Helpers\QuestionTypes;
use App\Helpers\StorageUrl;
use App\Models\TaskItem;
use Auth0\Login\Auth0Service;
use Auth0\Login\facade\Auth0;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
     * Upload a file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function workFile(Request $request, TaskItem $taskItem)
    {
        $currentUser = Auth::user();

        $this->authorize('view', $taskItem);

        if ($taskItem->question_type !== \App\Helpers\QuestionTypes::FILE) {
            return response('', Response::HTTP_BAD_REQUEST);
        }

        $file = $request->file('file');
        $filename = explode('.', $file->getClientOriginalName());
        array_pop($filename);
        $filename = implode('', $filename);

        $newFile = $filename . '-' . Carbon::now()->timestamp . '.' . $file->getClientOriginalExtension();

        $request->file('file')->storeAs('taskitems', $newFile, 's3');

        $taskItem->users()->syncWithoutDetaching([ $currentUser->id => [ 'result' => $newFile . ';' . $file->getMimeType() ] ]);

        return response('', Response::HTTP_OK);
    }

    /**
     * Upload a file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function workGetFile(Request $request, TaskItem $taskItem)
    {
        $this->authorize('view', $taskItem);

        if ($taskItem->question_type !== \App\Helpers\QuestionTypes::FILE) {
            return response('', Response::HTTP_BAD_REQUEST);
        }

        $result = $taskItem->users()->where('user_id', '=', $request->user()->id)->first();
        $resultString= $result->pivot->result;

        $exploseFile = explode(';', $resultString);

        $file = Storage::disk('s3')->read('taskitems/' . $exploseFile[0]);

        return response($file, 200, [
          'Content-Type' => $exploseFile[1],
          'Content-Disposition' => 'attachment; filename="' . $exploseFile[0] . '"',
        ]);
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
          'result' => 'required'
        ]);

        $currentUser = $request->user();

        $taskItem->users()->syncWithoutDetaching([ $currentUser->id => $attributes ]);

        return response('', Response::HTTP_OK);
    }
}
