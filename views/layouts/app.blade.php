<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')
  <body @php(body_class( 'sans-serif' ))>
    @php(do_action('get_header'))
    @include('partials.header')
    <div class="wrap container" role="document">
      <div class="content cf flex">
        <main class="main order-2 ph1">
          <?php if(!is_single()) { ?>
          <div class="controls f7">
            @include('partials.filter-controls')
            <div class="grid relative">
              @yield('content')
            </div>
          </div>
          <?php $the_artifacts = false; ?>
         <?php } elseif ('digiped_keyword' === get_post_type()) { 
           // If we are in a single view we are in a keyword
           ?>
           <?php $the_artifacts = new WP_Query( 'post_type=digiped_artifact', 'tag='.get_the_title() ); ?>
           @yield('content', $the_artifacts);

        </main>
        @if (App\display_sidebar())
          <aside class="sidebar order-1 mw5 br">
            @include('partials.sidebar', $the_artifacts)
          </aside>
        @endif
      </div>
    </div>
    @php(do_action('get_footer'))
    @include('partials.footer')
    @php(wp_footer())
  </body>
</html>
