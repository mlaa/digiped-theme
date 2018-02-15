<div class="pa3">
  @if(is_category())
  @endif

  {{--
  @php(dynamic_sidebar('sidebar-primary'))
  --}}

  {{-- my digiped --}}
  <div class="my-digiped">
    <p>Drag &amp; drop cards on a collection.</p>
    @foreach([1, 2] as $id)
      <div class="collection-{{$id}} ba mv2 pa1">
        <h3 class="ma0">Collection {{$id}}</h3>
        <div class="relative cf"></div>
      </div>
    @endforeach
    <br><a class="link" href="#" onclick="javascript:jQuery('article').addClass('open');return false;">grow all cards</a><br>
    <br><a class="link" href="#" onclick="javascript:jQuery('article').removeClass('open');return false;">shrink all cards</a><br>
  </div>
</div>
