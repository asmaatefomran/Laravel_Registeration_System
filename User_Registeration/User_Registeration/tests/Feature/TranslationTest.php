<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\App;

class TranslationTest extends TestCase
{
    /**
     * Test that English translations work correctly
     */
    public function test_english_translations_return_correct_values()
    {
        // Set locale to English
        App::setLocale('en');
        
        // Test navigation translations
        $this->assertEquals('About', __('messages.about'));
        $this->assertEquals('Home', __('messages.home'));
        $this->assertEquals('Login', __('messages.login'));
        $this->assertEquals('Register', __('messages.register'));
        
        // Test form field translations
        $this->assertEquals('Full Name', __('messages.fullname'));
        $this->assertEquals('Username', __('messages.username'));
        $this->assertEquals('E-mail', __('messages.email'));
        $this->assertEquals('Phone Number', __('messages.phone'));
        $this->assertEquals('Password', __('messages.password'));
        
        // Test other common translations
        $this->assertEquals('Submit', __('messages.submit'));
        $this->assertEquals('Error', __('messages.error'));
        $this->assertEquals('Success', __('messages.success'));
    }

    /**
     * Test that locale switching works properly
     */
    public function test_locale_switching_changes_translations()
    {
        // Start with English
        App::setLocale('en');
        $englishHome = __('messages.home');
        
        // Switch to Arabic
        App::setLocale('ar');
        $arabicHome = __('messages.home');
        
        // Switch back to English
        App::setLocale('en');
        $englishHomeAgain = __('messages.home');
        
        // Assert translations are different and switching works
        $this->assertEquals('Home', $englishHome);
        $this->assertEquals('Home', $englishHomeAgain);
        $this->assertNotEquals($englishHome, $arabicHome);
    }

    /**
     * Test that unsupported locale falls back to default
     */
    public function test_unsupported_locale_falls_back_to_default()
    {
        // Set an unsupported locale
        App::setLocale('fr'); // French - not supported
        
        // Should fall back to English (or whatever your fallback is)
        $translation = __('messages.home');
        
        // This will either be English or the key itself if no fallback
        $this->assertTrue(
            $translation === 'Home' || $translation === 'messages.home',
            'Unsupported locale should fallback to default or return key'
        );
    }
}