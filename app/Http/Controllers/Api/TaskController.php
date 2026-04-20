<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with(['project', 'assignee'])
            ->paginate(10);

        return TaskResource::collection($tasks);
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);
        $task = Task::create($request->validated());

        return new TaskResource($task);


    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task )
    {
        return $task;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'=> 'sometimes|string|max:255',
            'description' =>'sometimes|string',
            'status'=> 'sometimes|in:todo,in_progress,done',
            'assigned_to'=>'exists:users,id'
        ]);

        $task->update($validated);

        return response()->json($task);

    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete',$task);

        $task->delete();
        return response()->json(['message' => 'Task deleted']);
        
    }


    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('updateStatus', $task); // passes task to TaskPolicy@updateStatus

        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update(['status' => $request->status]);
        return new TaskResource($task);
    }


}