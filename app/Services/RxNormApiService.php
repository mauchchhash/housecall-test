<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;

class RxNormApiService
{
    /**
     * Search the drug name from RxNormApi and return top 5 result
     *
     * @param  [string] $drugName
     * @return [array] $topFiveDrugs
     */
    public function searchDrugs(string $drugName): array
    {
        try {
            if (cache($drugName)) {
                $topFiveDrugs = cache($drugName);
            } else {
                $responseData = Http::RxNormApi()->get('/drugs.json', [
                    'name' => $drugName
                ])->json();

                if (isset($responseData['drugGroup']['conceptGroup'])) {
                    $conceptGroup = $responseData['drugGroup']['conceptGroup'];
                    $sbdData = collect($conceptGroup)->filter(function ($cg) {
                        return $cg['tty'] === 'SBD';
                    })->first();
                    $topFiveDrugs = array_slice($sbdData['conceptProperties'], 0, 5);
                    foreach ($topFiveDrugs as &$d) {
                        unset($d['synonym'], $d['tty'], $d['language'], $d['suppress'], $d['umlscui']);

                        $history = $this->getRxcuiHistoryStatus($d['rxcui']);

                        $d['baseNames'] = $this->getBaseNamesFromHistory($history);
                        $d['doseFormGroupNames'] = $this->getDoseFormGroupNamesFromHistory($history);
                    }
                } else {
                    $topFiveDrugs = [];
                }

                cache([$drugName => $topFiveDrugs], now()->addMinutes(60));
            }
            return $topFiveDrugs;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get the history status for a drug by rxcui
     *
     * @param  [string] $rxcui
     * @return [array] $responseData
     */
    public function getRxcuiHistoryStatus($rxcui)
    {
        try {
            $responseData = Http::RxNormApi()->get("/rxcui/{$rxcui}/historystatus.json")->json();
            return $responseData;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get the base names array from history status
     *
     * @param  [array] $history
     * @return [array] $ingredientAndStrength
     */
    public function getBaseNamesFromHistory($history)
    {
        try {
            $ingredientAndStrength = $history['rxcuiStatusHistory']['definitionalFeatures']['ingredientAndStrength'];
            return $ingredientAndStrength;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get the doseFormGroupName array from history status
     *
     * @param  [array] $history
     * @return [array] doseFormGroupName array
     */
    public function getDoseFormGroupNamesFromHistory($history)
    {
        try {
            $doseFormGroupConcept = $history['rxcuiStatusHistory']['definitionalFeatures']['doseFormGroupConcept'];
            return array_map(function ($i) {
                return $i['doseFormGroupName'];
            }, $doseFormGroupConcept);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
