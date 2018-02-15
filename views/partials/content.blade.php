<article @php(post_class())>
  <div class="relative">
    <div class="thumbnail dn">
        @if(has_post_thumbnail())
          @php(the_post_thumbnail([200, 150]))
        @else
          <img src="//via.placeholder.com/200x150">
        @endif
    </div>
    <div class="meta pv2 ph3">
      <h2 class="ma0 mb1 f6"><a href="@php(the_permalink())" class="link">@php(the_title())</a></h2>
      <div class="author f7 mb1 dn">
        <i class="fas fa-user"></i>
        @php(the_author())
      </div>
      <div class="tags f7 dn">
          <i class="fas fa-tag mr1 f7"></i>
          <ul class="list di ttu ma0 pa0 f7">
            @php(the_tags('<li class="dib">', '</li><li class="dib">', '</li>'))
          </ul>
      </div>
      <div class="excerpt f7 dn">
        @php(the_excerpt())
      </div>
      {{-- @include('partials/entry-meta') --}}
    </div>
    {{--
    <div class="entry-summary">
      @php(the_excerpt())
    </div>
    --}}
  </div>
</article>
