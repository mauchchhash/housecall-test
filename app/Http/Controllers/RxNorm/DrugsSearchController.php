<?php

namespace App\Http\Controllers\RxNorm;

use App\Http\Controllers\Controller;
use App\Http\Requests\RxNorm\DrugsSearchRequest;
use App\Services\RxNormApiService;

class DrugsSearchController extends Controller
{
    /**
     * Delete a prescribed drug of a user
     *
     * @param  string $drug_name
     * @return array $data
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
