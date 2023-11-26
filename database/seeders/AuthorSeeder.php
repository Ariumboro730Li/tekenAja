<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Author::truncate();

        $author = new \App\Models\Author();
        $author->author_name = 'Ari';
        $author->user_id = 1;
        $author->save();

        $author = new \App\Models\Author();
        $author->author_name = 'Umboro';
        $author->user_id = 1;
        $author->save();

        $author = new \App\Models\Author();
        $author->author_name = 'Seno';
        $author->user_id = 1;
        $author->save();

        $author = new \App\Models\Author();
        $author->author_name = 'Example Author';
        $author->user_id = 1;
        $author->save();

        Schema::enableForeignKeyConstraints();
    }
}
