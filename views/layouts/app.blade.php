<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')
  <body @php(body_class( 'sans-serif' ))>
    @php(do_action('get_header'))
    @include('partials.header')
    <div class="wrap container" role="document">
      <div class="content cf">
        <main class="main relative cf fr w-70 pa2 nr1 nl1">
          @yield('content')
        </main>
        @if (App\display_sidebar())
          <aside class="sidebar fl w-30">
            @include('partials.sidebar')
          </aside>
        @endif
      </div>
    </div>
    @php(do_action('get_footer'))
    @include('partials.footer')
    @php(wp_footer())
<script id="__bs_script__">//<![CDATA[
    document.write("<script async src='//HOST:3000/browser-sync/browser-sync-client.2.15.0.js'><\/script>".replace("HOST", location.hostname));
    //]]></script>
  </body>
</html>
