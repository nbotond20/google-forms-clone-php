@extends('layouts.layout')

@section('title', $form->title)

@section('content')
    @php
    $questions = $form->questions()->get();
    @endphp
    <div class="container pt-3">
        <div class="form-group mb-3">
            <label for="title">Link to share</label>
            <a href="http://localhost:8000/form/{{ $form->link }}">
                <input type="text" class="form-control" id="link" disabled
                    value="http://localhost:8000/form/{{ $form->link }}">
            </a>
        </div>
    </div>
    <div class="container pt-3">
        @php
            $questions = $form->questions()->get();
            $hasAnswer = false;
            foreach ($questions as $question) {
                $answers = $question->answers()->get();
                if ($answers->count() > 0) {
                    $hasAnswer = true;
                    break;
                }
            }
        @endphp
        @if (!$hasAnswer && auth()->check())
            <div class="d-flex align-middle justify-content-between">
                <h2 class="mt-0">Form</h2>
                <button type="button" onclick="window.location='{{ route('forms.edit', $form->id) }}'"
                    class="btn btn-secondary">Edit</button>
            </div>
        @else
            <div class="d-flex align-middle justify-content-between">
                <h2 class="mt-0">Form</h2>
                <button type="button" class="btn btn-secondary" disabled>Edit</button>
            </div>
        @endif

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
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="login-req" name="login-req"
                    @if ($form->auth_required) checked @endif disabled>
                <label class="form-check-label" for="login-req">Login Required</label>
            </div>
        </div>

        <h2>Questions</h2>
        <div class="card p-3 mt-2 mb-3">
            <div id="groups">
                @php
                    $num = 0;
                    $uuid = Str::uuid();
                @endphp
                @foreach ($questions as $question)
                    @php
                        $choices = $question->choices()->get();
                        $answers = $question->answers()->get();
                    @endphp
                    <div class="card p-3 mt-2 mb-3">
                        <div class="mb-3" id="group_{{ $uuid }}">
                            <h3>{{ ++$num }}. Question @if ($answers->count() <= 0)
                                    <span class="text-secondary">(No Answers Yet)</span>
                                @endif
                            </h3>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="fill-required_{{ $uuid }}"
                                    name="groups[{{ $uuid }}][fill-required]"
                                    @if ($question->required) checked @endif disabled>
                                <label class="form-check-label" for="fill-required_{{ $uuid }}">Required</label>
                            </div>
                            <input type="text" class="form-control form-control-lg" id="question-title_{{ $uuid }}"
                                name="groups[{{ $uuid }}][question-title]" placeholder="Question..."
                                value="{{ $question->question }}" disabled>

                            {{-- TEXT --}}
                            @if ($question->answer_type == 'TEXTAREA')
                                @foreach ($answers as $answer)
                                    <div class="form-group mt-2">
                                        <span>
                                            <p class="mb-0" style="font-weight: bold;">
                                                @php
                                                    $user = App\Models\User::find($answer->user_id);
                                                    $name = $user ? $user->name : 'Anonymous';
                                                @endphp
                                                {{ $name }}</p>
                                        </span>
                                        {{ $answer->answer }}
                                    </div>
                                @endforeach
                            @endif

                            @php
                                $choices2 = $choices->toArray();
                                usort($choices2, function ($a, $b) use ($answers) {
                                    if (count($answers->where('choice_id', '=', $a['id'])) == count($answers->where('choice_id', '=', $b['id']))) {
                                        return 0;
                                    }
                                    return count($answers->where('choice_id', '=', $a['id'])) > count($answers->where('choice_id', '=', $b['id'])) ? -1 : 1;
                                });
                            @endphp

                            {{-- RADIO --}}
                            @if ($question->answer_type == 'ONE_CHOICE')
                                @foreach ($choices2 as $choice)
                                    <div class="form-group mt-2">
                                        <div class="">
                                            <i class="fa-regular fa-circle"></i>
                                            <span class="font-weight-normal"
                                                for="radio_{{ $uuid }}_{{ $choice['id'] }}">
                                                {{ $choice['choice'] }}
                                            </span>
                                            <span class="text-warning">
                                                @if ($answers->count() > 0)
                                                    {{ round((count($answers->where('choice_id', '=', $choice['id'])) / $answers->count()) * 100, 2) }}%
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- CHECKBOXES --}}
                            @if ($question->answer_type == 'MULTIPLE_CHOICES')
                                @foreach ($choices2 as $choice)
                                    <div class="form-group mt-2">
                                        <div class="">
                                            <i class="fa-regular fa-square"></i>
                                            <span class="form-check-label"
                                                for="checkbox_{{ $uuid }}_{{ $choice['id'] }}">
                                                {{ $choice['choice'] }}
                                            </span>
                                            <span class="text-warning">
                                                @if ($answers->count() > 0)
                                                    {{ round((count($answers->where('choice_id', '=', $choice['id'])) / $answers->count()) * 100, 2) }}%
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection
