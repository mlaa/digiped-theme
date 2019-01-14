import Muuri from 'muuri';

export default class Grid {
  // Initialize a Muuri grid.
  constructor(elem) {
    let inst = this;

    inst.Muuri = new Muuri(elem, {
      dragEnabled: true,
      dragSort: () => {
        let allowedArray = [];
        window.dpGrids.forEach(function (grid) {
          if (!$(grid.getElement()).hasClass('main-grid')) {
            allowedArray.push(grid);
          }
        });
        //return allowedArray;
        return allowedArray
      }, // TODO avoid global namespace
      dragSortInterval: 10,
      dragStartPredicate: {
        distance: 100,
        delay: 50,
      },
      layout: {
        fillGaps: false,
        horizontal: false,
        alignRight: false,
        alignBottom: false,
        rounding: false,
      },
      layoutOnResize: 100,
      layoutOnInit: true,
      layoutDuration: 300,
      layoutEasing: 'ease',
    });

    // Since cards have different dimensions in different grids, redraw after dragging.
    inst.Muuri.on('dragReleaseEnd', () => {
      inst.Muuri.refreshItems();
      inst.Muuri.layout(true);
    });

    // Update affected collection(s) on the backend.
    inst.Muuri.on('receive', function (data) {
      inst.receive(data);
      inst.Muuri.refreshItems();
      inst.Muuri.layout(true);
    });

    // TODO avoid global namespace
    window.dpGrids.push(inst.Muuri);
  }

  // Handler for Muuri 'receive' event.
  // TODO success/error handling/messaging
  receive(data) {
    const artifactID = $(data.item.getElement()).data('id');
    const fromCollectionID = $(data.fromGrid.getElement()).data('collection-id');
    const toCollectionID = $(data.toGrid.getElement()).data('collection-id');

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
          url: '/wp-json/digiped/v1/collections/' + toCollectionID + '/artifact/' + artifactID,
        }).done(function () {
          // put the card back into the main grid, and only the main grid
          if (data.fromGrid._id === 1) {
            let clone = data.item.getElement().cloneNode(true);
            data.fromGrid.add(clone, {
              index: data.fromIndex,
            });
            data.fromGrid.show(clone);
          }
        });
      }
    }

    if (fromCollectionID !== toCollectionID) {
      removeArtifact().done(addArtifact);
    }
  }
}
