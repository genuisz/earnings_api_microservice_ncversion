<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Repository\ProductRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
class ProductTest extends TestCase
{
    use WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }
    /** @test */
    public function it_can_list_related_constraint_product(){
        $product = Product::factory()->create();
        $productRepo = new ProductRepository();
        $result = $productRepo->listProduct($product->product_status_type_id,$product->category_id,'','','','','','','',['*'],['*'],['*'],['*']);

        foreach($result as $element){
            $this->assertEquals($product->product_status_type_id,$element->product_status_type_id);
            $this->assertEquals($product->category_id,$element->category_id);
        }

    }
    /** @test */
    public function it_can_list_item_with_correct_range_constraint(){
        $expireDateRange = Carbon::now()->addDay()->toDateTimeString();
        $range= $this->faker->numberBetween(0,20);
        $num = $this->faker->numberBetween(1,1000);
        $unitPrice = $this->faker->numberBetween(1,50);
        $leadtime = $this->faker->numberBetween(30,120);
        $expireDate =$this->faker->dateTimeBetween('now','+1 day')->format('Y-m-d H:i:s');

        $quantityOfLog = $this->faker->numberBetween(1,$num);
        $constraintRange = [
            'leadtime_range'=>$leadtime.'-'.($leadtime+$range),
            'unitPrice_range'=>$unitPrice.'-'.($unitPrice+$range),
            'quantity_per_log_range'=>$num.'-'.($num+$range),
            'achieve_rate_range'=> round(((1- ($quantityOfLog/$num))*100),1).'-'.round((((1- ($quantityOfLog/$num))*100)+$range),1)
        ];

        $product= Product::factory()->create(['duedate'=>$expireDate,'leadtime'=>$leadtime,'quantity_per_log'=>$num,'price'=>$num*$unitPrice,'quantity_of_log'=>$quantityOfLog]);
        $fromPort = $product->from_port_id;

        $expireDate  = Carbon::now()->addDay();
        $productRepo = new ProductRepository();
        dump($constraintRange);
        dump($expireDateRange);
        dump('expiredate'.$expireDate);
        dump('leadtime'.$leadtime);
        dump('quantity_per_log'.$num);
        dump('achieve_rate'.round((1- ($quantityOfLog/$num))*100),1);
        $result = $productRepo->listProduct($product->product_status_type_id,'',$expireDateRange,$constraintRange['leadtime_range'],$constraintRange['quantity_per_log_range'],$constraintRange['unitPrice_range'],$constraintRange['achieve_rate_range'],$fromPort,'',['*'],['*'],['*'],['*']);
        //$result = $productRepo->listProduct($product->product_status_typ,'','','','','','','','',['*'],['*'],['*'],['*']);
        $this->assertGreaterThan(0,$result->count());
        

    }
}
