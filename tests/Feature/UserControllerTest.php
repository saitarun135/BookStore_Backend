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
    /**
     * if guest (without credentials)login it shows unprocessible entity 
     */
    public function test_Login_Guest()
    {
        $response = $this->json('POST','/api/login',[
        ]);
        $response->assertStatus(422);
    }
    /* 
    * if the user provides invalid credentials
    */
    public function test_Login_With_Invalid_Credentials()
    {
        $response = $this->json('POST','/api/login',[
            "email"=>"saitarun800@gmail.com",
            "password"=>"SAItarun*123"
        ]);
        $response->assertStatus(401);
    }
    /**
     * checks emailID with DB and create a success msg
    */
    public function test_ForgotPasswordCreateSuccess(){
        $response = $this->withHeaders([
            'content-Type' =>'Application/json'
            ])->json('POST','/api/auth/sendPasswordResetLink',[
               'email' => 'saitarun800@gmail.com'
            ]);
        $response->assertStatus(200)
            ->assertExactJson(['data' => 'Reset link is send successfully, please check your inbox.']);
    }
    /**
    * checks weather the email is valid or not 
    */
    public function test_ForgotPasswordCreateFailure(){
        $response = $this->withHeaders([
            'Content-Type' => 'Application/Json'
            ])->json('Post','/api/auth/forgot',[
                'email' =>'johnjo@gmail.com'
            ]);
        $response->assertStatus(404);       
        }    
}
