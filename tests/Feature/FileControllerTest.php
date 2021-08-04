<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileControllerTest extends TestCase
{

    public function test_UploadBook()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
            'Authorization'=>'Bearer 
            eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyNjUwMjA2MCwiZXhwIjoxNjI2NTA1NjYwLCJuYmYiOjE2MjY1MDIwNjAsImp0aSI6ImwwR21GcGpaS3NpNGtrVHoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.FWq4w3kWrmo2tYintKGxpjIAfeYxqjg-4HORn-Tliy8'
        ])->json('POST', '/api/addBooks', [
            'name'=>'On Writing',
            'file'=>'http://books.google.com/books/content?id=d999Z2KbZJYC&printsec=frontcover&img=1&zoom=5',
            'quantity'=>'5',
            'price'=>'500',
            'author'=>'Stephen king',
            'description'=>'The author shares his insights into the craft of writing and offers a humorous perspective on his own experience as a writer'
        ]);
     $response->assertStatus(201);
    }

}
