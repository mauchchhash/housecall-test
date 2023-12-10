<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DrugSearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_drug_search_data_is_valid(): void
    {
        Http::fake([
            'https://rxnav.nlm.nih.gov/REST/drugs.json*' => Http::response(file_get_contents(base_path('tests/Feature/mockHttpResponses/RxNormApi/getDrugs.json')), 200),
            'https://rxnav.nlm.nih.gov/REST/rxcui/*/historystatus.json' => Http::response(file_get_contents(base_path('tests/Feature/mockHttpResponses/RxNormApi/getRxcuiHistoryStatus.json')), 200),
        ]);
        $searchTerm = "Naproxen";
        $response = $this->get("/api/public/rx_norm_drugs/search?drug_name=$searchTerm");
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [
                [
                    'rxcui',
                    'name',
                    'baseNames' => [],
                    'doseFormGroupNames' => []
                ]
            ]]);
    }
}
