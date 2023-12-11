<?php

namespace App\Services;

use App\Models\Drug;
use App\Models\User;

class DrugService
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getPrescribedDrugsOfUser()
    {
        return $this->user->drugs;
    }

    public function addNewDrugToUser(string $rxcui)
    {
        try {
            $rxNormApiService =  new RxNormApiService();
            $history = $rxNormApiService->getRxcuiHistoryStatus($rxcui);
            if ($history === null) {
                throw new \Exception('rxcui not valid');
            }
            $baseNames = $rxNormApiService->getBaseNamesFromHistory($history);
            $doseFormGroupNames = $rxNormApiService->getDoseFormGroupNamesFromHistory($history);

            $createdDrug = $this->user->drugs()->create([
                'rxcui' => $rxcui,
                'name' => $history['rxcuiStatusHistory']['attributes']['name'],
                'base_names' => $baseNames,
                'dose_form_group_names' => $doseFormGroupNames
            ]);
            return $createdDrug;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteDrug(string $rxcui)
    {
        try {
            $drug = Drug::where('rxcui', $rxcui)->first();
            if ($drug === null) {
                throw new \Exception('The Rxcui is not valid');
            }
            $drug->delete();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
