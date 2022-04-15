@extends('layouts.layout')

@section('title', 'Forms')

@section('content')
    <h1 class="ps-3">Forms</h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-center table-hover">
            <thead class=" table-light">
                <tr>
                    <th class="text-left" style="width: 90%">Title</th>
                    <th class="text-center" style="width: 10%">Questions</th>
                    <th style="width: 10%"></th>
                </tr>
            </thead>
            <tbody class="">
                @foreach ($forms as $form)
                    <tr class="align-middle justify-center">
                        <td class="align-middle">
                            <div>{{ $form->title }}</div>
                            <div class="text-secondary">{{ $form->created_at }}</div>
                        </td>
                        <td class="text-center align-middle">
                            <span class="text-dark fs-6">
                                {{ $form->questions()->count() }}
                            </span>
                        </td>
                        <td style="column-count: 2;">
                            <a type="submit" class="btn btn-outline-secondary"
                                href="{{ route('forms.show', $form->id) }}">
                                <i class="fa-solid fa-angles-right fa-fw"></i>
                            </a>
                            <form method="POST" action="{{route('forms.destroy', $form->id)}}" class="d-flex align-middle justify-center">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
