@extends('admin.layout.admin')

@section('content')

    <div class="row" style="width: 100%;">
        <table class="highlight col s12 m10 offset-m1 responsive-table">
            <thead>
                <th>Назва</th>
                <th>Name</th>
                <th>Short Name</th>
                <th>Гра</th>
                <th></th>
            </thead>
            <tbody>
            @foreach($dlc as $item)
                <tr @if(!$item->active)class="grey darken-2 black-text" @endif>
                    <td>@lang('dlc_list.'.$item->name)</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->short_name }}</td>
                    <td>@lang('general.'.$item->game)</td>
                    <td style="white-space: nowrap" class="right-align">
                        <a href="{{ route('dlc') }}/delete/{{ $item->id }}" class="mdc-icon-button material-icons notranslate"
                           onclick="return confirm('Видалити DLC?')">delete</a>
                        <a href="{{ route('dlc') }}/toggle/{{ $item->id }}" class="mdc-icon-button material-icons notranslate">
                            visibility_{{ $item->active ? 'off' : 'on' }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="fixed-action-btn">
        <a class="mdc-fab tooltipped" data-tooltip="Додати нове DLC" href="{{ route('dlc') }}/add">
            <span class="material-icons mdc-fab__icon">add</span>
        </a>
    </div>

@endsection