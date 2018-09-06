@extends('admin.layout.admin')

@section('content')

    @if($errors->any())
        <div class="row" style="width: 100%;">
            <div class="col s12 m10 offset-m1">
                @include('admin.warning')
            </div>
        </div>
    @endif
    @if(session()->get('success'))
        <div class="row" style="width: 100%;">
            <div class="col s12 m10 offset-m1">
                @include('admin.success')
            </div>
        </div>
    @endif

    <div class="row" style="width: 100%;">
        <table class="highlight col s12 m10 offset-m1 responsive-table">
            <thead>
                <th></th>
                <th>Назва</th>
                <th>Локаль</th>
                <th></th>
            </thead>
            <tbody>
            @foreach($languages as $item)
                <tr @if(!$item->active)class="yellow black-text" @endif>
                    <td><img src="{{ asset('assets/img/langs/'.$item->locale.'.png') }}" style="width: 32px; height: 32px;"></td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->locale }}</td>
                    <td style="white-space: nowrap" class="right-align">
                        <a href="{{ route('languages') }}/delete/{{ $item->id }}" class="mdc-button mdc-ripple mdc-button--raised red white-text"
                           onclick="return confirm('Видалити мову?')">
                            <i class="material-icons mdc-button__icon notranslate">delete</i>
                        </a>
                        <a href="{{ route('languages') }}/toggle/{{ $item->id }}" class="mdc-button mdc-ripple mdc-button--raised blue">
                            <i class="material-icons mdc-button__icon notranslate">visibility_{{ $item->active ? 'off' : 'on' }}</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="fixed-action-btn tooltipped" data-tooltip="Додати мову">
        <a class="mdc-fab mdc-ripple" href="{{ route('languages') }}/add">
            <span class="material-icons mdc-fab__icon">add</span>
        </a>
    </div>

@endsection