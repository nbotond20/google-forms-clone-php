<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;

class FormController extends Controller
{
    public function show()
    {
        $forms = Form::where('created_by', '=', auth()->user()->id)->orderBy('updated_at', 'desc')->paginate(5)->items();
        return view('site.forms', ['forms' => $forms]);
    }
}
