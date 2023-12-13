<?php

namespace App\Http\Controllers\RxNorm;

use App\Http\Controllers\Controller;
use App\Http\Requests\RxNorm\DrugsSearchRequest;
use App\Services\RxNormApiService;

class DrugsSearchController extends Controller
{
    /**
     * Search drug information via a drug name
     *
     * @unauthenticated
     * @urlParam drug_name string required The drug name to search for. Example: Naproxen
     * @group Public APIs
     */
    public function __invoke(string $drugName)
    {
        try {
            $rxNormApiService = new RxNormApiService();
            $results = $rxNormApiService->searchDrugs($drugName);
            return response()->json(['data' => $results]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
