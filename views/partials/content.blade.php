<article @php(post_class())>
  <div class="relative">
    @if(has_post_thumbnail())
      @php(the_post_thumbnail([200, 150]))
    @else
      <img src="//via.placeholder.com/200x150">
    @endif
    <header class="pv2 ph3">
      <h2 class="entry-title ma0 mb1 f6"><a href="@php(the_permalink())" class="link">@php(the_title())</a></h2>
      <i class="fas fa-tag mr1 f7"></i>
      <ul class="list di ttu ma0 pa0 f7">
        @php(the_tags('<li class="dib">', '</li><li class="dib">', '</li>'))
      </ul>
      {{-- @include('partials/entry-meta') --}}
    </header>
    {{--
    <div class="entry-summary">
      @php(the_excerpt())
    </div>
    --}}
  </div>
</article>
