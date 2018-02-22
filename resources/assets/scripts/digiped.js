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

    // Array of Muuri grid instances.
    inst.grids = [];

    // Initialize grids.
    inst.initGrid($('main .grid')[0]);

    // Initialize collections.
    inst.initCollections();

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
          .done(inst.createCollection);
      }

      e.preventDefault();
    });
  }

  // Create a new collection element.
  // Use initGrid() after creating.
  createCollection(data) {
    var container = $('<div class="ba mv2 pa1 collection">')
      .append($('<h3 class="ma0">' + data.name + '</h3>'))
      .append($('<div class="relative cf" data-collection-id="' + data.id + '"></div>'));
    var clone = container.clone().appendTo('.my-digiped .collections');

    return clone.find('.relative')[0];
  }

  // Load & initialize all collections.
  // TODO this doesn't handle the case where cards in a collection may not be on the page due to filters, then they're just missing from the page entirely
  // ...is that a feature? if you filter for a certain tag would you only want to see items in your collections matching that tag? or does the filter only apply to main grid
  initCollections() {
    var inst = this;
    var moveItems = (collection) => {
      for (var i in collection.artifacts) {
        var artifactItem = $('.post-' + collection.artifacts[i])[0];
        var destinationGrid;

        // Determine destination grid by collection ID.
        for (var j in inst.grids) {
          if (collection.id === $(inst.grids[j].getElement()).data('collection-id')) {
            destinationGrid = inst.grids[j];
          }
        }

        // Move item.
        if ( artifactItem && destinationGrid ) {
          inst.grids[0].send(artifactItem, destinationGrid, -1, {
            layoutSender: 'instant',
            layoutReceiver: 'instant',
          });
        }
      }
    }

    $.ajax({url: '/wp-json/digiped/v1/collections'})
      .done((collections) => {
        for (var i in collections) {
          var elem = inst.createCollection(collections[i]);
          var grid = inst.initGrid(elem);
          moveItems(collections[i]);
          inst.initGridEvents(grid);
        }

        inst.redraw();
        inst.loaded = true; // Flipping this effectively enables the receive() handler for future events.
      });
  }

  // Initialize filter menu to hide/show controls for each filter.
  initFilterMenu() {
  }

  // Add all tags to filter & bind events.
  initTagFilter() {
    var allTags = [];

    // Main grid only.
    this.grids[0].getItems().map((item) => {
      if ( ! $(item.getElement()).data('post-tags') ) {
        return;
      }

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
      dragSort: () => {return inst.grids},
      dragSortInterval: 10,
    });

    inst.grids.push(m);

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
    this.grids.map((grid) => {
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
