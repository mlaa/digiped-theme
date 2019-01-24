@extends('layouts.app')
@section('content')
<div @php(post_class('digi-keyword-page'))>
  <div class=" mt5">
    <div class="fl f3">Search for </div>
    <div class="fl f3">{!! get_search_form(false) !!}</div>
    <div class="fl f3">.</div>
  </div>

  </div>        
@endsection

