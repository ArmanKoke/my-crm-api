<?php

namespace App\Http\Controllers;

use App\Http\Resources\DealResource;
use App\Models\Deal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DealController extends Controller
{
    /**
     * @return Deal[]|Collection
     */
    public function index()
    {
        return Deal::all();
    }

    /**
     * @param Request $request
     * @return DealResource
     */
    public function store(Request $request): DealResource
    {
        $deal = new Deal();
        $deal->company_name = $request->company_name;
        $deal->description = $request->description;
        $deal->notes = $request->notes;
        $deal->status_id = $request->status_id;
        $deal->manager_id = Auth::user()->getAuthIdentifier();
        $deal->save();

        return new DealResource($deal);
    }

    /**
     * @param Deal $deal
     * @return DealResource
     */
    public function show(Deal $deal): DealResource
    {
        return new DealResource($deal->load('manager','status'));
    }

    /**
     * @param Request $request
     * @param Deal $deal
     * @return Deal
     */
    public function update(Request $request, Deal $deal)
    {
        $deal->fill($request->all());
        $deal->save();

        $deal->user()->associate($request->manager_id);

        return $deal->load('manager','status');
    }

    /**
     * @param Deal $deal
     * @return Response
     * @throws Exception
     */
    public function destroy(Deal $deal): Response
    {
        $deal->manager()->dissociate();
        $deal->status()->dissociate();
        $deal->delete();

        return response(null, 204);
    }
}
