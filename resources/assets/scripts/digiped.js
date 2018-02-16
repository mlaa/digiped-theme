import Muuri from 'muuri';

export default class DigiPed {
  constructor() {
    this.grids = [];

    // temporary debug helper
    $( 'aside a' ).on('click', this.redraw.bind(this));

    // main grid
    this.addGrid('main');

    // sidebar collections
    // 'this' scope is different, use 'inst'
    var inst = this;
    $('.my-digiped div > div').map((i, elem) => {
      inst.addGrid(elem);
    });
  }

  addGrid(elem, options = {}) {
    var inst = this;
    var defaultOptions = {
      dragEnabled: true,
      dragSort: () => {return inst.grids},
      dragSortInterval: 10,
    };

    Object.assign(options, defaultOptions);

    var m = new Muuri(elem, options);

    m.on('dragReleaseEnd', this.redraw.bind(this));

    this.grids.push(m);

    return m;
  }

  redraw() {
    this.grids.map((grid) => {
      grid.refreshItems(); // only necessary when changing item dimensions, not sorting
      grid.layout();
    });
  }
}
