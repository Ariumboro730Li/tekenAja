<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Http\Requests\AuthorUpdateRequest;
use App\Models\Author;
use App\Models\Book;
use App\Services\Author\DatatableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    public function page()
    {
        return view('pages.authors');
    }

    public function list(){
        $data = Author::select("id", "author_name")->get()->toArray();
        return response()->json($data);
    }

    public function datatable(){
        return app(DatatableService::class)->applyQueryBuilder()->applyActions()->editColumnsYajra()->getResultWithMessage();
    }

    public function store(AuthorRequest $request){
        $data = $request->validated();
        $author = [];
        DB::transaction(function () use ($data, &$author) {
            $author = Author::create([
                'author_name' => ucwords($data['name']),
                'user_id' => auth()->user()->id,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been saved',
            'data' => $author,
        ]);
    }
    public function fetchById($id){
        $data = Author::where("id", $id)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been fetched',
            'data' => $data,
        ]);
    }

    public function update(AuthorUpdateRequest $request){
        $author = [];
        DB::transaction(function () use ($request, &$author) {
            $find = Author::find($request->id);
            if($find){
                $author = Author::where('id', $request->id)->update([
                    'author_name' => ucwords($request->name),
                    'user_id' => auth()->user()->id,
                ]);
            }
        });

        if($author){
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been updated',
                'data' => $author,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
                'data' => $author,
            ], 404);
        }
    }

    public function delete($id){
        $message = '';
        $books = [];
        $author = [];
        DB::transaction(function () use ($id, &$author, &$books, &$message) {
            $books = Book::where('author_id', $id)->count();
            if(!$books){
                $find = Author::find($id);
                if($find){
                    $author = Author::where('id', $id)->delete();
                }
            } else {
                $message = 'Author cannot be deleted because there are books that use this author';
            }
        });

        if($author){
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted',
                'data' => $author,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => $message ? $message : 'Data not found',
                'data' => $author,
            ], 404);
        }
    }
}
