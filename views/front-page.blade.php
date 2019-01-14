@extends('layouts.app')
here
@php($the_query = new WP_Query( 'post_type=digiped_artifact&posts_per_page=99' ))

@section('content')
  @while($the_query->have_posts()) @php($the_query->the_post())
    @include('partials.content')
  @endwhile
@endsection
