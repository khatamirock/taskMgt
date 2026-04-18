<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Session\Store;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Project::class);
        $projects = Project::all();
        return response()->json($projects);

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
    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create',[Project::class]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => $request->user()->id,
        ]);

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $project = Project::findOrFail($id);

        $this->authorize('view',$project);

        return response()->json($project);



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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete',Project::class);
        $project->delete();
        return response()->json(['message' => 'Project deleted']);

    }


    public function assignMember(Request $request, Project $project)
    {

        $this->authorize('assignMember',$project);

        $request->validate([
            'user_id'=>'required|exists:users,id',
        ]);

        $project->members()->syncWithoutDetaching($request->user_id);

        return response()->json(['message' => 'Project members assigned']);


    }






}
