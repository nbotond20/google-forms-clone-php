@extends('layouts.layout')

@section('title', 'Feladatok')

@section('content')
    <h1 class="ps-3">Feladatok</h1>
    <hr />
    <div class="table-responsive">
        <table class="table align-left table-hover">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 50%">Title</th>
                    <th style="width: 40%">Questions</th>
                    <th style="width: 10%"></th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr class="">
                    <td>
                        <div>Title</div>
                        <div class="text-secondary">2001.12.30</div>
                    </td>
                    <td>
                        <span class="text-dark fs-6">
                            1
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline-secondary">
                            <i class="fa-solid fa-angles-right fa-fw"></i>
                        </button>
                    </td>
                </tr>
                <tr class="">
                    <td>
                        <div>Title</div>
                        <div class="text-secondary">2001.12.30</div>
                    </td>
                    <td>
                        <span class="text-dark fs-6">
                            1
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline-secondary">
                            <i class="fa-solid fa-angles-right fa-fw"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
