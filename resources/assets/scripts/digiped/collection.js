import Grid from './grid';

export default class Collection extends Grid {
  // Initialize a new collection.
  constructor(id = '', name = '', artifacts = []) {
    // Create the collection element.
    var container = $('<div class="collection ba mv2 pa1">')
      .append($('<h3 class="ma0">' + name + '</h3>'))
      .append($('<div class="grid relative cf" data-collection-id="' + id + '"></div>'))
      .clone()
      .appendTo('.my-digiped .collections');

    // Copy artifacts from the main grid to this collection's grid.
    for (var i in artifacts) {
      var artifactItem = $('.post-' + artifacts[i]);
      if (1 === artifactItem.length) {
        artifactItem.clone().appendTo(container.find('.grid'));
      }
    }

    // Initialize this collection's grid.
    super(container.find('.grid')[0]);
  }

  open() {

  }
}
