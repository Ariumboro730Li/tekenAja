<?php

namespace Tests\Feature;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->user = User::where('role_id', 1)->first();
        $this->actingAs($this->user);
        $this->name = "Example User";
        $this->email = "example@admin.com";
    }

    /**
     * A basic feature test example.
     */
    public function testStore(): void
    {
        if (User::where('email',  $this->email)->exists()) {
            User::where('email',  $this->email)->forcedelete();
        }

        $data = new UserRequest([
            'name' =>  $this->name,
            'email' => $this->email,
            'role_id' => 1,
            'password' => 'adminadmin',
        ]);


        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('users', $data->all());

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been saved',
        ]);
    }

    public function testFetchById(){
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->get('users/id/1');

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been fetched',
        ]);
    }

    public function testUpdate(){
        $name = User::where('email',  $this->email)->first();
        $this->name = "Example Users";

        $data = new UserUpdateRequest([
            'id' => $name->id, // this is the id of 'Example User
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => 2,
            'password' => 'adminadmin',
        ]);

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('users', $data->all());

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been updated',
        ]);

    }

    public function testDelete(){
        $name = User::where('email',  $this->email)->first();
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete('users/'.$name->id);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'User has been disabled',
        ]);
    }

    public function testActivate(){
        $name = User::where('name',  $this->name)->first();
        if($name){
            User::where('name',  $this->name)->delete();
        }
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('users/activate/'.$name->id);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'User is Active now',
        ]);

    }

    public function testStoreFailedValidation(): void
    {
        $data = new UserRequest();

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('users', $data->all());

        $jsonString = $response->getContent();
        $arrayData = json_decode($jsonString, true);

        $response->assertJson($arrayData);
    }

}
