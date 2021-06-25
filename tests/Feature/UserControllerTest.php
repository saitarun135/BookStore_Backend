<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    /* 
    *   create user if valid credentials entered
    */
    public function test_validCredentials(){
        $response = $this->json('POST','/api/register',[
            "fullName"=>"saiTarun",
            "email"=>"kit@gmail.com",
            "password"=>"SAItarun*1",
            "mobile"=>"8851597897"
            ]);
            $response->assertStatus(201);
    }

    /* 
    * create user if existing usermail-id entered
    */
    public function test_existingCredentials(){
        $response=$this->json('POST','/api/register',[
            "fullName"=>"sai",
            "email"=>"saitarun800@gmail.com",
            "password"=>"SAItarun*1",
            "mobile"=>"7901001572"
        ]);
        $response->assertStatus(422);
    }
    /* 
    * valid user login it should return true
    */

    public function test_Login_ValidUser()
    {
        $response = $this->json('POST','/api/login',[
            "email"=>"saitarun800@gmail.com",
            "password"=>"SAItarun*1"
        ]);
        $response->assertStatus(200);
    }
   
}
