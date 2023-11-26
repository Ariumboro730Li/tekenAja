<?php

namespace Tests\Feature;

use App\Http\Requests\BookRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use App\Models\User;
use Database\Seeders\BookSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookTest extends TestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BookSeeder::class); // this will seed the database with the data from BookSeeder.php
        $this->user = User::where('role_id', 1)->first();
        $this->actingAs($this->user);
    }


    /**
     * A basic feature test example.
     */
    public function testStore(): void
    {
        Book::where('book_name', 'Example Book')->delete();
        $data = new BookRequest([
            'book_name' => 'Example Book',
            'author_id' => 1,
        ]);

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('books', $data->all());


        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been saved',
        ]);
    }

    public function testFetchById(){
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->get('books/id/1');

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been fetched',
        ]);
    }

    public function testUpdate(){
        $book = Book::where('book_name', 'Example Book')->first();
        $data = new BookUpdateRequest([
            'id' => $book->id, // this is the id of 'Example Book
            'book_name' => 'Example Book',
            'author_id' => 2,
        ]);

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->put('books', $data->all());

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been updated',
        ]);

    }

    public function testDelete(){
        $book = Book::where('book_name', 'Example Book')->first();
        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->delete('books/'.$book->id);

        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'message' => 'Data has been deleted',
        ]);
    }

    public function testStoreFailedValidation(): void
    {
        $data = new BookRequest();

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->post('books', $data->all());

        $jsonString = $response->getContent();
        $arrayData = json_decode($jsonString, true);

        $response->assertJson($arrayData);
    }

}
