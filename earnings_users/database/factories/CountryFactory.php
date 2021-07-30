<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country  = $this->faker->country;
        return [
            //
            'name_en'=>$country,
            'name_cn'=>$country,
            'name_zh'=>$country,
            'description'=>$country,
        ];
    }
}
