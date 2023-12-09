<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RxNormApiService
{
    public function searchDrugs(string $drugName): array
    {
        $responseData = Http::RxNormApi()->get('/drugs.json', [
            'name' => $drugName
        ])->json();
        $conceptGroup = $responseData['drugGroup']['conceptGroup'];
        $sbdData = collect($conceptGroup)->filter(function ($cg) {
            return $cg['tty'] === 'SBD';
        })->first();
        $topFiveDrugs = array_slice($sbdData['conceptProperties'], 0, 5);
        foreach ($topFiveDrugs as &$d) {
            $history = $this->getRxcuiHistoryStatus($d['rxcui']);
            // $d['history'] = $history;
            $ingredientAndStrength = $history['rxcuiStatusHistory']['definitionalFeatures']['ingredientAndStrength'];
            $d['baseName'] = $ingredientAndStrength;
            $doseFormGroupConcept = $history['rxcuiStatusHistory']['definitionalFeatures']['doseFormGroupConcept'];
            $d['doseFormGroupName'] = array_map(function ($i) {
                return $i['doseFormGroupName'];
            }, $doseFormGroupConcept);
        }
        // dd($topFiveDrugs);
        return $topFiveDrugs;
    }

    public function getRxcuiHistoryStatus($rxcui)
    {
        $responseData = Http::RxNormApi()->get("/rxcui/{$rxcui}/historystatus.json")->json();
        return $responseData;
    }
}
