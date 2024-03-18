<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateInvestigatorRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateInvestigatorRequest;
use App\Models\Investigator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Knuckles\Scribe\Attributes\Group;

#[Group("Investigators")]
class InvestigatorsController
{
    /**
     * List all Investigators
     *
     * Retrieve a paginated list of all investigators & the projects they belong to.
     */
    public function index(): LengthAwarePaginator
    {
        return Investigator::with('projects.bioSamples.samples.specimens')->paginate(50);
    }

    /**
     * Search Investigators
     *
     * Retrieve a paginated list of investigators based on a search term provided.
     */
    public function search(SearchRequest $request)
    {
        return tap(Investigator::getGlobalSearchResultsQuery($request->get('term'))->dd()->paginate(1), function ($paginator) {
            return $paginator->getCollection()->transform(fn($search) => Investigator::find($search->model_id)->makeHidden('name'));
        });
    }

    /**
     * Retrieve an Investigator
     *
     * Retrieve the details of an existing investigator.
     */
    public function show(Investigator $investigator): Investigator
    {
        return $investigator->load('projects.bioSamples.samples.specimens');
    }

    /**
     * Create an Investigator
     *
     * Create a new Investigator.
     */
    public function store(CreateInvestigatorRequest $request): JsonResponse
    {
        $investigator = Investigator::create($request->validated());

        return response()->json($investigator, 201);
    }

    /**
     * Update an Investigator
     *
     * Update the details of an existing investigator.
     */
    public function update(UpdateInvestigatorRequest $request, Investigator $investigator): ?Investigator
    {
        $investigator->update($request->validated());

        return $investigator->fresh()->makeHidden('name');
    }
}
