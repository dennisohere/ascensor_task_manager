<?php

namespace App\Services;

use App\Dtos\TaskDto;
use App\Events\TaskWasCreated;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getUserTasks(User $user, ?int $paginate = null): Collection|LengthAwarePaginator
    {
        $query = Task::query()->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->orderBy('completed');

        if($paginate){
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public function saveTask(TaskDto $payload, ?Task $task = null): Task
    {
        $is_edit = !($task == null);

        if(!$task){
            $task = new Task();
        }
        $task->name = $payload->name;
        $task->description = $payload->description;
        $task->completed = $payload->completed;
        $task->due_date = $payload->due_date;

        if(!!$payload->category_id){
            $task->category_id = $payload->category_id;
        } else {
            $new_category = $this->initTaskCategoryByName($payload->new_category_name);
            $task->category_id = $new_category->id;
        }

        $task->user_id = $payload->user_id;

        $task->save();

        if(!$is_edit){
            event(new TaskWasCreated($task));
        }

        return $task;
    }


    public function initTaskCategoryByName(string $category_name): TaskCategory
    {
        return TaskCategory::query()->firstOrCreate(['name' => $category_name]);
    }

}
