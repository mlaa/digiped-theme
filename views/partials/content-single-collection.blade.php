<?php 
$artifacts = array();
foreach(get_comments('post_id='.get_the_ID()) as $child) {
  $artifacts[] = $child->comment_content;
}
$the_artifacts = new WP_Query( array( 'post_type' => 'artifact', 'post__in' => $artifacts )); ?>
<div 
@php(post_class('digi-keyword-page'))>
  <div class="keyword-wrapper">
    <header>
      <h2 class="sub-title"><div class="keyword">Collection</div></h1>
      <h1 class="title">{{ get_the_title() }}</h1>
      @include('partials/entry-meta-keyword')
    </header>
    <div class="the-keyword-content">
      <?php the_content(); ?><br/>
    </div>  
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
        <h2 class="sub-title"><div class="artifacts">Works Cited</div></h2>
    </header>  
  </div>
</div>
