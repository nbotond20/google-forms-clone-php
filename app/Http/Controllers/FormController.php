<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Form;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Form::where('created_by', '=', auth()->user()->id)->orderBy('updated_at', 'desc')->paginate(5)->items();
        return view('site.forms', ['forms' => $forms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.form-modify');
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
            'title' => 'required|max:255',
            'exp-date' => 'required|date',
            // Form array
            'groups.*.question-title' => 'required|string|max:255',
            'groups.*.answer-type' => 'required|in:ONE_CHOICE,MULTIPLE_CHOICES,TEXTAREA',
            'groups.*.choice.*' => 'required|string|max:255',
            'groups' => 'required|array|min:1',
        ]);

        $data = $request->request;
        $data = $data->all();

        $form = new Form(
            [
                'title' => $data['title'],
                'expires_at' => $data['exp-date'],
                'auth_required' => (isset($data['login-req'])),
                'created_by' => auth()->user()->id,
                'link' => Str::uuid(),
            ]
        );
        $form->save();
        //$form->user()->associate(\Auth::user());

        foreach ($data['groups'] as $group) {
            $question = new Question(
                [
                    'question' => $group['question-title'],
                    'answer_type' => $group['answer-type'],
                    'required' => (isset($group['fill-required'])),
                    'form_id' => $form->id,
                ]
            );
            $question->save();
            //$form->questions()->attach($question);

            if ($question->answer_type == 'ONE_CHOICE' || $question->answer_type == 'MULTIPLE_CHOICES') {
                foreach ($group['choice'] as $choice) {
                    $choice = new Choice(
                        [
                            'choice' => $choice,
                            'question_id' => $question->id,
                        ]
                    );
                    $choice->save();
                    //$choice->question()->associate($question);
                }
            }
        }

        return view('site.form', ['form' => $form]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = Form::findOrFail($id);
        if (!($form->created_by === auth()->user()->id)) {
            abort(401);
        }
        return view('site.form', ['form' => $form]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = Form::findOrFail($id);
        if (!($form->created_by === auth()->user()->id)) {
            abort(401);
        }
        return view('site.form-modify', ['form' => $form]);
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
        $request->validate([
            'title' => 'required|max:255',
            'exp-date' => 'required|date',
            // Form array
            'groups.*.question-title' => 'required|string|max:255',
            'groups.*.answer-type' => 'required|in:ONE_CHOICE,MULTIPLE_CHOICES,TEXTAREA',
            'groups.*.choice.*' => 'required|string|max:255',
            'groups' => 'required|array|min:1',
        ]);

        $form = Form::findOrFail($id);
        if (!($form->created_by === auth()->user()->id)) {
            abort(401);
        }

        $data = $request->request;
        $data = $data->all();

        $form->update(
            [
                'title' => $data['title'],
                'expires_at' => $data['exp-date'],
                'auth_required' => (isset($data['login-req'])),
            ]
        );
        $questions = Question::where('form_id', '=', $form->id)->get();
        foreach ($questions as $question) {
            $choices = Question::where('question_id', '=', $question->id)->get();
            foreach ($choices as $choice) {
                $choice->delete();
            }
            $question->delete();
        }

        foreach ($data['groups'] as $group) {
            $question = new Question(
                [
                    'question' => $group['question-title'],
                    'answer_type' => $group['answer-type'],
                    'required' => (isset($group['fill-required'])),
                    'form_id' => $form->id,
                ]
            );
            $question->save();

            if ($question->answer_type == 'ONE_CHOICE' || $question->answer_type == 'MULTIPLE_CHOICES') {
                foreach ($group['choice'] as $choice) {
                    $choice = new Choice(
                        [
                            'choice' => $choice,
                            'question_id' => $question->id,
                        ]
                    );
                    $choice->save();
                }
            }
        }

        return view('site.form', ['form' => $form]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $questions = Question::where('form_id', '=', $form->id)->get();
        foreach ($questions as $question) {
            $choices = Question::where('question_id', '=', $question->id)->get();
            foreach ($choices as $choice) {
                $choice->delete();
            }
            $question->delete();
        }
        $form->delete();

        return redirect('forms');
    }
}
