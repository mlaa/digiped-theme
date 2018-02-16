import Muuri from 'muuri';

export default class DigiPed {
  constructor() {
    var mainGrid = $('main');
    var collectionGrids = $('.my-digiped div > div');
    this.grids = [];

    this.setupGrid(mainGrid[0]);
    for (var i in collectionGrids.toArray()) {
      this.setupGrid(collectionGrids[i]);
    }

    $( 'aside a' ).on('click', this.redraw.bind(this)); // temporary debug helper
  }

  setupGrid(elem, options = {}) {
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
