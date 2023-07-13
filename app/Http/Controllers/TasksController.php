<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TasksResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TasksResource::collection(
            Task::where('user_id', Auth::user()->id)->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());
        $task = Task::create(['user_id' => Auth::user()->id, 'name' => $request->name, 'description' => $request->description, 'priority' => $request->priority]);

        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show($taskId)
    {
        $task = Task::find($taskId);
        if (!$task) {
            return
                $this->error('', 'Task Not found', 404);
        }

        return $this->isNotAuthorised($task) ?: new TasksResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (Auth::user()->id !== $task->user->id) {
            return $this->error('', 'You are not authorised to make this request', 403);
        }
        $task->update($request->all());
        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {

        return $this->isNotAuthorised($task) ? $this->isNotAuthorised($task) :  $task->delete();
    }

    private function isNotAuthorised($task)
    {
        if (Auth::user()->id !== $task->user->id) {
            return $this->error('', 'You are not authorised to make this request', 403);
        }
        return;
    }
}
