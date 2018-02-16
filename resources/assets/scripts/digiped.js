import Muuri from 'muuri';

export function init() {
  // Multigrid drag sorting.
  var grids = [];

  // eslint-disable-next-line
  function getAllGrids(item) {
    return grids;
  }

  var recalc = function() {
    grids.forEach(function(muuri) {
      muuri.refreshItems(); // only necessary when changing item dimensions, not sorting
      muuri.layout();
    });
  }

  // temporary debug helper
  $( 'aside a' ).on('click', recalc);

  // sidebar collections
  $('.my-digiped div > div').each(function(){
    var muuri = new Muuri(this, {
      items: 'article',
      dragEnabled: true,
      dragSort: getAllGrids,
      dragSortInterval: 10,
    })
    .on('dragReleaseEnd', recalc);
    grids.push(muuri);
  });

  // main grid
  var mmuuri = new Muuri('main', {
    dragEnabled: true,
    dragSort: getAllGrids,
    dragSortInterval: 10,
  })
  .on('dragReleaseEnd', recalc);

  grids.push(mmuuri);
}
