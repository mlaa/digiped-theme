import Muuri from 'muuri';

export default class DigiPed {
  constructor() {
    // Multigrid drag sorting.
    var grids = [];

    // temporary debug helper
    $( 'aside a' ).on('click', DigiPed.redraw);

    // sidebar collections
    $('.my-digiped div > div').each(function(){
      var muuri = new Muuri(this, {
        items: 'article',
        dragEnabled: true,
        dragSort: DigiPed.getAllGrids,
        dragSortInterval: 10,
      })
      .on('dragReleaseEnd', DigiPed.redraw);
      grids.push(muuri);
    });

    // main grid
    var mmuuri = new Muuri('main', {
      dragEnabled: true,
      dragSort: DigiPed.getAllGrids,
      dragSortInterval: 10,
    })
    .on('dragReleaseEnd', DigiPed.redraw);

    DigiPed.grids.push(mmuuri);
  }

  getAllGrids() {
    return DigiPed.grids;
  }

  redraw() {
    DigiPed.grids.forEach(function(muuri) {
      muuri.refreshItems(); // only necessary when changing item dimensions, not sorting
      muuri.layout();
    });
  }
}
