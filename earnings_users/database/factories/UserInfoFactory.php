<?php

namespace Database\Factories;

use App\Models\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        return [
            //
            'name_en'=>$name,
            'name_cn'=>$name,
            'name_zh'=>$name,
            'reward_point'=>$this->faker->randomNumber(),
            'delivery_address1'=>$this->faker->address,
            'delivery_address2'=>$this->faker->address,
            'status'=>'1',
            'contact_no'=>$this->faker->phoneNumber,
            'notification_status'=>'1',
            'registered_ip'=>$this->faker->ipv4,
            'recent_ip'=>$this->faker->ipv4,
        ];
    }
}
