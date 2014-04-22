@extends('base')

@section('title')
  {{{ $page_title ? $page_title : Parameter::get(Parameter::$UNIT_LONG_NAME) }}}
@stop

@section('forward_links')
  {{-- Link to management --}}
  @if ($can_edit)
    <p>
      <a href='{{ $edit_url }}'>
        Modifier cette page
      </a>
    </p>
  @endif
@stop

@section('content')
  <div class="row page_body">
    <h1>{{{ $page_title }}}</h1>
    {{ $page_body }}
  </div>
@stop