<?php

namespace Database\Factories;

use App\Models\Logistic;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogisticFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Logistic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $logistic = $this->faker->lastName;
        
        return [
            //
            'name_en'=>$logistic,
            'name_zh'=>$logistic,
            'name_cn'=>$logistic,
            'contact_no'=>$this->faker->phoneNumber,
            'email'=>$this->faker->email
        ];
    }
}
