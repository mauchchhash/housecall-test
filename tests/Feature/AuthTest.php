<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_registration(): void
    {
        $response = $this->post('/api/auth/register', [
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
            "confirm_password" => "12345678"
        ])->assertStatus(201)
            ->assertJsonStructure([
                "message", "accessToken"
            ]);
        $this->assertDatabaseHas('users', [
            "name" => "New User 1",
            "email" => "user1@example.com",
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_user_login(): void
    {
        $this->post('/api/auth/register', [
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
            "confirm_password" => "12345678"
        ]);

        $this->post('/api/auth/login', [
            "email" => "user1@example.com",
            "password" => "12345678"
        ])->assertStatus(200)
            ->assertJsonStructure([
                "accessToken", "token_type"
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_user_logout(): void
    {
        $this->post('/api/auth/register', [
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
            "confirm_password" => "12345678"
        ]);

        $loginResponse = $this->post('/api/auth/login', [
            "email" => "user1@example.com",
            "password" => "12345678"
        ]);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $loginResponse['accessToken'],
        ])->get('/api/auth/logout')->assertStatus(200)
            ->assertJson([
                "message" => "Successfully logged out"
            ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_fetch_user(): void
    {
        $this->post('/api/auth/register', [
            "name" => "New User 1",
            "email" => "user1@example.com",
            "password" => "12345678",
            "confirm_password" => "12345678"
        ]);

        $loginResponse = $this->post('/api/auth/login', [
            "email" => "user1@example.com",
            "password" => "12345678"
        ]);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $loginResponse['accessToken'],
        ])->get('/api/auth/user')->assertStatus(200)
            ->assertJson([
                "name" => "New User 1",
                "email" => "user1@example.com"
            ]);
    }
}
