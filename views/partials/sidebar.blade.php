<div class="pa3">
  @if(is_category())
  @endif

  {{--
  @php(dynamic_sidebar('sidebar-primary'))
  --}}

  {{-- my digiped --}}
  <div class="my-digiped">
    <p>Drop cards here.</p>
    @foreach([1, 2] as $id)
      <div class="collection-{{$id}} ba mv2 pa1">
        <h3 class="ma0">Collection {{$id}}</h3>
        <div class="relative cf"></div>
      </div>
    @endforeach
  </div>
</div>
