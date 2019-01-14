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
      $types = array();                 
      if( !empty( $wp_query->posts ) ) {
          foreach ($wp_query->posts as $p ) {
            if(empty( $types[$p->post_type] )) {
              //initiate with zero to allow for integer counting.
              $types[$p->post_type] =  0;
            }
            $types[$p->post_type]++;
          }
        $keyword = $types['digiped_keyword']?:0;
        $artifacts = $types['digiped_artifact']?:0;
        echo  "$keyword keyword and $artifacts artifacts match your search.";
      }
    ?>
    </div>
    <br style="clear:both"/>
    <br style="clear:both"/>
    <div><code>In an effort to get the most applicable results to the top, I'm thinking about searching only in Title, Tags, and Keyword. With a secondary full body text search that is deduped and appended to bottom of results. @JB #Discuss</code></div>
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

