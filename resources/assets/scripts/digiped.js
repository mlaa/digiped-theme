/* global wpApiSettings */

import Grid from './digiped/grid';
import Collection from './digiped/collection';

export default class DigiPed {
  constructor() {
    var inst = this;

    // Add WP Nonce header to all ajax requests.
    $.ajaxSetup({
      headers: {'X-WP-Nonce': wpApiSettings.nonce},
    });

    // Initialize main grid.
    inst.grids = [
      new Grid($('main .grid')[0]).Muuri,
    ];

    // Initialize filters.
    inst.initFilters();

    // Initialize collections.
    inst.initCollections()
      .done(() => {
        window.dpGrids.map((grid) => {
          grid.refreshItems();
          grid.layout(true);
        });
      });
  }

  // Add all tags to filter & bind events.
  initFilters() {
    var taxonomies = [
      'tag',
      'type',
      'keyword',
      'author',
    ];

    taxonomies.forEach((taxonomy) => {
      var allTerms = [];

      // Parse terms from artifacts in the main grid.
      window.dpGrids[0].getItems().map((item) => {
        if ($(item.getElement()).data(taxonomy)) {
          $(item.getElement()).data(taxonomy).map((term) => {
            var termHTML = '<li class="dib"><a class="link db ba br2 ma1 pa1 dark-gray" href="#">' + term + '</a></li>'
            if (-1 === allTerms.indexOf(termHTML)) {
              allTerms.push(termHTML);
            }
          });
        }
      });

      // Add terms to filters.
      $('.controls ul.' + taxonomy).html(allTerms.sort().join(''));

      // Handle clicking filter menu to change filter taxonomy.
      $('.controls a.' + taxonomy).on('click', (e) => {
        $(e.target).parent()
          .addClass('b')
          .siblings().removeClass('b');

        $('.options ul').hide();
        $('.options .' + taxonomy).show();

        e.preventDefault();
      });

      // Handle clicking a term to filter artifacts.
      $('.controls ul.' + taxonomy + ' a').on('click', (e) => {
        if ($(e.target).hasClass('active')) {
          // Clear existing filter.
          $(e.target).removeClass('active');
          window.dpGrids[0].filter('article');
        } else {
          // Apply filter for this term.
          $(e.target).parents('.options').find('a').removeClass('active');
          $(e.target).addClass('active');
          window.dpGrids[0].filter((item) => {
            return $(item.getElement()).data(taxonomy).includes($(e.target).html());
          });
        }

        e.preventDefault();
      });
    });
  }

  // Load & initialize all collections.
  initCollections() {
    var inst = this;

    var createCollectionHandler = (e) => {
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
    }

    $('.create-collection').on('click', createCollectionHandler);

    return $.ajax({url: '/wp-json/digiped/v1/collections'})
      .done((data) => {
        var collections = data;
        for (var i in collections) {
          var c = new Collection(collections[i].id, collections[i].name, collections[i].artifacts);
          inst.grids.push(c.Muuri);
        }
      });
  }
}
