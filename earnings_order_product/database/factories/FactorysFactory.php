<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Factorys as ModelsFactory;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactorysFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModelsFactory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->lastName;
        $country = Country::factory()->create();
        return [
            //
            'name_en'=>$name,
            'name_zh'=>$name,
            'name_cn'=>$name,
            'country_id'=>$country->id,
            'contact_no'=>$this->faker->phoneNumber,
            'address'=>$this->faker->address,
            'email'=>$this->faker->email
            
        ];
    }
}
