<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = Http::withHeaders([
            'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
            'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com'
        ]);
    }

    private function getSymbols()
    {
        return json_decode(file_get_contents('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'));
    }
    
    public function showForm()
    {
        $symbols = $this->getSymbols();
        return view('simpleForm', ['symbols' => $symbols]);
    }

    public function processForm(Request $request)
    {
        $symbols = $this->getSymbols();

        $validator = Validator::make($request->all(), [
            'symbol' => 'required|in:' . implode(',', array_column($symbols, 'Symbol')),
            'start_date' => 'required|date|before_or_equal:today|before_or_equal:end_date',
            'end_date' => 'required|date|before_or_equal:today|after_or_equal:start_date',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect('/display/form')
                        ->withErrors($validator)
                        ->withInput();
        }

        $response = $this->httpClient->get('https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data', [
            'symbol' => $request->symbol,
            'region' => '',
        ]);

        $quotes = $response->json()['prices'];

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $filteredQuotes = array_filter($quotes, function ($quote) use ($startDate, $endDate) {
            $quoteDate = date('Y-m-d', $quote['date']);

            return ($quoteDate >= $startDate && $quoteDate <= $endDate);
        });

        return view('historicalData', [
            'quotes' => $filteredQuotes,
            'symbol' => $request->symbol
        ]);
    }
}
