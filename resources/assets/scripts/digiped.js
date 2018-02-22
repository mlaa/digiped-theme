/* global wpApiSettings */

import Muuri from 'muuri';

export default class DigiPed {
  constructor() {
    var inst = this;

    // This flag prevents receive() from updating the backend until initCollections() has finished.
    inst.loaded = false;

    // Add WP Nonce header to all ajax requests.
    $.ajaxSetup({
      headers: {'X-WP-Nonce': wpApiSettings.nonce},
    });

    // jQuery collection of grid containers.
    inst.$grids = $('main .grid, .my-digiped div > div');

    // Array of Muuri grid instances.
    inst.mgrids = [];

    // Initialize grids.
    inst.initGrid($('main .grid')[0]);

    // Initialize collections.
    inst.initCollections()
      .then(inst.redraw.bind(inst));

    // Initialize controls/filters.
    inst.initCollectionControls();
    inst.initFilterMenu();
    inst.initTagFilter();

    // temporary debug helper
    $( 'aside a' ).on('click', inst.redraw.bind(inst));
  }

  // Bind controls to manage collections.
  initCollectionControls() {
    var inst = this;

    $('.create-collection').on('click', (e) => {
      var name = prompt('What should this collection be named?');

      if (name) {
        $.ajax({
          method: "POST",
          url: '/wp-json/digiped/v1/collections',
          data: {name: name},
        })
          .done(inst.createCollection)
      }

      e.preventDefault();
    });
  }

  // Create a new collection element & initialize its grid.
  createCollection(data) {
    var container = $('<div class="ba mv2 pa1 collection">')
      .append($('<h3 class="ma0">' + data.name + '</h3>'))
      .append($('<div class="relative cf" data-collection-id="' + data.id + '"></div>'));
    var clone = container.clone().appendTo('.my-digiped .collections');

    this.initGridEvents(this.initGrid(clone.find('.relative')[0]));

    return clone;
  }

  // Load & initialize all collections.
  initCollections() {
    var inst = this;
    var moveItems = (collections) => {
      var originGrid = inst.mgrids[0];
      for (var i in collections) {
        // Create this collection element.
        inst.createCollection(collections[i]);

        // TODO this doesn't handle the case where cards in a collection may not be on the page due to filters, then they're just missing from the page entirely
        // ...is that a feature? if you filter for a certain tag would you only want to see items in your collections matching that tag? or does the filter only apply to main grid
        for (var j in collections[i].artifacts) {
          var artifactItem = $('.post-' + collections[i].artifacts[j])[0];
          var destinationGrid = false;

          // Determine destination grid by collection ID.
          for (var k in inst.mgrids) {
            if (collections[i].id === $(inst.mgrids[k].getElement()).data('collection-id')) {
              destinationGrid = inst.mgrids[k];
            }
          }

          // Move item.
          if ( artifactItem && destinationGrid ) {
            originGrid.send(artifactItem, destinationGrid, -1, {
              layoutSender: 'instant',
              layoutReceiver: 'instant',
            });
          }
        }
      }

      // Flipping this effectively enables the receive() handler for future events.
      inst.loaded = true;
    }

    return $.ajax({url: '/wp-json/digiped/v1/collections'}).then(moveItems);
  }

  // Initialize filter menu to hide/show controls for each filter.
  initFilterMenu() {
  }

  // Add all tags to filter & bind events.
  initTagFilter() {
    var allTags = [];

    // Main grid only.
    this.mgrids[0].getItems().map((item) => {
      $(item.getElement()).data('post-tags').map((tag) => {
        var tagHTML = '<li class="dib"><a class="link db ba br2 ma1 pa1 dim dark-gray" href="/tag/' + tag.slug + '">' + tag.name + '</a></li>'

        if (-1 === allTags.indexOf(tagHTML)) {
          allTags.push(tagHTML);
        }
      });
    });

    $('.controls .tags').html(() => {
      return allTags.join('');
    });
  }

  // Initialize a Muuri grid.
  initGrid(elem) {
    var inst = this;

    var m = new Muuri(elem, {
      dragEnabled: true,
      dragSort: () => {return inst.mgrids},
      dragSortInterval: 10,
    });

    this.mgrids.push(m);

    // Return new grid for use in initGridEvents().
    return m;
  }

  // Bind Muuri event handlers.
  initGridEvents(grid) {
    var inst = this;

    // Since cards have different dimensions in different grids, redraw after dragging.
    grid.on('dragReleaseEnd', inst.redraw.bind(inst));

    // Update affected collection(s) on the backend.
    grid.on('receive', (data) => {
      if (inst.loaded) {
        inst.receive(data);
      }
    });
  }

  // Resize grids to fit cards.
  redraw() {
    this.mgrids.map((grid) => {
      grid.refreshItems();
      grid.layout(true);
    });
  }

  // Handler for Muuri 'receive' event.
  // TODO success/error handling/messaging
  receive(data) {
    var artifactID = $(data.item.getElement()).data('post-id');
    var fromCollectionID = $(data.fromGrid.getElement()).data('collection-id');
    var toCollectionID = $(data.toGrid.getElement()).data('collection-id');
    var promise = new Promise((resolve) => {resolve()});
    var removeArtifact = () => {
      if (fromCollectionID) {
        promise = $.ajax({
          method: "DELETE",
          url: '/wp-json/digiped/v1/collections/' + fromCollectionID + '/artifact/' + artifactID,
        });
      }
      return promise;
    }
    var addArtifact = () => {
      if (toCollectionID) {
        promise = $.ajax({
          method: "PUT",
          url:  '/wp-json/digiped/v1/collections/' + toCollectionID + '/artifact/' + artifactID,
        });
      }
      return promise;
    }

    if (fromCollectionID !== toCollectionID) {
      removeArtifact().done(addArtifact);
    }
  }
}
