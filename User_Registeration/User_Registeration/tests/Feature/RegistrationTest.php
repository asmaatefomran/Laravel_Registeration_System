<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the home page returns a successful response.
     */
    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
    }

    /**
     * Test the registration form loads correctly
     */
    public function test_registration_form_loads_correctly()
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSee('Register in the Form');
        $response->assertSee('fullname');
        $response->assertSee('Username');
        $response->assertSee('E-mail');
    }

    /**
     * Test successful student registration
     */
    public function test_student_can_register_successfully()
    {
        Storage::fake('public');

        $response = $this->post('/en', [
            'fullname' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'phone' => '12345678901',
            'whatsapp_number' => '12345678901',
            'address' => '123 Main St',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Registration successful!');

        $this->assertDatabaseHas('user_infos', [
            'fullname' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
        ]);
    }
}
