@extends('layouts.app')

@php($the_query = new WP_Query( 'posts_per_page=999' ))

@section('content')
  @while($the_query->have_posts()) @php($the_query->the_post())
    @include('partials.content')
  @endwhile
@endsection
