<?php

namespace App\Http\Controllers\Api\V1;

use App\Factories\TaskFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{

    private $task;

    public function __construct(TaskFactory $task)
    {
        $this->task = $task::createApi();
    }

    public function index(Request $request)

    {
        try {
            $this->authorize('viewAny', Task::class);
            return $this->task->getAll($request);
        } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
            return $this->task->getAllForUserId($request);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTaskRequest $request)
    {
        $response = $this->task->create($request);
        return $this->respondOk($response, Response::HTTP_CREATED, 'Task created successfully!',);
    }

    public function view($id)
    {
        $response = $this->task->view($id);
        $this->respondOk($response, Response::HTTP_OK);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTaskRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $response = $this->task->update($request, $id);
        return $this->respondOk($response, Response::HTTP_CREATED, 'Task updated successfully!',);
    }

    public function markAsComplete($id)
    {
        $response = $this->task->markComplete($id);
        $this->respondOk($response, Response::HTTP_OK, 'Task has been marked as completed successfully!',);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $theTask = $this->task->findById($id);
        $this->authorize('delete', $theTask);
        $deleteTask = $this->task->delete($theTask);
        return $this->respondOk($deleteTask, Response::HTTP_OK, 'Task has been deleted successfully!',);
    }
}
