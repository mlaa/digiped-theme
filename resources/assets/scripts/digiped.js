/* global wpApiSettings */

import Grid from './digiped/grid';
import Collection from './digiped/collection';

export default class DigiPed {
  constructor() {
    var inst = this;

    // This flag prevents receive() from updating the backend until initCollections() has finished.
    inst.loaded = false;

    // Add WP Nonce header to all ajax requests.
    $.ajaxSetup({
      headers: {'X-WP-Nonce': wpApiSettings.nonce},
    });

    // Initialize main grid.
    new Grid($('main .grid')[0]);

    // Initialize filters.
    inst.initFilterMenu();
    inst.initTagFilter();

    // Initialize collections.
    inst.initCollections();

    // temporary debug helper
    $( 'aside a' ).on('click', (e) => {
      window.dpGrids.map((grid) => {
        $(grid).trigger('dragReleaseEnd');
      });
      e.preventDefault();
    });
  }

  // Load & initialize all collections.
  initCollections() {
    $.ajax({url: '/wp-json/digiped/v1/collections'})
      .done((collections) => {
        for (var i in collections) {
          new Collection(collections[i].id, collections[i].name, collections[i].artifacts);
        }
      });

    $('.create-collection').on('click', (e) => {
      var name = prompt('What should this collection be named?');

      if (name) {
        $.ajax({
          method: "POST",
          url: '/wp-json/digiped/v1/collections',
          data: {name: name},
        })
          .done((data) => {
            new Collection(data.id, data.name);
          });
      }

      e.preventDefault();
    });
  }

  // Initialize filter menu to hide/show controls for each filter.
  initFilterMenu() {
  }

  // Add all tags to filter & bind events.
  initTagFilter() {
    var allTags = [];

    // Main grid only.
    window.dpGrids[0].getItems().map((item) => {
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
}
