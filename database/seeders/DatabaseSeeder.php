<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Apiary;
use App\Models\Disease;
use App\Models\Food;
use App\Models\Harvest;
use App\Models\Hive;
use App\Models\Stock;
use App\Models\Transhumance;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(5)->create();

        User::factory()->create([
            'firstName' => 'Admin',
            'lastName' => 'Admin',
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $users->each(function ($user) {
            $user->stocks()->saveMany(Stock::factory(3)->make());
            $apiaries = Apiary::factory(2)->create(['user_id' => $user->id]);

            $apiaries->each(function ($apiary) {
                $hives = Hive::factory(3)->create(['apiary_id' => $apiary->id]);

                $hives->each(function ($hive) {
                    $diseases = Disease::factory(1)->create();
                    $foods = Food::factory(2)->create();

                    $hive->diseases()->attach($diseases->pluck('id'));
                    $hive->foods()->attach($foods->pluck('id'));
                });

                $apiary->harvests()->saveMany(Harvest::factory(2)->make(['apiary_id' => $apiary->id]));
                $apiary->transhumances()->saveMany(Transhumance::factory(2)->make(['apiary_id' => $apiary->id]));
            });
        });
    }
}
