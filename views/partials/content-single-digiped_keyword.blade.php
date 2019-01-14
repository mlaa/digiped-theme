<?php $the_artifacts = new WP_Query( 'post_type=digiped_artifact', 'post_parent='.get_the_ID() ); ?>
<div 
@php(post_class('digi-keyword-page'))>
  <div class="keyword-wrapper">
    <header>
      <h2 class="sub-title"><div class="keyword">Keyword</div></h1>
      <h1 class="title">{{ get_the_title() }}</h1>
      @include('partials/entry-meta-keyword')
      <h2 class="sub-title"><div class="statement">Curatorial Statement</div></h2>
    </header>
    <div class="the-keyword-content read-more-show">
      <?php the_content(); ?>
    </div>  
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
      
        if ( $the_artifacts->have_posts() ) {
            while ( $the_artifacts->have_posts() ) {
                $the_artifacts->the_post();
                ?> 
                @include('partials.content')
                <?php 
            }
            wp_reset_postdata();
        }
      ?>
    </div>
    <header>
        <h2 class="sub-title"><div class="artifacts">Related Materials</div></h2>
    </header>  
    <header>
        <h2 class="sub-title"><div class="artifacts">Works Cited</div></h2>
    </header>  
  </div>
  @php(comments_template('/partials/comments.blade.php'))
</div>
