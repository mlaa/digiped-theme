<div 
@php(post_class('keyword'))>
  <div class="keyword-wrapper">
    <header>
      <h2 class="sub-title"><div class="keyword">Keyword</div></h1>
      <h1 class="title">{{ get_the_title() }}</h1>
      @include('partials/entry-meta-keyword')
      <h2 class="sub-title"><div class="statement">Curatorial Statement</div></h2>
    </header>
    <div>
      <?php the_content(); ?>
      <div id="read-more">Read Less</div>
    </div>  
    <header>
      <h2 class="sub-title"><div class="artifacts">Artifacts</div></h2>
    </header>
    <div class="grid relative">
      <div class="entry-content"> 
       
        @foreach($the_artifacts as $artifact)
        @include('partials.content-digiped-keyword-panel', $artifact)
        @endforeach
      </div>
    </div>
  </div>
  @php(comments_template('/partials/comments.blade.php'))
</div>
