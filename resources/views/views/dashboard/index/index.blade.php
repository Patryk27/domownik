@extends('layouts.app.auth')

@section('content')
    {{-- @todo --}}

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                Witaj, świecie!
            </div>
        </div>

        <div class="panel-body">
            <a href="{{ route('finances.budgets.index') }}" class="btn btn-success">
                Otwórz zarządzanie budżetami
            </a>
        </div>
    </div>
@endsection