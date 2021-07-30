<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->randomElement($array = array ('create','update','read','delete'));
        return [
            //
            'display_name_en'=>$name,
            'display_name_cn'=>$name,
            'display_name_zh'=>$name,
            'name'=>$name,
            'description'=>$name
        ];
    }
}
