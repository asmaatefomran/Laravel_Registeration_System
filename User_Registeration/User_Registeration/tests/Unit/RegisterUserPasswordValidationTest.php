<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class RegisterUserPasswordValidationTest extends TestCase
{
    private function getValidationRules(): array
    {
        return [
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',    
                'regex:/[A-Z]/',    
                'regex:/[0-9]/',    
            ],
        ];
    }

    /** @test */
    public function it_accepts_a_valid_password()
    {
        $data = [
            'password' => 'StrongPass1',
            'password_confirmation' => 'StrongPass1',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_rejects_passwords_without_uppercase_letters()
    {
        $data = [
            'password' => 'weakpass1',
            'password_confirmation' => 'weakpass1',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_rejects_passwords_without_lowercase_letters()
    {
        $data = [
            'password' => 'WEAKPASS1',
            'password_confirmation' => 'WEAKPASS1',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_rejects_passwords_without_numbers()
    {
        $data = [
            'password' => 'NoNumbersHere',
            'password_confirmation' => 'NoNumbersHere',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_rejects_passwords_shorter_than_8_characters()
    {
        $data = [
            'password' => 'Shrt1',
            'password_confirmation' => 'Shrt1',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_rejects_passwords_that_do_not_match_confirmation()
    {
        $data = [
            'password' => 'StrongPass1',
            'password_confirmation' => 'DifferentPass1',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }
}
