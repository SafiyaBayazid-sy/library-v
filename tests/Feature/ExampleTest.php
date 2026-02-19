<?php

test('the application returns a successful response', function () {
    $response = $this->get('/api/books');

    $response->assertStatus(200);
});
