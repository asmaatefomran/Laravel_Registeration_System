<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\user_info;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class PasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_hashes_password_when_user_registers()
    {
        // Prepare fake user data
        $userData = [
            'fullname' => 'John Doe',
            'username' => 'johndoe123',
            'email' => 'john@example.com',
            'phone' => '01234567890',
            'whatsapp_number' => '01234567890',
            'address' => '123 Main St',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->post(route('index.store', ['locale' => 'en']), $userData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Registration successful!');

        // Get the user saved in DB
        $user = user_info::where('email', 'john@example.com')->first();

        $this->assertNotNull($user);

        // Check the password is hashed and NOT equal to plain text
        $this->assertNotEquals('Password123', $user->password);

        // Check the password hash matches the original password
        $this->assertTrue(Hash::check('Password123', $user->password));
    }
}
