<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\HealthController;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HealthControllerTest extends TestCase
{
    /**
     * Tests health check endpoint.
     */
    public function test_health_endpoint_ok_response(): void
    {
        $response = $this->get('api/health');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['status' => HealthController::STATUS_OK]);
    }
}
