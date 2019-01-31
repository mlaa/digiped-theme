@extends('layouts.app')

@section('content')
<div @php(post_class('digi-keyword-page'))>
  <div class=" mt5">
    <div class="fl f3">Search results for </div>
    <div class="fl f3">{!! get_search_form(false) !!}</div>
    <div class="fl f3">.</div>

    <br style="clear:both"/>
    <div class="fl f6 mt1">
    <?php
      global $wp_query;
      $types = array('keyword'=>0, 'artifact'=>0);                 
      if( !empty( $wp_query->posts ) ) {
          foreach ($wp_query->posts as $p ) {
            $genre = wp_get_post_terms($p->ID, 'genre');
            if($genre[0]->slug == "curation-statement") {
              $types['keyword']++;
            } else{
              $types['artifact']++;
            }
          }
        $keyword = $types['keyword'];
        $artifacts = $types['artifact'];
        echo  "$keyword keyword and $artifacts artifacts match your search.";
      }
    ?>
    </div>
    <br style="clear:both"/>
    <br style="clear:both"/>
     </div>
  <br style="clear:both"/>
  <div class="cf mt3 keyword-wrapper">
    <div class="controls f7">
      @include('partials.filter-controls')
    </div>
    <div class="main-grid grid relative">

        @while (have_posts()) @php(the_post())
        @include('partials.content')
        @endwhile
     
      </div>
    </div>
  </div>        
@endsection

