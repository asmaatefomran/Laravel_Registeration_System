<?php

namespace Tests\Feature;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function password_must_meet_requirements()
    {
        $response = $this->post(route('index.store'), [
            'fullname' => 'Menna Saad',
            'username' => 'MennaSaad',
            'email' => 'menna@example.com',
            'phone' => '1234567890',
            'whatsapp_number' => '1234567890',
            'address' => '123 Helwan St',
            'password' => 'weak',
            'password_confirmation' => 'weak'
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}