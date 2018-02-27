import Muuri from 'muuri';

export default class Grid {
  // Initialize a Muuri grid.
  constructor(elem) {
    var inst = this;

    inst.Muuri = new Muuri(elem, {
      dragEnabled: true,
      dragSort: () => {return window.dpGrids}, // TODO avoid global namespace
      dragSortInterval: 10,
    });

    // Since cards have different dimensions in different grids, redraw after dragging.
    inst.Muuri.on('dragReleaseEnd', () => {
      inst.Muuri.refreshItems();
      this.Muuri.layout(true);
    });

    // Update affected collection(s) on the backend.
    inst.Muuri.on('receive', inst.receive);

    window.dpGrids.push(this.Muuri); // TODO avoid global namespace

  }

  // Handler for Muuri 'receive' event.
  // TODO success/error handling/messaging
  receive(data) {
    var artifactID = $(data.item.getElement()).data('id');
    var fromCollectionID = $(data.fromGrid.getElement()).data('collection-id');
    var toCollectionID = $(data.toGrid.getElement()).data('collection-id');
    var removeArtifact = () => {
      var dfd = $.Deferred();
      if (fromCollectionID) {
        $.ajax({
          method: "DELETE",
          url: '/wp-json/digiped/v1/collections/' + fromCollectionID + '/artifact/' + artifactID,
        }).then(dfd.resolve);
      } else {
        dfd.resolve();
      }
      return dfd.promise();
    }
    var addArtifact = () => {
      if (toCollectionID) {
        $.ajax({
          method: "PUT",
          url:  '/wp-json/digiped/v1/collections/' + toCollectionID + '/artifact/' + artifactID,
        });
      }
    }

    if (fromCollectionID !== toCollectionID) {
      removeArtifact().done(addArtifact);
    }
  }
}
