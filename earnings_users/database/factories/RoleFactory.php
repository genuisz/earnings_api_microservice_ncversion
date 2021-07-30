<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->jobTitle;
        return [
            //
            'name_en'=>$name,
            'name_cn'=>$name,
            'name_zh'=>$name,
            'description'=>$name,
            'slug'=>$name
        ];
    }
}
