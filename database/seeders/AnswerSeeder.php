<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Form;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forms = Form::all();
        foreach ($forms as $form) {
            $userCount = rand(2, 5);
            $users = User::all()->random($userCount);
            foreach ($users as $user) {
                $questions = Question::where('form_id', $form->id)->get();
                foreach ($questions as $question) {
                    if (str_contains($question->answer_type, 'CHOICE')) {
                        if(str_contains($question->answer_type, 'ONE_CHOICE')){
                            $choice = $question->choices->random()->first();
                            Answer::factory()->for($question)->create([
                                'user_id' => $user->id,
                                'question_id' => $question->id,
                                'choice_id' => $choice->id,
                                'answer' => null
                            ]);
                        }else{
                            $choiceCount = rand(1, $question->choices->count());
                            $choices = $question->choices->random($choiceCount);
                            foreach ($choices as $choice) {
                                Answer::factory()->create([
                                    'user_id' => $user->id,
                                    'question_id' => $question->id,
                                    'choice_id' => $choice->id,
                                    'answer' => null
                                ]);
                            }
                        }
                    } else {
                        Answer::factory()->create([
                            'user_id' => $user->id,
                            'question_id' => $question->id,
                        ]);
                    }
                }
            }
        }
    }
}
