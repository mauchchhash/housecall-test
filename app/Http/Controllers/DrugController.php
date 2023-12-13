<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DrugDeleteRequest;
use App\Http\Requests\DrugStoreRequest;
use App\Models\User;
use App\Services\DrugService;

class DrugController extends Controller
{
    /**
     * Get prescribed drugs for a user
     *
     * @param  [User] user
     * @return [Drug[]] data
     */
    public function index(User $user)
    {
        try {
            $drugService = new DrugService($user);
            $drugs = $drugService->getPrescribedDrugsOfUser();
            return response()->json(['data' => $drugs], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a prescribed drug for a user
     *
     * @param  [User] user
     * @param  [string] rxcui
     * @return [Drug] data
     */
    public function store(User $user, DrugStoreRequest $request)
    {
        try {
            $rxcui = $request->input('rxcui');
            $drugService = new DrugService($user);
            $createdDrug = $drugService->addNewDrugToUser($rxcui);
            return response()->json(['data' => $createdDrug], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Delete a prescribed drug of a user
     *
     * @param  [User] user
     * @param  [string] rxcui
     * @return [string] message
     */
    public function delete(User $user, $rxcui)
    {
        try {
            $drugService = new DrugService($user);
            $createdDrug = $drugService->deleteDrug($rxcui);
            return response()->json(['message' => 'The drug is deleted'], 204);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
