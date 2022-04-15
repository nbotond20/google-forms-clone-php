<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Form;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

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
        $request->validate([
            'questions.*.*' => 'required',
        ]);

        $data = $request->request;
        $data = $data->all();

        /* dd($data); */

        foreach ($data['questions'] as $q_key => $question) {
            if (isset($question['radio'])) {
                $answer = new Answer([
                    'question_id' => $q_key,
                    'user_id' => ((Auth::check()) ? auth()->user()->id : -1),
                    'choice_id' => $question['radio'],
                    'answer' => null,
                ]);
                $answer->save();
            } else if (isset($question['checkbox'])) {
                foreach ($question['checkbox'] as $choice) {
                    $answer = new Answer([
                        'question_id' => $q_key,
                        'user_id' => ((Auth::check()) ? auth()->user()->id : null),
                        'choice_id' => $choice,
                        'answer' => null,
                    ]);
                    $answer->save();
                }
            } else {
                $answer = new Answer([
                    'question_id' => $q_key,
                    'user_id' => ((Auth::check()) ? auth()->user()->id : null),
                    'choice_id' => null,
                    'answer' => $question['text'],
                ]);
                $answer->save();
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
