<!doctype html>
<html lang="hu">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Dinamikus form példa</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js" integrity="sha512-UNM1njAgOFUa74Z0bADwAq8gbTcqZC8Ej4xPSzpnh0l6KMevwvkBvbldF9uR++qKeJ+MOZHRjV1HZjoRvjDfNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body class="container pt-3">
    {{-- Validator hibáinak kilogolása --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

	<form action="{{ route('example-form-process') }}" method="POST">
		@csrf
        <h2>Required</h2>
		<div class="card p-3 mt-2 mb-3">
			<div class="form-group mb-3">
				<label for="title">Title</label>
				<input type="text" class="form-control" id="title" name="title" placeholder="Example Title">
			</div>
			<div class="form-group mb-3">
				<label for="exp-date">Expiration date</label>
				<input type="datetime-local" class="form-control" id="exp-date" name="exp-date">
			</div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="login-req" name="login-req">
                <label class="form-check-label" for="login-req">Login Required</label>
            </div>
		</div>

        <h2>Questions</h2>
		<div class="card p-3 mt-2 mb-3">
			<div id="groups">
                {{-- Az előző group-ok újrarenderelése, mivel ha a validator visszadobja a formot, az egy új oldalt ad, vagyis a js-el hozzáadott elemek elvesznek --}}
                @if (old('groups') !== null)
                    @foreach (old('groups') as $group)
                        @php
                            $uuid = Str::uuid();
                        @endphp
                        <div class="mb-3" id="group_{{ $uuid }}">
                            <h4>Question...</h4>
                            <div class="card p-3 mt-2 mb-3">
                                <div class="form-group mb-3">
                                    <label for="textinput_{{ $uuid }}">Beviteli mező</label>
                                    <input type="text" class="form-control" id="textinput_{{ $uuid }}" name="groups[{{ $uuid }}][textinput]" placeholder="Csoport beviteli mezeje" value="{{ array_key_exists('textinput', $group) ? $group['textinput'] : '' }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="selector_{{ $uuid }}">Választás</label>
                                    <select id="selector_{{ $uuid }}" name="groups[{{ $uuid }}][selector]" class="form-select">
                                        <option value="" disabled selected>Válassz valamit</option>
                                        <option value="one" @if(array_key_exists('selector', $group) && $group['selector'] === 'one') selected @endif>One</option>
                                        <option value="two" @if(array_key_exists('selector', $group) && $group['selector'] === 'two') selected @endif>Two</option>
                                        <option value="three" @if(array_key_exists('selector', $group) && $group['selector'] === 'three') selected @endif>Three</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="delete-group btn btn-danger" data-group-id="{{ $uuid }}">Delete Question</button>
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
		// Template a group-hoz
		const template = `
			<div class="mb-3" id="group_#ID#">
				<h4>Question...</h4>
				<div class="card p-3 mt-2 mb-3">
					<div class="form-group mb-3">
						<label for="textinput_#ID#">Beviteli mező</label>
						<input type="text" class="form-control" id="textinput_#ID#" name="groups[#ID#][textinput]" placeholder="Csoport beviteli mezeje">
					</div>
					<div class="form-group mb-3">
						<label for="selector_#ID#">Választás</label>
						<select id="selector_#ID#" name="groups[#ID#][selector]" class="form-select">
							<option value="" disabled selected>Válassz valamit</option>
							<option value="one">One</option>
							<option value="two">Two</option>
							<option value="three">Three</option>
						</select>
					</div>
					<div class="d-flex justify-content-center">
						<button type="button" class="delete-group btn btn-danger" data-group-id="#ID#">Delete Question</button>
					</div>
				</div>
			</div>
		`;

		const groups = document.querySelector('#groups');
		const addGroup = document.querySelector('button#add-group');
		addGroup.addEventListener('click', (event) => {
            let group = document.createElement("div");
            group.innerHTML = template.replaceAll('#ID#', uuid.v4());
			groups.appendChild(group);
		});
        // Általános esemény, mivel a delete-group-okat dinamikusan adjuk hozzá
		document.addEventListener('click', (event) => {
			if(event.target && event.target.classList.contains('delete-group')) {
				const group = document.querySelector(`#group_${event.target.dataset.groupId}`);
				group.remove();
			}
		});
	</script>
</body>
</html>
