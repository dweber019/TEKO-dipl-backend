<?php

use Illuminate\Database\Seeder;

class SmallSchool extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Create Some Users
         */
        $admin = tap(new App\Models\User(
            [
              'firstname' => 'David',
              'lastname' => 'Weber',
              'invite_token' => 'ABC',
              'invite_email' => 'david.weber.schenker@gmail.com',
              'type' => 'admin',
            ]
        ))->save();

        $students = factory(App\Models\User::class, 20)->create();
        $teachers = factory(App\Models\User::class, 'teacher', 3)->create();

        /**
         * Create some Groups
         */
        $group1A = factory(App\Models\Group::class)->create([ 'name' => '1A' ]);
        $group1B = factory(App\Models\Group::class)->create([ 'name' => '1B' ]);
        $group1C = factory(App\Models\Group::class)->create([ 'name' => '1C' ]);

        $studentsIds = $students->pluck('id');
        $studentsPaged = $studentsIds->chunk(7);

        $group1A->users()->sync($studentsPaged[0]);
        $group1B->users()->sync($studentsPaged[1]);
        $group1C->users()->sync($studentsPaged[2]);

        /**
         * Create some Chats
         */
        factory(App\Models\Chat::class)->create([ 'sender_id' => $admin->id, 'receiver_id' => $students[0]->id ]);
        factory(App\Models\Chat::class)->create([ 'sender_id' => $students[0]->id, 'receiver_id' => $admin->id ]);
        factory(App\Models\Chat::class)->create([ 'sender_id' => $admin->id, 'receiver_id' => $students[0]->id ]);
        factory(App\Models\Chat::class)->create([ 'sender_id' => $students[0]->id, 'receiver_id' => $admin->id ]);
        factory(App\Models\Chat::class, 10)->create([ 'sender_id' => $admin->id, 'receiver_id' => $students[0]->id ]);
        factory(App\Models\Chat::class)->create([ 'sender_id' => $students[0]->id, 'receiver_id' => $admin->id, 'read' => false ]);

        /**
         * Create some subjects
         */
        $subjectEnglish = factory(App\Models\Subject::class)->create([ 'name' => 'Englisch', 'teacher_id' => $teachers[0]->id ]);
        $subjectFrench = factory(App\Models\Subject::class)->create([ 'name' => 'French', 'teacher_id' => $teachers[1]->id ]);
        $subjectMath = factory(App\Models\Subject::class)->create([ 'name' => 'Mathematics', 'teacher_id' => $teachers[2]->id ]);
        $subjectIt = factory(App\Models\Subject::class)->create([ 'name' => 'IT', 'teacher_id' => $teachers[2]->id ]);

        $subjectEnglish->users()->sync($studentsPaged[0]);
        $subjectFrench->users()->sync($studentsPaged[1]);
        $subjectMath->users()->sync($studentsPaged[2]);
        $subjectIt->users()->sync($studentsPaged[2]);

        $subjectEnglish->userGrades()->attach($studentsPaged[0][0], ['grade' => 3.5]);
        $subjectEnglish->userGrades()->attach($studentsPaged[0][0], ['grade' => 5]);
        $subjectEnglish->userGrades()->attach($studentsPaged[0][0], ['grade' => 3]);

        $subjectEnglish->userGrades()->attach($studentsPaged[0][1], ['grade' => 3.5]);
        $subjectEnglish->userGrades()->attach($studentsPaged[0][1], ['grade' => 5]);
        $subjectEnglish->userGrades()->attach($studentsPaged[0][1], ['grade' => 3]);

        /**
         * Create some lessons for
         *
         * Only users from
         */
        $lessonsEnglish = [];
        array_push($lessonsEnglish, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now(), 'end_date' => \Carbon\Carbon::now()->addHour(), 'subject_id' => $subjectEnglish->id ]));
        array_push($lessonsEnglish, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeek(), 'end_date' => \Carbon\Carbon::now()->addWeek()->addHour(), 'subject_id' => $subjectEnglish->id ]));
        array_push($lessonsEnglish, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeeks(2), 'end_date' => \Carbon\Carbon::now()->addWeeks(2)->addHour(), 'subject_id' => $subjectEnglish->id ]));

        $lessonsFrench = [];
        array_push($lessonsFrench, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now(), 'end_date' => \Carbon\Carbon::now()->addHour(), 'subject_id' => $subjectFrench->id ]));
        array_push($lessonsFrench, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeek(), 'end_date' => \Carbon\Carbon::now()->addWeek()->addHour(), 'subject_id' => $subjectFrench->id ]));
        array_push($lessonsFrench, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeeks(2), 'end_date' => \Carbon\Carbon::now()->addWeeks(2)->addHour(), 'subject_id' => $subjectFrench->id ]));

        $lessonsMath = [];
        array_push($lessonsMath, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now(), 'end_date' => \Carbon\Carbon::now()->addHour(), 'subject_id' => $subjectMath->id ]));
        array_push($lessonsMath, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeek(), 'end_date' => \Carbon\Carbon::now()->addWeek()->addHour(), 'subject_id' => $subjectMath->id ]));
        array_push($lessonsMath, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeeks(2), 'end_date' => \Carbon\Carbon::now()->addWeeks(2)->addHour(), 'subject_id' => $subjectMath->id ]));

        $lessonsIt = [];
        array_push($lessonsIt, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addHour(), 'end_date' => \Carbon\Carbon::now()->addHours(2), 'subject_id' => $subjectIt->id ]));
        array_push($lessonsIt, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeek()->addHour(), 'end_date' => \Carbon\Carbon::now()->addWeek()->addHours(2), 'subject_id' => $subjectIt->id ]));
        array_push($lessonsIt, factory(App\Models\Lesson::class)->create([ 'start_date' => \Carbon\Carbon::now()->addWeeks(2)->addHour(), 'end_date' => \Carbon\Carbon::now()->addWeeks(2)->addHours(2), 'subject_id' => $subjectIt->id ]));

        /**
         * Create some Tasks
         */
        $tasksTranslate = [];
        $tasksVerbs = [];
        foreach ($lessonsEnglish as &$lesson) {
            array_push($tasksTranslate, factory(App\Models\Task::class)->create(
              [ 'name' => 'Übersetzten', 'description' => 'Bitee übersetzte die Liste', 'due_date' => $lesson->start_date, 'lesson_id' => $lesson->id ]
            ));
            array_push($tasksVerbs, factory(App\Models\Task::class)->create(
              [ 'name' => 'Verbs', 'description' => 'Lerne folgende Verben zu konugieren: do, read, run, laught', 'due_date' => $lesson->start_date, 'lesson_id' => $lesson->id ]
            ));
        }

        foreach ($tasksVerbs as &$task) {
            $task->users()->syncWithoutDetaching([ $studentsPaged[0][0] => [ 'done' => true ] ]);
        }

        /**
         * Create some Task Items
         */
        $taskItems = [];
        foreach ($tasksTranslate as &$task) {
            $taskItemDo = factory(App\Models\TaskItem::class)->create(
              [ 'title' => 'do', 'description' => null, 'question_type' => 'input', 'question' => null, 'order' => 3, 'task_id' => $task->id ]
            );
            $taskItemRead = factory(App\Models\TaskItem::class)->create(
              [ 'title' => 'read', 'description' => null, 'question_type' => 'input', 'question' => null, 'order' => 2, 'task_id' => $task->id ]
            );
            $taskItemRun = factory(App\Models\TaskItem::class)->create(
              [ 'title' => 'run', 'description' => null, 'question_type' => 'input', 'question' => null, 'order' => 1, 'task_id' => $task->id ]
            );

            if ($task->id === $tasksTranslate[0]->id) {
                array_push($taskItems, $taskItemDo);
                array_push($taskItems, $taskItemRead);
                array_push($taskItems, $taskItemRun);
            }
        }

        foreach ($taskItems as &$taskItem) {
            $taskItem->users()->attach($studentsPaged[0][0], [ 'result' => (function() use ($taskItem) {
                switch ($taskItem->title) {
                    case 'Do':
                        return 'machen';
                    case 'read':
                        return 'lesen';
                    case 'run':
                        return 'sprinten';
                    default:
                        return 'keine Ahnung';
                }
            })() ]);
        }

        /**
         * Create some notes
         */
        $notesLesson = factory(App\Models\Note::class, 10)->make([ 'user_id' => $studentsPaged[0][0] ]);
        $notesTask = factory(App\Models\Note::class, 10)->make([ 'user_id' => $studentsPaged[0][0] ]);

        foreach ($notesLesson as &$note) {
            $lessonsEnglish[0]->notes()->save($note);
        }

        foreach ($notesTask as &$note) {
            $tasksTranslate[0]->notes()->save($note);
        }

        /**
         * Create some comments
         */
        $commentsLesson = factory(App\Models\Comment::class, 10)->make([ 'user_id' => $studentsPaged[0][0] ]);
        $commentsTask = factory(App\Models\Comment::class, 10)->make([ 'user_id' => $studentsPaged[0][0] ]);

        foreach ($commentsLesson as &$comment) {
            $lessonsEnglish[0]->comments()->save($comment);
        }

        foreach ($commentsTask as &$comment) {
            $tasksTranslate[0]->comments()->save($comment);
        }

    }
}
