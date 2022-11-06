<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Label;
use App\Models\User;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $users = collect();
        $users -> add(User::factory()->create(
            ['email' => 'admin@szerveroldali.hu', 'password' => 'adminpwd', 'is_admin' => true]
        ));
        $n = rand(5, 10);
        for ($i = 1; $i <= $n; $i++){
            $users -> add(User::factory()->create(
                ['email' => 'user'.$i.'@szerveroldali.hu'],
            ));
        }

        $items = Item::factory(rand(10, 20))->create();
        $comments = Comment::factory(rand(10, 20))->create();
        $labels = Label::factory(rand(15, 25))->create();

        $comments -> each(function($c) use (&$users, &$items) {
            $c -> user() -> associate($users -> random()) -> save();
            $c -> item() -> associate($items -> random()) -> save();
            // $c -> items() -> attach($items -> random(rand(1, $items -> count())));
        });

        // $labels -> each(function($l) use (&$comments, &$labels) {
        //     // $item -> user() -> associate($users -> random()) -> save();
        //     // $item -> comments() -> attach($comments -> random(rand(1, $comments -> count())));
        //     $l -> labels() -> attach($comments -> random(rand(1, $comments -> count())));
        // });

        $items -> each(function($item) use (&$comments, &$labels) {
            // $item -> user() -> associate($users -> random()) -> save();
            // $item -> comments() -> attach($comments -> random(rand(1, $comments -> count())));
            $item -> labels() -> attach($labels -> random(rand(1, $labels -> count())));
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
