import Muuri from 'muuri';

export default class DigiPed {

  // Set up dragging & bind events.
  constructor() {
    // jQuery collection of grid containers.
    this.$grids = $('main, .my-digiped div > div');

    // Array of initialized Muuri grid instances.
    this.mgrids = [];

    // Initialize grids.
    for (var i in this.$grids.toArray()) {
      this.initGrid(this.$grids[i]);
    }

    // temporary debug helper
    $( 'aside a' ).on('click', this.redraw.bind(this));
  }

  // Initialize a new grid with Muuri.
  initGrid(elem) {
    var inst = this;

    var m = new Muuri(elem, {
      dragEnabled: true,
      dragSort: () => {return inst.mgrids},
      dragSortInterval: 10,
    });

    // Since cards have different dimensions in different grids, redraw after dragging.
    m.on('dragReleaseEnd', this.redraw.bind(this));

    // Update affected collection(s) on the backend.
    // TODO this actually triggers before releasing mouse, which works, but see if we can wait until actually dropped
    m.on('receive', this.receive);

    this.mgrids.push(m);
  }

  // Resize grids to fit cards.
  redraw() {
    this.mgrids.map((m) => {
      m.refreshItems();
      m.layout(true);
    });
  }

  // Handler for Muuri 'receive' event.
  // TODO success/error handling/messaging
  receive(data) {
    var artifactID = $(data.item.getElement()).data('post-id');
    var fromCollectionID = $(data.fromGrid.getElement()).data('collection-id');
    var toCollectionID = $(data.toGrid.getElement()).data('collection-id');

    // Remove from origin collection.
    if (fromCollectionID) {
      $.ajax({
        method: "DELETE",
        url: '/TODO/collection/' + fromCollectionID + '/artifact/' + artifactID,
      });
    }

    // Add to destination collection.
    if (toCollectionID) {
      $.ajax({
        method: "PUT",
        url: '/TODO/collection/' + toCollectionID + '/artifact/' + artifactID,
      });
    }
  }
}
