<?php

test('the application returns a successful response', function () {
    $response = $this->get('/en');

    $response->assertStatus(200);
});
