<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskCreated;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function creating(Task $task)
    {
        //If mutator not working then this will ensure author id to be inserted
        $task->author_id = Auth::id();
    }

    public function created(Task $task)
    {
        //We can here set to send notification to admin also
        $task->author->notify(new TaskCreated($task));

    }

    public function updated(Task $task)
    {
        // You can customize this based on your needs
        if ($task->status=='Complete') {
            $task->author->notify(new TaskCompleted($task));

        }
    }
}
