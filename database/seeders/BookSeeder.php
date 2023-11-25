<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
