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
     * @urlParam user_id integer required The id of the user. Example: 1
     * @response { "data": [ { "id": 2, "user_id": 1, "rxcui": "105898", "name": "naproxen 250 MG Oral Tablet [Naprosyn]", "base_names": [ { "baseRxcui": "7258", "baseName": "naproxen", "bossRxcui": "7258", "bossName": "naproxen", "activeIngredientRxcui": "7258", "activeIngredientName": "naproxen", "moietyRxcui": "7258", "moietyName": "naproxen", "numeratorValue": "250", "numeratorUnit": "MG", "denominatorValue": "1", "denominatorUnit": "EACH" } ], "dose_form_group_names": [ "Oral Product", "Pill" ], "created_at": "2023-12-13T18:22:45.000000Z", "updated_at": "2023-12-13T18:22:45.000000Z" } ] }
     * @group User Drug Management
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
     * @urlParam user_id integer required The id of the user. Example: 1
     * @bodyParam rxcui string required The rxcui of the drug. Example: 105898
     * @response { "data": { "rxcui": "105898", "name": "naproxen 250 MG Oral Tablet [Naprosyn]", "base_names": [ { "baseRxcui": "7258", "baseName": "naproxen", "bossRxcui": "7258", "bossName": "naproxen", "activeIngredientRxcui": "7258", "activeIngredientName": "naproxen", "moietyRxcui": "7258", "moietyName": "naproxen", "numeratorValue": "250", "numeratorUnit": "MG", "denominatorValue": "1", "denominatorUnit": "EACH" } ], "dose_form_group_names": [ "Oral Product", "Pill" ], "user_id": 1, "updated_at": "2023-12-13T18:22:45.000000Z", "created_at": "2023-12-13T18:22:45.000000Z", "id": 2 } }
     * @group User Drug Management
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
     * @urlParam user_id integer required The id of the user. Example: 1
     * @urlParam rxcui string required The 'rxcui' of the drug to be deleted. Example: 105898
     * @response { "message": "The drug is deleted" }
     * @group User Drug Management
     */
    public function delete(User $user, $rxcui)
    {
        try {
            $drugService = new DrugService($user);
            $createdDrug = $drugService->deleteDrug($rxcui);
            return response()->json(['message' => 'The drug is deleted'], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
