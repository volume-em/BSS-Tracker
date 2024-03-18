<?php

namespace App\Http\Controllers\Api;

use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;

#[Group("Projects")]
class ProjectsController
{
    /**
     * List all Projects
     *
     * Retrieve a paginated list of all projects & the bio samples associated with them.
     */
    public function index()
    {
        return Project::with('bioSamples.samples.specimens')->paginate(50);
    }

    /**
     * Search Projects
     *
     * Retrieve a paginated list of projects based on a search term provided.
     */
    public function search(SearchRequest $request)
    {
        return tap(Project::getGlobalSearchResultsQuery($request->get('term'))->dd()->paginate(1), function ($paginator) {
            return $paginator->getCollection()->transform(fn($search) => Project::find($search->model_id)->makeHidden('name'));
        });
    }

    /**
     * Retrieve a Project
     *
     * Retrieve the details of an existing project.
     */
    public function show(Project $project)
    {
        return $project->load('bioSamples.samples.specimens');
    }

    /**
     * Create a Project
     *
     * Create a new project.
     */
    public function store(CreateProjectRequest $request): JsonResponse
    {
        $createProject = new CreateProject();

        $project = Project::create($createProject->mutateFormDataBeforeCreate($request->validated()));

        return response()->json($project, 201);
    }
}
