<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Form;
use App\Models\Question;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Redirect;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $startDate = strtotime(date('Y-m-d H:i:s', strtotime($request->request->all()['exp-date'])));
        $currentDate = strtotime(date('Y-m-d H:i:s'));

        if ($startDate < $currentDate) {
            return redirect($request->server->get('HTTP_REFERER'));
        }

        $request->validate([
            'questions.checkbox.required.*' => 'sometimes|required',
            'questions.radio.required.*' => 'sometimes|required',
            'questions.text.required.*' => 'sometimes|required',
        ]);

        /* dd($request->all()['questions']['checkbox']['required']); */
        $validChoices = true;
        if (isset($request->all()['questions']['checkbox']['required'])) {
            foreach ($request->all()['questions']['checkbox']['required'] as $questionID => $answers) {
                $possibleChoices = array_map(function ($item) {
                    return ($item['id']);
                }, Question::where('id', $questionID)->first()->choices()->get()->toArray());

                if ($answers !== null) {
                    foreach ($answers as $choice) {
                        if (!in_array($choice, $possibleChoices)) {
                            $validChoices = false;
                        }
                    }
                }
            }
        }
        if (isset($request->all()['questions']['checkbox']['not-required'])) {
            foreach ($request->all()['questions']['checkbox']['not-required'] as $questionID => $answers) {
                $possibleChoices = array_map(function ($item) {
                    return ($item['id']);
                }, Question::where('id', $questionID)->first()->choices()->get()->toArray());

                if ($answers !== null) {
                    foreach ($answers as $choice) {
                        if (!in_array($choice, $possibleChoices)) {
                            $validChoices = false;
                        }
                    }
                }
            }
        }
        if (isset($request->all()['questions']['radio']['required'])) {
            foreach ($request->all()['questions']['radio']['required'] as $questionID => $answer) {
                $possibleChoices = array_map(function ($item) {
                    return ($item['id']);
                }, Question::where('id', $questionID)->first()->choices()->get()->toArray());

                if ($answer !== null) {
                    if (!in_array($answer, $possibleChoices)) {
                        $validChoices = false;
                    }
                }
            }
        }
        if (isset($request->all()['questions']['radio']['not-required'])) {
            foreach ($request->all()['questions']['radio']['not-required'] as $questionID => $answer) {
                $possibleChoices = array_map(function ($item) {
                    return ($item['id']);
                }, Question::where('id', $questionID)->first()->choices()->get()->toArray());

                if ($answer !== null) {
                    if (!in_array($answer, $possibleChoices)) {
                        $validChoices = false;
                    }
                }
            }
        }

        if (!$validChoices) {
            return \Redirect::back()->withErrors(['msg' => 'You have selected an invalid choice for one or more questions.']);
        }

        $data = $request->request;
        $data = $data->all();

        foreach ($data['questions'] as $answer_type => $reqs) {
            if ($answer_type === "text") {
                foreach ($reqs as $req_key => $req) {
                    foreach ($req as $question_id => $answer) {
                        if($answer !== null) {
                            $answer = Answer::create([
                                'user_id' => Auth::user()->id,
                                'question_id' => $question_id,
                                'answer' => $answer,
                                'choice_id' => null,
                            ]);
                            $answer->save();
                        }
                    }
                }
            } else if ($answer_type === "radio") {
                foreach ($reqs as $req) {
                    foreach ($req as $question_id => $choice) {
                        if($choice !== null) {
                            $answer = Answer::create([
                                'user_id' => Auth::user()->id,
                                'question_id' => $question_id,
                                'answer' => null,
                                'choice_id' => $choice,
                            ]);
                            $answer->save();
                        }
                    }
                }
            } else if ($answer_type === "checkbox") {
                foreach ($reqs as $req) {
                    foreach ($req as $question_id => $choices) {
                        foreach ($choices as $choice) {
                            if($choice !== null) {
                                $answer = Answer::create([
                                    'user_id' => Auth::user()->id,
                                    'question_id' => $question_id,
                                    'answer' => null,
                                    'choice_id' => $choice,
                                ]);
                                $answer->save();
                            }
                        }
                    }
                }
            }
        }
        return redirect()->route('forms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = Form::where('link', $id)->first();
        if ($form === null) {
            abort(404);
        }
        if ($form->auth_required && !auth()->check()) {
            return redirect()->route('login');
        }
        return view('site.form-fill', ['form' => $form]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
