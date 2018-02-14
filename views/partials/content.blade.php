<article @php(post_class( 'item w4 fl ba ma2 bg-white black' ))>
  <div class="item-content">
    <img src="https://place.cat/c/250/250">
    <header class="ph3">
      <h2 class="entry-title f5"><a href="{{ get_permalink() }}" class="link">{{ get_the_title() }}</a></h2>
      @php(the_tags())
      {{-- @include('partials/entry-meta') --}}
    </header>
    {{--
    <div class="entry-summary">
      @php(the_excerpt())
    </div>
    --}}
  </div>
</article>
