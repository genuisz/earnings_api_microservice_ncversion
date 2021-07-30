<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Factorys;
use App\Models\LogisticPort;
use App\Models\Product;
use App\Models\ProductStatusType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::factory()->create();
        $factorys = Factorys::factory()->create();
        $fromPort  = LogisticPort::factory()->create();
        
        $name = $this->faker->lastName;
        $quantityPerLog = $this->faker->numberBetween(1,10000);

        $quantityOfLogInven = $this->faker->numberBetween(1,1000000);
        $quantityOfLog = $this->faker->numberBetween(1,$quantityOfLogInven);
        $quantityOfLogTotal = $quantityOfLogInven;
        return [

            'category_id'=>$category->id,
            'factory_id'=>$factorys->id,
            'from_port_id'=>$fromPort->id,
            'name_en'=>$name,
            'name_zh'=>$name,
            'name_cn'=>$name,
            'product_no'=>quickRandom(10),
            'description'=>$this->faker->text(300),
            'quantity_per_log'=>$quantityPerLog,
            'quantity_of_log'=>$quantityOfLog,
            'quantity_of_log_total'=>$quantityOfLogTotal,
            'quantity_of_log_inventory_limit'=>$quantityOfLogInven,
            'quantity_unit_id'=>1,
            'quantity_reach_target_in_log'=>$quantityOfLogTotal,
            'price'=>$this->faker->randomFloat(2,1,100000),
            'log_weight'=>$this->faker->randomFloat(1,1,1000),
            'on_sale'=>1,
            'product_status_type_id'=>$this->faker->randomElement(array(1,2)),
            'tolerance'=>$this->faker->numberBetween(1,100),
            'leadtime'=>'10',
            'downpayment_ratio'=>$this->faker->numberBetween(1,10),
            'deposit_ratio'=>$this->faker->numberBetween(30,50),
            'duedate'=>$this->faker->dateTime('+30 days'),
            'image_url'=>$this->faker->url


        ];
    }
}
