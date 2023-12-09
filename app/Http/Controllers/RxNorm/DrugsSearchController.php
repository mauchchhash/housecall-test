<?php

namespace App\Http\Controllers\RxNorm;

use App\Http\Controllers\Controller;
use App\Http\Requests\RxNorm\DrugsSearchRequest;
use App\Services\RxNormApiService;

class DrugsSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DrugsSearchRequest $request)
    {
        try {
            $drugName = $request->input('drug_name');
            $rxNormApiService = new RxNormApiService();
            $results = $rxNormApiService->searchDrugs($drugName);
            return response()->json(['data' => $results]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
