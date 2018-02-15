<article @php(post_class( 'item db absolute z-1 fl ba ma2 bg-white black' ))>
  <div class="item-content relative w-100 h-100">
    <img src="https://place.cat/c/200/200">
    <header class="ph2 nr1 nl1">
      <h2 class="entry-title"><a href="{{ get_permalink() }}" class="link">{{ get_the_title() }}</a></h2>
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
