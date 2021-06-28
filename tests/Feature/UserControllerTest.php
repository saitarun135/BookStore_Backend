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
            "email"=>"sravani@gmail.com",
            "password"=>"SAItarun*1",
            "mobile"=>"8851597018"
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
            "password"=>"SAItarun*12"
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
            ])->json('Post','/api/auth/sendPasswordResetLink',[
                'email' =>'johnjo@gmail.com'
            ]);
        $response->assertStatus(404);       
        }
        
    public function test_ResetPasswordReturnSuccessStatus()
        {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9zZW5kUGFzc3dvcmRSZXNldExpbmsiLCJpYXQiOjE2MjQ4NjM1NzUsImV4cCI6MTYyNDg2NzE3NSwibmJmIjoxNjI0ODYzNTc1LCJqdGkiOiI4VEx3QW94elBzZ3g3elFOIiwic3ViIjo1LCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.2-cAenoIRfSSmebEfSetvbe0T0mxYP7TKC9zRChdkS0',
            ])->json('POST', '/api/auth/resetPassword', [
                "new_password" => "SAItarun*1",
                "confirm_password" => "SAItarun*1"
            ]);
            $response->assertStatus(200)->assertExactJson(['message' => 'Password reset successfull!']);
    }
    
    
    public function test_ResetPasswordReturnFailureStatus_WhenPass_InvalidToken()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaVwvZm9yZ290UGFzc3dvcmQiLCJpYXQiOjE2MjQyMjUzMDQsImV4cCI6MTYyNDIyODkwNCwibmJmIjoxNjI0MjI1MzA0LCJqdGkiOiI2a2IxajNHYXJXMkRzMlpCIiwic3ViIjozLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.wha6jrnL-5UtRVTF7bxuqXnQqEcILsUnKBma9pY3dLc',
        ])->json('POST', '/api/auth/resetPassword', [
            "new_password" => "SAItarun*1",
            "confirm_password" => "SAItarun*1"
        ]);
        $response->assertStatus(201);
}   
}
