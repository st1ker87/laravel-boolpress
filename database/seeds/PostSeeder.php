<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i=0; $i < 10; $i++) { 
            $new_post = new Post();

            $new_post->title = $faker->sentence(rand(2, 6));
            $new_post->content = $faker->text(rand(100,200));
            $new_post->slug = Str::slug($new_post->title, '-');

            $new_post->save();
        }
    }
}
