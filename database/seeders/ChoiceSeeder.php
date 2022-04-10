<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\Question;
use Illuminate\Database\Seeder;

class ChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Question::all();
        // Create a random number of forms for each user
        foreach ($questions as $question) {
            $numOfChoices = rand(2, 5);
            if (str_contains($question->answer_type, 'CHOICE')) {
                for ($i = 0; $i < $numOfChoices; $i++) {
                    Choice::factory()->for($question)->create([
                        'question_id' => $question->id,
                    ]);
                }
            }
        }
    }
}
