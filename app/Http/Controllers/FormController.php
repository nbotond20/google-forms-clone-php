<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;

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

        /* dd($request->request->all());
        $form = new Form(
            [
                'title' => $request->title,
                'expires_at' => $request->exp-date,
                'auth_required' => $request->auth_required,
                'created_by' => auth()->user()->id,
            ]
        ); */



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
        $form = Form::findOrFail($id);
        if(!($form->created_by === auth()->user()->id)){
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
        if(!($form->created_by === auth()->user()->id)){
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

        return redirect()->route('forms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
