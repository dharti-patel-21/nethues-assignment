<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormControllerTest extends TestCase
{
    /**
     * 
     *
     * @return void
     */

    public function testFormValidation()
    {
        $symbols = json_decode(file_get_contents('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'));
        $symbolList = implode(',', array_column($symbols, 'Symbol'));

        // Testing invalid symbol
        $response = $this->post('/process/form', [
            'symbol' => 'INVALID',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-10',
            'email' => 'test@example.com'
        ]);
        $response->assertSessionHasErrors(['symbol']);

        // Testing invalid dates
        $response = $this->post('/process/form', [
            'symbol' => $symbols[0]->Symbol,
            'start_date' => 'invalid-date',
            'end_date' => '2022-01-10',
            'email' => 'test@example.com'
        ]);
        $response->assertSessionHasErrors(['start_date']);

        $response = $this->post('/process/form', [
            'symbol' => $symbols[0]->Symbol,
            'start_date' => '2022-01-01',
            'end_date' => 'invalid-date',
            'email' => 'test@example.com'
        ]);
        $response->assertSessionHasErrors(['end_date']);

        // Testing start date after end date
        $response = $this->post('/process/form', [
            'symbol' => $symbols[0]->Symbol,
            'start_date' => '2022-01-10',
            'end_date' => '2022-01-01',
            'email' => 'test@example.com'
        ]);
        $response->assertSessionHasErrors(['start_date']);

         // Testing start date and end date after current date
        $response = $this->post('/process/form', [
            'symbol' => 'VALIDSYMBOL',
            'start_date' => date('Y-m-d', strtotime('+1 day')), // Tomorrow's date
            'end_date' => '2024-08-10',
            'email' => 'test@example.com'
        ]);
        $response->assertSessionHasErrors(['start_date']);

        // Testing valid data
        $response = $this->post('/process/form', [
            'symbol' => $symbols[0]->Symbol,
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-10',
            'email' => 'test@example.com'
        ]);
        $response->assertSessionDoesntHaveErrors();
        $response->assertStatus(200);
    }
}
