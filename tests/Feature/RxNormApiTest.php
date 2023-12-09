<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RxNormApiTest extends TestCase
{
    /**
     * Test that the RxNormApi's macro is registered to the HTTP Facade
     */
    public function test_RxNormApi_macro_is_registered(): void
    {
        $this->assertInstanceOf('Illuminate\Http\Client\PendingRequest', Http::RxNormApi());
    }
}
