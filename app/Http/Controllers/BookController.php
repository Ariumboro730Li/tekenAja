<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use App\Services\Book\DatatableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function page()
    {
        return view('pages.books');
    }

    public function datatable(){
        return app(DatatableService::class)->applyQueryBuilder()->applyActions()->editColumnsYajra()->getResultWithMessage();
    }

    public function store(BookRequest $request){
        $data = $request->validated();
        $book = [];
        DB::transaction(function () use ($data, &$book) {
            $book = Book::create([
                'book_name' => ucwords($data['book_name']),
                'author_id' => $data['author_id'],
                'user_id' => auth()->user()->id,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been saved',
            'data' => $book,
        ]);
    }

    public function fetchById($id){
        $data = Book::selectRaw(
            'books.id as id, books.book_name as book_name, authors.id as author_id, authors.author_name as author_name'
        )
        ->join('authors', 'books.author_id', '=', 'authors.id')->where("books.id", $id)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been fetched',
            'data' => $data,
        ]);
    }

    public function update(BookUpdateRequest $request){
        $book = [];
        DB::transaction(function () use ($request, &$book) {
            $find = Book::find($request->id);
            if($find){
                $book = Book::where('id', $request->id)->update([
                    'book_name' => ucwords($request->book_name),
                    'author_id' => $request->author_id,
                    'user_id' => auth()->user()->id,
                ]);
            }
        });

        if($book){
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been updated',
                'data' => $book,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
                'data' => $book,
            ], 404);
        }
    }

    public function delete($id){
        $book = [];
        DB::transaction(function () use ($id, &$book) {
            $find = Book::find($id);
            if($find){
                $book = Book::where('id', $id)->delete();
            }
        });

        if($book){
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted',
                'data' => $book,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
                'data' => $book,
            ], 404);
        }
    }
}
