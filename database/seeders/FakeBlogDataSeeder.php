<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class FakeBlogDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Vider les tables
        Schema::disableForeignKeyConstraints();
        DB::table('comments')->truncate();
        DB::table('posts')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // Créer des utilisateurs
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->name;
            $email = strtolower(str_replace(' ', '.', $name)) . '@blog.com';
            $users[] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'), // mot de passe par défaut
                'role' => $faker->randomElement(['admin','editor', 'editor', 'reader', 'reader', 'reader', 'reader', 'reader', 'reader', 'reader']),
                'created_at' => $faker->dateTimeBetween('-14 months', '-13 months'),
                'updated_at' => now(),
            ]);
        }

        // Créer des catégories
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $categories[] = Category::create([
                'name' => ucfirst($faker->words(rand(1,3), true)),  // majuscule, aléatoire entre 2 et 4 mots, mode phrase (pas tableau)
                'description' => ucfirst($faker->sentence(5)),
                'created_at' => $faker->dateTimeBetween('-13 months', '-12 months'),
                'updated_at' => now(),
            ]);
        }

        // Créer des articles
        $posts = [];
        $postsWriters = User::whereIn('role', ['admin','editor'])->get();
        for ($i = 0; $i < 50; $i++) {
            $posts[] = Post::create([
                'user_id' => $faker->randomElement($postsWriters)->id,
                'title' => ucfirst($faker->words(rand(4,8), true)),
                'content' => ucfirst($faker->paragraph(5)),
                'created_at' => $faker->dateTimeBetween('-12 months', 'now'),
                'updated_at' => now(),
            ]);
        }

        // On associe les articles aux catégories
        foreach ($posts as $post) {
            $randomCategories = $faker->randomElements($categories, rand(1, 2));
            $post->categories()->sync(array_map(fn($cat) => $cat->id, $randomCategories));
        }

        // Créer des commentaires
        for ($i = 0; $i < 100; $i++) {
            Comment::create([
                'user_id' => $faker->randomElement($users)->id,
                'post_id' => $faker->randomElement($posts)->id,
                'content' => ucfirst($faker->sentence(20)),
                'created_at' => $faker->dateTimeBetween('-12 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
