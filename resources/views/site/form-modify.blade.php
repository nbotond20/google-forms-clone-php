@extends('layouts.layout')

@section('title', isset($form) ? 'Edit Form' : 'New Form')

@section('content')
    <div class="container pt-3">
        {{-- Validator hibáinak kilogolása --}}
        @error('groups')
            <div class="alert alert-danger align-middle" role="alert">
                <ul class="mb-0">
                    <li>{{ 'You have to add at least one question!' }}</li>
                </ul>
            </div>
        @enderror
        {{-- @if ($errors->any())
            <div class="alert alert-danger align-middle" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <form
            action="@isset($form) {{ route('forms.update', $form->id) }} @else {{ route('forms.store') }} @endisset"
            method="POST">
            @csrf
            @isset($form)
                @method('put')
            @endisset
            <h2>
                @if (isset($form))
                    Edit Form
                @else
                    New Form
                @endif
            </h2>
            <div class="card p-3 mt-2 mb-3">
                <div class="form-group mb-3">
                    <label for="title">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                        placeholder="Example Title" value="{{ old('title', isset($form) ? $form->title : '') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="exp-date">Expiration date</label>
                    @php
                        if (isset($form)) {
                            $date = explode(' ', $form->expires_at)[0];
                            $time = explode(' ', $form->expires_at)[1];
                        }
                    @endphp
                    <input type="datetime-local" class="form-control @error('exp-date') is-invalid @enderror" id="exp-date"
                        name="exp-date" value={{ old('exp-date', isset($form) ? $date . 'T' . $time : '') }}>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="login-req" name="login-req"
                        @if (old('login-req') !== null) checked @endif>
                    <label class="form-check-label" for="login-req">Login Required</label>
                </div>
            </div>

            {{-- <h2>Questions</h2> --}}
            <div class="card p-3 mt-2 mb-3">
                <div id="groups">
                    @if (old('groups') !== null)
                        @php
                            $num = 0;
                            $uuid = Str::uuid();
                        @endphp
                        @foreach (old('groups') as $group)
                            <div class="mb-3" id="group_{{ $uuid }}">
                                <h3>{{ ++$num }}. Question</h3>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="fill-required_{{ $uuid }}"
                                        name="groups[{{ $uuid }}][fill-required]"
                                        @if (isset($group['fill-required'])) checked @endif>
                                    <label class="form-check-label"
                                        for="fill-required_{{ $uuid }}">Required</label>
                                </div>
                                <input type="text"
                                    class="form-control form-control-lg @if (!isset($group['question-title'])) is-invalid @endif"
                                    id="question-title_{{ $uuid }}"
                                    name="groups[{{ $uuid }}][question-title]" placeholder="Question..."
                                    value="@if (isset($group['question-title'])) {{ $group['question-title'] }} @endif">
                                <div class="card p-3 mt-2 mb-3">

                                    <div class="form-group mb-3 question-inside">
                                        <label for="answer-type_{{ $uuid }}">Answer Type</label>
                                        <select id="answer-type_{{ $uuid }}"
                                            name="groups[{{ $uuid }}][answer-type]"
                                            class="form-select @if (!isset($group['answer-type'])) is-invalid @endif">
                                            <option value="" @if ($group['answer-type'] === null) selected @endif>...</option>
                                            <option value="ONE_CHOICE" @if ($group['answer-type'] === 'ONE_CHOICE') selected @endif>
                                                One
                                                Choice</option>
                                            <option value="MULTIPLE_CHOICES"
                                                @if ($group['answer-type'] === 'MULTIPLE_CHOICES') selected @endif>Multiple Choice</option>
                                            <option value="TEXTAREA" @if ($group['answer-type'] === 'TEXTAREA') selected @endif>
                                                Text
                                            </option>
                                        </select>
                                        @if (isset($group['choice']))
                                            <div class="form-group mt-2">
                                                @foreach ($group['choice'] as $choice)
                                                    @php
                                                        $choiceID = Str::uuid();
                                                    @endphp
                                                    <input type="text"
                                                        class="form-control mb-1 @if ($choice === null) is-invalid @endif"
                                                        id="choice_{{ $uuid }}_{{ $choiceID }}"
                                                        name="groups[{{ $uuid }}][choice][{{ $choiceID }}]"
                                                        placeholder="Choice" value="{{ $choice }}">
                                                @endforeach
                                                <div class="mt-2 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary add-choice"
                                                        data-group-id="{{ $uuid }}">+</button>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="delete-group btn btn-danger"
                                            data-group-id="{{ $uuid }}">Delete Question</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @elseif(isset($form))
                        @php
                            $num = 0;
                            $questions = $form->questions()->get();
                        @endphp
                        @foreach ($questions as $question)
                            @php
                                $uuid2 = Str::uuid();
                                $choices = $question->choices()->get();
                            @endphp
                            <div class="mb-3" id="group_{{ $uuid2 }}">
                                <h3>{{ ++$num }}. Question</h3>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="fill-required_{{ $uuid2 }}"
                                        name="groups[{{ $uuid2 }}][fill-required]"
                                        @if ($question->required) checked @endif>
                                    <label class="form-check-label"
                                        for="fill-required_{{ $uuid2 }}">Required</label>
                                </div>
                                <input type="text" class="form-control form-control-lg"
                                    id="question-title_{{ $uuid2 }}"
                                    name="groups[{{ $uuid2 }}][question-title]" placeholder="Question..."
                                    value="{{ $question->question }}">
                                <div class="card p-3 mt-2 mb-3">

                                    <div class="form-group mb-3 question-inside">
                                        <label for="answer-type_{{ $uuid2 }}">Answer Type</label>
                                        <select id="answer-type_{{ $uuid2 }}"
                                            name="groups[{{ $uuid2 }}][answer-type]" class="form-select">
                                            <option value="" @if ($question->answer_type === null) selected @endif>
                                                ...
                                            </option>
                                            <option value="ONE_CHOICE" @if ($question->answer_type === 'ONE_CHOICE') selected @endif>
                                                One Choice
                                            </option>
                                            <option value="MULTIPLE_CHOICES"
                                                @if ($question->answer_type === 'MULTIPLE_CHOICES') selected @endif>
                                                Multiple Choice
                                            </option>
                                            <option value="TEXTAREA" @if ($question->answer_type === 'TEXTAREA') selected @endif>
                                                Text
                                            </option>
                                        </select>
                                        @if ($choices->count() > 0)
                                            <div class="form-group mt-2">
                                                @foreach ($choices as $choice)
                                                    @php
                                                        $choiceID2 = Str::uuid();
                                                    @endphp
                                                    <input type="text" class="form-control mb-1"
                                                        id="choice_{{ $uuid2 }}_{{ $choiceID2 }}"
                                                        name="groups[{{ $uuid2 }}][choice][{{ $choiceID2 }}]"
                                                        placeholder="Choice" value="{{ $choice->choice }}">
                                                @endforeach
                                                <div class="mt-2 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary add-choice"
                                                        data-group-id="{{ $uuid2 }}">+</button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="delete-group btn btn-danger"
                                            data-group-id="{{ $uuid2 }}">Delete Question</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" id="add-group">New Question</button>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-secondary">Save Form</button>
            </div>
        </form>


        <script>
            let questionCount = 0;
            // Template a group-hoz
            const template = (param, num, id) => `
			<div class="mb-3" id="group_${id}">
                <h3>${num}. Question</h3>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="fill-required_${id}" name="groups[${id}][fill-required]" checked>
                    <label class="form-check-label" for="fill-required_${id}">Required</label>
                </div>
                <input type="text" class="form-control form-control-lg" id="question-title_${id}" name="groups[${id}][question-title]" placeholder="Question...">
				<div class="card p-3 mt-2 mb-3">
					${(param === '') ? '' : param}
					<div class="d-flex justify-content-center">
						<button type="button" class="delete-group btn btn-danger" data-group-id="${id}">Delete Question</button>
					</div>
				</div>
			</div>`;

            // Empty Question template
            const insideQuestion = (param, selected, id) => `
			<div class="form-group mb-3 question-inside">
				<label for="answer-type_${id}">Answer Type</label>
				<select id="answer-type_${id}" name="groups[${id}][answer-type]" class="form-select">
					<option value="" ${(selected == 0) ? "selected" : ""}>...</option>
					<option value="ONE_CHOICE" ${(selected == 1) ? "selected" : ""}>One Choice</option>
					<option value="MULTIPLE_CHOICES" ${(selected == 2) ? "selected" : ""}>Multiple Choice</option>
					<option value="TEXTAREA" ${(selected == 3) ? "selected" : ""}>Text</option>
				</select>
                ${(param === '') ? '' : param}
			</div>`;

            const choiceFormTemplate = (id, rndID) => `
            <div class="form-group mt-2">
                <input type="text" class="form-control mb-1" id="choice_${id}_${rndID}" name="groups[${id}][choice][${rndID}]" placeholder="Choice">
                <div class="mt-2 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary add-choice" data-group-id="${id}">+</button>
                </div>
			</div>`;

            // Choice template
            const choiceTemplate = (id, rndID) =>
                `<input type="text" class="form-control mb-1" id="choice_${id}_${rndID}" name="groups[${id}][choice][${rndID}]" placeholder="Choice">`;

            const groups = document.querySelector('#groups');
            const addGroup = document.querySelector('button#add-group');
            addGroup.addEventListener('click', (event) => {
                const id = uuid.v4()
                let div = document.createElement('div');
                div.innerHTML = template(insideQuestion('', 0, id), ++questionCount, id)
                document.querySelector('#groups').appendChild(div);
            });
            // Általános esemény, mivel a delete-group-okat dinamikusan adjuk hozzá
            document.addEventListener('click', (event) => {
                if (event.target && event.target.classList.contains('delete-group')) {
                    const group = document.querySelector(`#group_${event.target.dataset.groupId}`);
                    group.remove();
                }
            });

            document.addEventListener('click', (event) => {
                if (event.target && event.target.classList.contains('add-choice')) {
                    const id = event.target.parentElement.previousElementSibling.id.split('_')[1];
                    /* const id = event.target.parentElement.previousElementSibling.id; */
                    event.target.parentElement.insertAdjacentHTML("beforebegin", choiceTemplate(id, uuid.v4()));
                }
            });

            document.addEventListener('input', (event) => {
                if (event.target && event.target.classList.contains('form-select')) {
                    const value = event.target.value
                    const id = event.target.id.split('_')[1];
                    if (value === 'ONE_CHOICE') {
                        event.target.parentElement.outerHTML = insideQuestion(choiceFormTemplate(id, uuid.v4()), 1, id);
                    } else if (value === 'MULTIPLE_CHOICES') {
                        event.target.parentElement.outerHTML = insideQuestion(choiceFormTemplate(id, uuid.v4()), 2, id);
                    } else if (value === 'TEXTAREA') {
                        event.target.parentElement.outerHTML = insideQuestion('', 3, id);
                    } else {
                        event.target.parentElement.outerHTML = insideQuestion('', 0, id);
                    }
                }
            });
        </script>
    </div>
@endsection
