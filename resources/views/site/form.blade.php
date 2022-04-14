@extends('layouts.layout')

@section('title', $form->title)

@section('content')
    @php
    $questions = $form->questions()->get();
    @endphp
    <div class="container pt-3">
        <h2>Form</h2>
        <div class="card p-3 mt-2 mb-3">
            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Example Title" disabled
                    value="{{ $form->title }}">
            </div>
            <div class="form-group mb-3">
                <label for="exp-date">Expiration date</label>
                @php
                    $date = explode(' ', $form->expires_at)[0];
                    $time = explode(' ', $form->expires_at)[1];
                @endphp
                <input type="datetime-local" class="form-control" id="exp-date" disabled
                    value="{{ $date }}T{{ $time }}">
            </div>
        </div>

        <h2>Questions</h2>
        <form action="{{ route('forms.store') }}" method="POST">
            <div class="card p-3 mt-2 mb-3">
                <div id="groups">
                    @php
                        $num = 0;
                        $uuid = Str::uuid();
                    @endphp
                    @foreach ($questions as $question)
                        @php
                            $choices = $question->choices()->get();
                        @endphp
                        <div class="card p-3 mt-2 mb-3">
                            <div class="mb-3" id="group_{{ $uuid }}">
                                <h3>{{ ++$num }}. Question</h3>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="fill-required_{{ $uuid }}"
                                        name="groups[{{ $uuid }}][fill-required]"
                                        @if ($question->required) checked @endif disabled>
                                    <label class="form-check-label" for="fill-required_{{ $uuid }}">Required</label>
                                </div>
                                <input type="text" class="form-control form-control-lg"
                                    id="question-title_{{ $uuid }}"
                                    name="groups[{{ $uuid }}][question-title]" placeholder="Question..."
                                    value="{{ $question->question }}" disabled>

                                {{-- TEXT --}}
                                @if ($question->answer_type == 'TEXTAREA')
                                    <div class="form-group mt-2">
                                        <textarea class="form-control mb-1" id="text_{{ $uuid }}" placeholder="Your answer..."></textarea>
                                    </div>
                                @endif

                                {{-- RADIO --}}
                                @if ($question->answer_type == 'ONE_CHOICE')
                                    @foreach ($choices as $choice)
                                        <div class="form-group mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="groups[{{ $uuid }}][answer]"
                                                    id="radio_{{ $uuid }}_{{ $choice->id }}" value="{{ $choice->id }}">
                                                <label class="form-check-label" for="radio_{{ $uuid }}_{{ $choice->id }}">
                                                    {{ $choice->choice }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- CHECKBOXES --}}
                                @if ($question->answer_type == 'MULTIPLE_CHOICES')
                                    @foreach ($choices as $choice)
                                        <div class="form-group mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="groups[{{ $uuid }}][answer][]"
                                                    id="checkbox_{{ $uuid }}_{{ $choice->id }}" value="{{ $choice->id }}">
                                                <label class="form-check-label" for="checkbox_{{ $uuid }}_{{ $choice->id }}">
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
        </form>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-secondary">Submit Form</button>
        </div>
    @endsection
