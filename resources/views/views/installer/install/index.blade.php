@extends('layouts.installer.main')

@section('content')
    <div class="text-center">
        <h4>
            {{ __('views/installer/install/index.messages')[0] }}
        </h4>

        <p style="margin:30px 0">
            <i class="fa fa-microchip fa-4x"></i>
        </p>

        <p>
            {!! __('views/installer/install/index.messages')[1] !!}
        </p>
    </div>
@endsection