<?php

namespace App\Http\Controllers\Api;

use App\Dtos\TaskDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class TaskController extends Controller
{
    public function __construct(public TaskService $taskService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = $this->taskService->getUserTasks($user);

        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'due_date' => 'nullable|date',
            'completed' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'new_category_name' => 'required_without:category_id',
        ]);

        $payload = TaskDto::fromRequest($request);

        $task = $this->taskService->saveTask($payload);

        return TaskResource::make($task);
    }

    public function show(Task $task)
    {
        return TaskResource::make($task);
    }

    public function update(Request $request, Task $task) {
        Gate::authorize('update', $task);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'due_date' => 'nullable|date',
            'completed' => 'nullable|boolean',
            'category_id' => 'sometimes|exists:categories,id',
            'new_category_name' => 'required_without:category_id',
        ]);

        $payload = TaskDto::fromRequest($request);

        $task = $this->taskService->saveTask($payload, $task);

        return TaskResource::make($task);

    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();
    }
}
