<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        // Create a random number of forms for each user
        foreach ($users as $user) {
            $numForms = rand(1, 10);
            for ($i = 0; $i < $numForms; $i++) {
                Form::factory()->create([
                    'created_by' => $user->id,
                ]);
            }
        }
    }
}
