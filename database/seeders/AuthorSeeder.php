<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
