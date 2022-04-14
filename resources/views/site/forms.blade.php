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
                    <tr class="">
                        <td>
                            <div>{{ $form->title }}</div>
                            <div class="text-secondary">{{ $form->created_at }}</div>
                        </td>
                        <td class="text-center align-middle">
                            <span class="text-dark fs-6">
                                {{ $form->questions()->count() }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-outline-secondary" href="{{ route('forms.show', $form->id) }}">
                                <i class="fa-solid fa-angles-right fa-fw"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
