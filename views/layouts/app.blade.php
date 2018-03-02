<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')
  <body @php(body_class( 'sans-serif' ))>
    @php(do_action('get_header'))
    @include('partials.header')
    <div class="wrap container" role="document">
      <div class="content cf flex">
        <main class="main order-2 ph1">
          <div class="controls f7">
            @include('partials.filter-controls')
          </div>
          <div class="grid relative">
            @yield('content')
          </div>
        </main>
        @if (App\display_sidebar())
          <aside class="sidebar order-1 mw5 br">
            @include('partials.sidebar')
          </aside>
        @endif
      </div>
    </div>
    @php(do_action('get_footer'))
    @include('partials.footer')
    @php(wp_footer())
  </body>
</html>
