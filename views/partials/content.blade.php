<article @php(post_class( 'item w-25 fl ba ma2 bg-white black' ))>
  <div class="item-content">
    <img src="https://place.cat/c/400/400">
    <header class="pa2">
      <h2 class="entry-title"><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h2>
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
