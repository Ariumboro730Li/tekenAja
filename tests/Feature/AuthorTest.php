<?php

namespace Tests\Feature;

use App\Http\Requests\AuthorRequest;
use App\Http\Requests\AuthorUpdateRequest;
use App\Models\Author;
use App\Models\User;
use Database\Seeders\AuthorSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AuthorSeeder::class);
        $this->user = User::where('role_id', 1)->first();
        $this->actingAs($this->user);
        $this->author = "Example Author";
    }


    /**
     * A basic feature test example.
     */
    public function testStore(): void
    {
        if (Author::where('author_name', $this->author)->exists()) {
            Author::where('author_name', $this->author)->delete();
        }

        $data = new AuthorRequest([
            'name' =>  $this->author,
        ]);

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('authors', $data->all());

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been saved',
        ]);
    }

    public function testFetchById(){
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->get('authors/id/1');

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been fetched',
        ]);
    }

    public function testUpdate(){
        $author = Author::where('author_name',  $this->author)->first();
        $this->author = "Example Authors";

        $data = new AuthorUpdateRequest([
            'id' => $author->id, // this is the id of 'Example Author
            'name' => $this->author,
        ]);

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('authors', $data->all());

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been updated',
        ]);

    }

    public function testDelete(){
        $author = Author::where('author_name',  $this->author)->first();
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete('authors/'.$author->id);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been deleted',
        ]);
    }

    public function testStoreFailedValidation(): void
    {
        $data = new AuthorRequest();

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('authors', $data->all());

        $jsonString = $response->getContent();
        $arrayData = json_decode($jsonString, true);

        $response->assertJson($arrayData);
    }

}
