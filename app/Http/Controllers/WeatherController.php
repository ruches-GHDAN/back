<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $apiKey = config('services.weather.key');

        if (!$apiKey) {
            return response()->json(['error' => 'API key is missing'], 500);
        }

        $url = "https://api.openweathermap.org/data/3.0/onecall?lat=$lat&lon=$lon&exclude=minutely,hourly,daily&appid=$apiKey";
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch weather data'], $response->status());
        }

        $weather = [
            'lat' => $response['lat'],
            'lon' => $response['lon'],
            'temperature' => $response['current']['temp'] - 273.15,
            'humidity' => $response['current']['humidity'],
            'main' => $response['current']['weather'][0]['main'],
        ];

        return response()->json($weather);
    }
}
