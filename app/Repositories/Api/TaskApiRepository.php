<?php

namespace App\Repositories\Api;

use App\Http\Resources\TaskResource;
use App\Jobs\SendTaskNotification;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\RepositoryInterface;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskApiRepository implements RepositoryInterface
{

    private $task;

    public function __construct()
    {
        $this->task = Task::class;
    }

    public function findById($id)
    {
        return $this->task::findOrFail($id);
    }

    public function view($id)
    {
        $theTask = $this->findById($id);

        if (auth()->user()->cannot('update', $theTask)) {
            abort(403, 'You are not author of the task');
        }

        return new TaskResource($theTask);

    }

    public function getAll($request)
    {
        $order = $request->order ?? 'DESC';
        $limit = $request->limit ?? 10;
        $status = $request->status ?? ['InComplete'];


        $allTask = $this->task::
        orderBy('id', $order)
            ->with('author')
            ->whereIn('status', $status)
            ->take($limit)
            ->get();

        return TaskResource::collection($allTask);

    }

    public function getAllForUserId($id)
    {

        $taskList = auth()->user()->tasks;
        return TaskResource::collection($taskList);


    }

    public function create(CreateTaskRequest $request)
    {
        $task = $this->task::create($request->only(['title', 'description']));
        return new TaskResource($task);

    }

    public function markComplete($id)
    {
        $theTask = $this->task::findOrFail($id);

        if (auth()->user()->cannot('update', $theTask)) {
            abort(403, 'You are not author of the task');
        }
        $task = $theTask->update(['status' => 'Complete']);


        return $task;

    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $theTask = $this->task::findOrFail($id);
        Log::info($theTask);

        if ($request->user()->cannot('update', $theTask)) {
            abort(403, 'You are not author of the task');
        }
        $theTask->update($request->validated());
        return new TaskResource($theTask);

    }

    public function delete($theTask)
    {
        return $theTask->delete();
    }

}
