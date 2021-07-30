<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\LogisticPort;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogisticPortFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LogisticPort::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country = Country::factory()->create();
        $port = $this->faker->state;
        return [
            //
            'name_en'=>$port,
            'name_cn'=>$port,
            'name_zh'=>$port,
            'slug'=>$port,
            'country_id'=>$country->id,
            'port_type_id'=>$this->faker->randomElement(array(1,2))
            
        ];
    }
}
