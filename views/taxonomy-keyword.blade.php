@extends('layouts.app')

@section('content')
  {{-- @include('partials.page-header') --}}
  @if (!have_posts())
    <div class="alert alert-warning">
      {{ __('Sorry, no results were found.', 'sage') }}
    </div>
    {!! get_search_form(false) !!}
  @endif
  <?php 
    $args = array(
      'post_type' => 'artifact',
      'showposts' => 1,
      'tax_query' => array(
          'relation' => 'AND',
          array(
            'taxonomy' => 'keyword',
            'field'    => 'term_id',
            'terms'    => get_queried_object()->term_id
          ),
          array(
            'taxonomy' => 'genre',
            'field'    => 'slug',
            'terms'    => 'curation-statement'
          )
      )
    );// end args
    query_posts($args);
  ?>
        
        <div @php(post_class('digi-keyword-page'))>
          <div class="keyword-wrapper">
            @while (have_posts()) @php(the_post())
            <header>
              <h2 class="sub-title"><div class="keyword">Keyword</div></h1>
              <h1 class="title">{{ get_the_title() }}</h1>
              @include('partials/entry-meta-keyword')
              <h2 class="sub-title"><div class="statement">Curatorial Statement</div></h2>
            </header>
            <div class="the-keyword-content read-more-show">
              <?php the_content(); ?>
            </div>  
            <?php $currStatementID = get_the_ID(); ?>
            @endwhile
            <p class="f6"><a href="#" class="read-more-keyword-description link mid-gray title">Read More...</a></p>
            <header>
              <h2 class="sub-title pb5">
                <div class="artifacts fl">Artifacts</div>
                <div class="ml3 fl"><div class="pv2 normal f60 black fl">View:</div><a class="view-list fl" href="#"><i class="green material-icons">view_list</i></a>
                  <a class="view-module fl" href="#"><i class="green material-icons">view_module</i></a></div>
              </h2>
              <br class="clear">
            </header>  
        
            <div class="main-grid grid relative">
              <?php 
              wp_reset_query(); 
              global $wp_query;
              $args = array_merge( $wp_query->query_vars, array( 'post__not_in'  => array($currStatementID)) );
              query_posts( $args );
              ?>
              @while (have_posts()) @php(the_post())
              @include('partials.content')
              @endwhile
            </div>
            <header>
              <h2 class="sub-title"><div class="artifacts">Related Materials</div></h2>
            </header>  
            <header>
              <h2 class="sub-title"><div class="artifacts">Works Cited</div></h2>
            </header>  
        </div>  
        
   

  {{-- {!! get_the_posts_navigation() !!} --}}
@endsection
