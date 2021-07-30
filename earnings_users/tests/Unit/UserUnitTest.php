<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserInfo;
use App\Repository\UserRepository;
use App\Repository\UserInfoRepository;
use Faker;
class UserUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function it_can_update_the_user(){
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create();

        $userInfoRepo = new UserInfoRepository();
        $userInfoRepo->setModel($userInfo);
        $update=[
            'name_en' => $this->faker->name
        ];
        $result = $userInfoRepo->updateUserInfo($update);
        $this->assertTrue($result);
        $this->assertEquals($update['name_en'],$userInfo->name_en);

        

    }
    
    /** @test */
    public function it_can_show_the_user(){
        $user = User::factory()->create();
        $userRepo  = new UserRepository();
        $found  = $userRepo->getOneById($user->id);
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->email, $found->email);
        $this->assertEquals($user->email_verified_at, $found->email_verified_at);
        $this->assertEquals($user->username, $found->username);
        $this->assertEquals($user->password, $found->password);
        $this->assertEquals($user->remember_token, $found->remember_token);
    } 
    /** @test */
    public function it_can_show_the_correct_user_info(){
        $user = User::factory()->create();
        $userInfo = UserInfo::factory()->create();
        $userRepo  = new UserRepository();
        $userRepo->setModel($user);
        //$userRepo->createUserInfo($userInfo->toArray());
        $list = $userRepo->getUserInfo();
        $this->assertCount(1, $list);
    }




    public function test_example()
    {
        $this->assertTrue(true);
    }
}
