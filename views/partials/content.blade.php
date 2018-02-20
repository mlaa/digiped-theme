{{-- TODO only include tag name, not other term data --}}
<article @php(post_class('absolute z-1 fl ba bg-white black ma2')) data-post-id="@php(the_id())" data-post-tags="{{json_encode(get_the_tags())}}">
  <div class="flex flex-column">
    <div class="thumbnail">
        @if(has_post_thumbnail())
          @php(the_post_thumbnail([200, 150], ['class' => 'db']))
        @else
          <img class="db" src="//via.placeholder.com/200x150">
        @endif
    </div>
    <div class="meta pv2 ph3">
      <h2 class="ma0 mb2 f6"><a href="@php(the_permalink())" class="link dim mid-gray">@php(the_title())</a></h2>
      <div class="author f7 mb2">
        <i class="fa fa-user"></i>
        @php(the_author())
      </div>
      <div class="tags f7">
          <i class="fa fa-tag mr1 f7"></i>
          <ul class="list di ttu ma0 pa0 f7">
            @php(the_tags('<li class="dib">', '</li><li class="dib">', '</li>'))
          </ul>
      </div>
      <div class="excerpt f7">
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
