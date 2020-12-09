<?php

use App\Skill;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        $skill = Skill::create(['name' => 'Laravel']);

        User::find(1)->skills()->attach($skill, ['level' => 'beginner']);
    }
}
