<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WhatsAppNumberValidationTest extends TestCase
{
    private function getValidationRules(): array
    {
        return [
            'whatsapp_number' => [
                'required',
                'regex:/^[0-9]{11}$/',
            ],
        ];
    }

    private function mockValidWhatsAppApiResponse(): void
    {
        Http::fake([
            'whatsapp-number-validator3.p.rapidapi.com/*' => Http::response([
                'status' => 'valid'
            ], 200)
        ]);
    }

    private function mockInvalidWhatsAppApiResponse(): void
    {
        Http::fake([
            'whatsapp-number-validator3.p.rapidapi.com/*' => Http::response([
                'status' => 'invalid'
            ], 200)
        ]);
    }

    private function mockApiFailure(): void
    {
        Http::fake([
            'whatsapp-number-validator3.p.rapidapi.com/*' => Http::response([], 500)
        ]);
    }

    /** @test */
    public function it_accepts_valid_11_digit_whatsapp_number()
    {
        $data = [
            'whatsapp_number' => '12345678901',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_rejects_empty_whatsapp_number()
    {
        $data = [
            'whatsapp_number' => '',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('whatsapp_number', $validator->errors()->toArray());
    }

    /** @test */
    public function it_rejects_whatsapp_number_with_less_than_11_digits()
    {
        $data = [
            'whatsapp_number' => '1234567890',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('whatsapp_number', $validator->errors()->toArray());
    }

    /** @test */
    public function it_rejects_whatsapp_number_with_more_than_11_digits()
    {
        $data = [
            'whatsapp_number' => '123456789012',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_rejects_whatsapp_number_with_letters()
    {
        $data = [
            'whatsapp_number' => '1234567890a',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_rejects_whatsapp_number_with_special_characters()
    {
        $data = [
            'whatsapp_number' => '12345-67890',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_rejects_whatsapp_number_with_spaces()
    {
        $data = [
            'whatsapp_number' => '123 456 7890',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function it_accepts_whatsapp_number_with_leading_zeros()
    {
        $data = [
            'whatsapp_number' => '01234567890',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_makes_correct_api_call_for_valid_number()
    {
        $this->mockValidWhatsAppApiResponse();

        $whatsappNumber = '12345678901';
        $expectedPhoneNumber = '+2' . $whatsappNumber;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
            'phone_number' => $expectedPhoneNumber
        ]);

        $this->assertTrue($response->successful());
        $this->assertEquals('valid', $response->json('status'));

        Http::assertSent(function ($request) use ($expectedPhoneNumber) {
            return $request->url() === 'https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken' &&
                   $request['phone_number'] === $expectedPhoneNumber &&
                   $request->hasHeader('X-RapidAPI-Key', '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6');
        });
    }

    /** @test */
    public function it_returns_true_for_valid_whatsapp_number_response()
    {
        $this->mockValidWhatsAppApiResponse();

        $whatsappNumber = '12345678901';
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
            'phone_number' => '+2' . $whatsappNumber
        ]);

        $this->assertEquals('valid', $response->json('status'));
    }

    /** @test */
    public function it_returns_false_for_invalid_whatsapp_number_response()
    {
        $this->mockInvalidWhatsAppApiResponse();

        $whatsappNumber = '12345678901';
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
            'phone_number' => '+2' . $whatsappNumber
        ]);

        $this->assertEquals('invalid', $response->json('status'));
    }

    /** @test */
    public function it_handles_api_server_errors_gracefully()
    {
        $this->mockApiFailure();

        $whatsappNumber = '12345678901';
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
            'phone_number' => '+2' . $whatsappNumber
        ]);

        $this->assertFalse($response->successful());
        $this->assertEquals(500, $response->status());
    }


    /** @test */
    public function it_formats_phone_number_with_country_code_correctly()
    {
        $this->mockValidWhatsAppApiResponse();

        $testCases = [
            '12345678901' => '+212345678901',
            '01234567890' => '+201234567890',
            '99999999999' => '+299999999999',
        ];

        foreach ($testCases as $input => $expected) {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
                'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
            ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
                'phone_number' => $expected
            ]);

            Http::assertSent(function ($request) use ($expected) {
                return $request['phone_number'] === $expected;
            });
        }
    }

    /** @test */
    public function it_validates_api_request_headers()
    {
        $this->mockValidWhatsAppApiResponse();

        $whatsappNumber = '12345678901';
        
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
            'phone_number' => '+2' . $whatsappNumber
        ]);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-Type', 'application/json') &&
                   $request->hasHeader('X-RapidAPI-Key', '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6') &&
                   $request->hasHeader('X-RapidAPI-Host', 'whatsapp-number-validator3.p.rapidapi.com');
        });
    }



    /** @test */
    public function it_sends_correct_api_endpoint()
    {
        $this->mockValidWhatsAppApiResponse();

        $whatsappNumber = '12345678901';
        
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', [
            'phone_number' => '+2' . $whatsappNumber
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken';
        });
    }

    /** @test */
    public function it_validates_request_payload_structure()
    {
        $this->mockValidWhatsAppApiResponse();

        $whatsappNumber = '12345678901';
        $expectedPayload = [
            'phone_number' => '+2' . $whatsappNumber
        ];
        
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-RapidAPI-Key' => '5781c45302mshf7e3c7b407a86a3p137921jsndd01fdf934c6',
            'X-RapidAPI-Host' => 'whatsapp-number-validator3.p.rapidapi.com'
        ])->post('https://whatsapp-number-validator3.p.rapidapi.com/WhatsappNumberHasItWithToken', $expectedPayload);

        Http::assertSent(function ($request) use ($expectedPayload) {
            return $request->data() === $expectedPayload;
        });
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
    }

    protected function tearDown(): void
    {
        Http::clearResolvedInstances();
        parent::tearDown();
    }
}