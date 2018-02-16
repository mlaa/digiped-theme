import Muuri from 'muuri';

export default class DigiPed {
  constructor() {
    this.grids = [];

    // temporary debug helper
    $( 'aside a' ).on('click', this.redraw.bind(this));

    // sidebar collections
    //$('.my-digiped div > div').each(this.init_collection);

    // main grid
    var m = new Muuri('main', {
      dragEnabled: true,
      dragSort: this.grids.bind(this),
      dragSortInterval: 10,
    })
    .on('dragReleaseEnd', this.redraw.bind(this));

    this.grids.push(m);
  }

  get grids() {
    return this.constructor._grids;
  }

  set grids(value) {
    this.constructor._grids = value;
    this.redraw.bind(this.constructor);
  }

  //  // TODO _grids grids
//  init_collection(el) {
//    console.log(el);
//    var muuri = new Muuri(el, {
//      items: 'article',
//      dragEnabled: true,
//      dragSort: this.grids,
//      dragSortInterval: 10,
//    });
//
//    muuri.on('dragReleaseEnd', this.redraw);
//
//    this._grids.push(muuri);
//  }

  redraw() {
    this.grids.forEach(function(grid) {
      grid.refreshItems(); // only necessary when changing item dimensions, not sorting
      grid.layout();
    });
  }
}
