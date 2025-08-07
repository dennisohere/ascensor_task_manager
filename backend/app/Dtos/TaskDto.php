<?php

namespace App\Dtos;

use Illuminate\Http\Request;

class TaskDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string     $name,
                                public string     $description,
                                public string     $user_id,
                                public ?\DateTime $due_date,
                                public ?bool      $completed,
                                public ?string    $category_id,
                                public ?string    $new_category_name)
    {
        //
    }


    public static function fromRequest(Request $request): TaskDto
    {
        return new self(
            name: $request->name,
            description: $request->description,
            user_id: $request->user()->id,
            due_date: !empty($request->due_date) ? new \DateTime($request->due_date) : null,
            completed: $request->completed,
            category_id: $request->category_id,
            new_category_name: $request->new_category_name,
        );
    }
}
