<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CountryStateCitySeeder extends Seeder
{
    public function run()
    {
        // path to your JSON file (put inside /database/data/ folder)
        $json = file_get_contents(database_path('data/countries.json'));
        $countries = json_decode($json, true);

        foreach ($countries as $countryData) {

            $country = Country::create([
                'name' => $countryData['name'] ?? '',
                'iso3' => $countryData['iso3'] ?? '',
                'iso2' => $countryData['iso2'] ?? '',
                'numeric_code' => $countryData['numeric_code'] ?? '',
                'phone_code' => $countryData['phone_code'] ?? '',
                'capital' => $countryData['capital'] ?? '',
                'currency' => $countryData['currency'] ?? '',
                'nationality' => $countryData['nationality'] ?? '',
                'emoji' => $countryData['emoji'] ?? '',
                'latitude' => $countryData['latitude'] ?? null,
                'longitude' => $countryData['longitude'] ?? null,
            ]);

            if (!empty($countryData['states'])) {
                foreach ($countryData['states'] as $stateData) {

                    $state = State::create([
                        'country_id' => $country->id,
                        'name' => $stateData['name'] ?? '',
                        'state_code' => $stateData['state_code'] ?? '',
                        'latitude' => $stateData['latitude'] ?? null,
                        'longitude' => $stateData['longitude'] ?? null,
                    ]);

                    if (!empty($stateData['cities'])) {
                        foreach ($stateData['cities'] as $cityData) {
                            City::create([
                                'state_id' => $state->id,
                                'name' => $cityData['name'] ?? '',
                                'latitude' => $cityData['latitude'] ?? null,
                                'longitude' => $cityData['longitude'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
