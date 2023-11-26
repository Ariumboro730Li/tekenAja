<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Book::truncate();

        $data = new Book;
        $data->book_name = 'Menuju Programmer Hebat';
        $data->author_id = 1;
        $data->user_id = 1;
        $data->save();

        $data = new Book;
        $data->book_name = 'Langkah langkah menjadi senior programmer';
        $data->author_id = 2;
        $data->user_id = 1;
        $data->save();

        $data = new Book;
        $data->book_name = 'Meraih 1 milyar pertama dari programmer';
        $data->author_id = 3;
        $data->user_id = 1;
        $data->save();

        $data = new Book;
        $data->book_name = 'Example Book';
        $data->author_id = 3;
        $data->user_id = 1;
        $data->save();

        Schema::enableForeignKeyConstraints();
    }
}
