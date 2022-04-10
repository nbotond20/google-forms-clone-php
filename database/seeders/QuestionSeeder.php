<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forms = Form::all();
        // Create a random number of forms for each user
        foreach ($forms as $form) {
            $numOfQuestions = rand(1, 10);
            for ($i = 0; $i < $numOfQuestions; $i++) {
                Question::factory()->for($form)->create([
                    'form_id' => $form->id,
                ]);
            }
        }
    }
}
