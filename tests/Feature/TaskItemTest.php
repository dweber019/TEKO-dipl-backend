<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskItemTest extends TestCase
{
    private $taskitemPost = [
      'title' => 'New Task Item',
      'description' => 'Task Item description',
      'questionType' => 'input',
      'question' => null,
      'order' => 1,
    ];

    public function test_update_task_item()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->putJson('/api/taskItems/1', $this->taskitemPost);

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'title' => $this->taskitemPost['title'] ]);
    }

    public function test_update_task_item_as_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->putJson('/api/taskItems/1', $this->taskitemPost);

        $response->assertStatus(403);
    }

    public function test_update_task_item_as_unallowed_teacher()
    {
        $actingUser = User::find(23);

        $response = $this->actingAs($actingUser)->putJson('/api/taskItems/1', $this->taskitemPost);

        $response->assertStatus(403);
    }

    public function test_delete_task_item()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->deleteJson('/api/taskItems/1');

        $response->assertSuccessful();
    }

    public function test_update_work_of_task_item()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->putJson('/api/taskItems/1/work', [ 'result' => 'My answer!' ]);

        $response->assertSuccessful();
    }

    public function test_upload_file_to_task_item()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->post('/api/taskItems/4/file', [
          'file' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        $response->assertSuccessful();

        $filePath = 'taskitems/avatar-' . Carbon::now()->timestamp . '.jpg';

        $this->assertTrue(Storage::cloud()->exists($filePath));

        /**
         * Clean Up
         */
        Storage::deleteDirectory('taskitems');
    }

    public function test_read_file_of_task_item()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->post('/api/taskItems/4/file', [
          'file' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        $response->assertSuccessful();

        $fileName = 'avatar-' . Carbon::now()->timestamp . '.jpg';

        $response = $this->actingAs($actingUser)->get('/api/taskItems/4/file');

        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        /**
         * Clean Up
         */
        Storage::deleteDirectory('taskitems');
    }
}
