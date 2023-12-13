<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DrugsTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_drug_to_user_medication_list(): void
    {
        // $this->withoutExceptionHandling();
        Http::fake([
            'https://rxnav.nlm.nih.gov/REST/rxcui/*/historystatus.json' => Http::response(file_get_contents(base_path('tests/Feature/mockHttpResponses/RxNormApi/getRxcuiHistoryStatus.json')), 200),
        ]);

        $user = User::create([
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
        ]);
        $this->be($user);

        $response = $this->post("/api/users/{$user->id}/drugs", [
            'rxcui' => '105898'
        ])->assertStatus(201);
    }

    public function test_delete_a_user_prescribed_drug(): void
    {
        Http::fake([
            'https://rxnav.nlm.nih.gov/REST/rxcui/*/historystatus.json' => Http::response(file_get_contents(base_path('tests/Feature/mockHttpResponses/RxNormApi/getRxcuiHistoryStatus.json')), 200),
        ]);

        $user = User::create([
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
        ]);
        $this->be($user);

        $createdDrugResponse = $this->post("/api/users/{$user->id}/drugs", [
            'rxcui' => '105898'
        ]);
        $drugId = $createdDrugResponse['data']['id'];

        $this->delete("/api/users/{$user->id}/drugs/105898")->assertStatus(200);
    }

    public function test_get_prescribed_drugs_of_user(): void
    {
        // $this->withoutExceptionHandling();
        Http::fake([
            'https://rxnav.nlm.nih.gov/REST/rxcui/*/historystatus.json' => Http::response(file_get_contents(base_path('tests/Feature/mockHttpResponses/RxNormApi/getRxcuiHistoryStatus.json')), 200),
        ]);

        $user = User::create([
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
        ]);
        $this->be($user);

        $this->post("/api/users/{$user->id}/drugs", [
            'rxcui' => '105898'
        ]);
        $this->post("/api/users/{$user->id}/drugs", [
            'rxcui' => '105898'
        ]);

        $response = $this->get("/api/users/{$user->id}/drugs")->assertStatus(200);
    }
}
