@extends('layouts.layout')

@section('title', $form->title)

@section('content')
    @php
    $expired = false;
    $startDate = strtotime(date('Y-m-d H:i:s', strtotime($form->expires_at)));
    $currentDate = strtotime(date('Y-m-d H:i:s'));

    if ($startDate < $currentDate) {
        $expired = true;
    }
    @endphp
    @if (!$expired)
        @if ($errors->any())
            <div class="alert alert-danger align-middle" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            $questions = $form->questions()->get();
        @endphp
        <div class="container pt-3">
            @php
                $questions = $form->questions()->get();
            @endphp
            <form method="POST" action="{{ route('form.store') }}">
                @csrf
                <div class="d-flex align-middle justify-content-between">
                    <h2 class="mt-0">Form</h2>
                </div>

                <div class="card p-3 mt-2 mb-3">
                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" disabled value="{{ $form->title }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="exp-date">Expiration date</label>
                        @php
                            $date = explode(' ', $form->expires_at)[0];
                            $time = explode(' ', $form->expires_at)[1];
                        @endphp
                        <input type="datetime-local" class="form-control" disabled
                            value="{{ $date }}T{{ $time }}">
                        <input type="hidden" name="exp-date" id="exp-date" value="{{ $date }}T{{ $time }}"
                            style="display: none;">
                    </div>
                </div>

                <h2>Questions</h2>
                <div class="card p-3 mt-2 mb-3">
                    <div id="questions">
                        @php
                            $num = 0;
                        @endphp
                        @foreach ($questions as $question)
                            @php
                                $choices = $question->choices()->get();
                                $uuid = Str::uuid();
                            @endphp
                            <div class="card p-3 mt-2 mb-3">
                                <div class="mb-3" id="group_{{ $uuid }}">
                                    <h3>{{ ++$num }}. Question <span class="text-secondary">
                                            @if ($question->required)
                                                (Required)
                                            @endif
                                        </span> </h3>
                                    <input type="text" class="form-control form-control-lg"
                                        value="{{ $question->question }}" disabled>

                                    {{-- TEXT --}}
                                    @if ($question->answer_type == 'TEXTAREA')
                                        <div class="form-group mt-2">
                                            <textarea class="form-control mb-1" id="questions[{{ $question->id }}][text]"
                                                name="questions[text][{{ $question->required ? 'required' : 'not-required' }}][{{ $question->id }}]"
                                                placeholder="Your answer..."></textarea>
                                        </div>
                                    @endif

                                    {{-- RADIO --}}
                                    @if ($question->answer_type == 'ONE_CHOICE')
                                        <input class="form-check-input" type="hidden"
                                            name="questions[radio][{{ $question->required ? 'required' : 'not-required' }}][{{ $question->id }}]"
                                            id="radio_hidden" value={{ null }}>
                                        @foreach ($choices as $choice)
                                            <div class="form-group mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="questions[radio][{{ $question->required ? 'required' : 'not-required' }}][{{ $question->id }}]"
                                                        id="radio_[{{ $choice->id }}]" value="{{ $choice->id }}">
                                                    <label class="form-check-label" for="radio_[{{ $choice->id }}]">
                                                        {{ $choice->choice }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- CHECKBOXES --}}
                                    @if ($question->answer_type == 'MULTIPLE_CHOICES')
                                        <input class="form-check-input" type="hidden"
                                            name="questions[checkbox][{{ $question->required ? 'required' : 'not-required' }}][{{ $question->id }}]"
                                            id="checkbox_hidden" value={{ null }}>
                                        @foreach ($choices as $choice)
                                            <div class="form-group mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="questions[checkbox][{{ $question->required ? 'required' : 'not-required' }}][{{ $question->id }}][{{ $choice->id }}]"
                                                        id="checkbox_[{{ $choice->id }}]" value="{{ $choice->id }}">
                                                    <label class="form-check-label" for="checkbox_[{{ $choice->id }}]">
                                                        {{ $choice->choice }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-secondary">Submit Form</button>
                </div>
            </form>
        @else
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">This form has expired.</h4>
                <p>The form is closed. Please check back later.</p>
    @endif
@endsection
