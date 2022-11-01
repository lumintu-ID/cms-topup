<?php

namespace App\Repository\Country;

use App\Models\Country;
use Illuminate\Support\Str;
use App\Repository\Country\CountryRepository;

class CountryImplement implements CountryRepository
{
    public function getAll()
    {
        $data =  Country::all();

        return $data;
    }

    public function create($request)
    {
        $data = Country::create([
            'country_id' => Str::uuid(),
            'currency' => $request['currency'],
            'country' => $request['country']
        ]);

        return $data;
    }

    public function getId($id)
    {
        $data = Country::where('country_id', $id)->first();

        return $data;
    }


    public function update($id, $request)
    {
        $data = Country::where('country_id', $id)->update([
            'currency' => $request['currency'],
            'country' => $request['country']
        ]);

        return $data;
    }

    public function delete($id)
    {
        $data = Country::where('country_id', $id)->delete();

        return $data;
    }
}
