import Grid from './grid';

export default class Collection extends Grid {
  // Initialize a new collection.
  constructor(id = '', name = '', artifacts = []) {
    // Create the collection element.
    var container = $('<div class="collection pa3 bg-light-gray mb1">');

    container
      .appendTo('.my-digiped .collections')
      .append('<h3 class="ma0">' + name + '</h3>')
      .append('<div class="grid relative cf" data-collection-id="' + id + '"></div>');

    // Copy artifacts from the main grid to this collection's grid.
    for (var i in artifacts) {
      var artifactItem = $('.post-' + artifacts[i]);
      if (1 === artifactItem.length) {
        artifactItem.clone().appendTo(container.find('.grid'));
      }
    }

    // Initialize this collection's grid.
    super(container.find('.grid')[0]);

    this.close();
    this.initEvents();
  }

  initEvents() {
    var inst = this;

    // Toggle artifact visibility by clicking collection name.
    $(inst.Muuri.getElement()).parent().find('h3').on('click', (e) => {
      e.preventDefault();
      inst.toggle();
    });
  }

  toggle() {
    if (this.Muuri.getItems(this.Muuri.getItems(), 'visible').length) {
      this.close();
    } else {
      this.open();
    }
  }

  open() {
    $(this.Muuri.getElement()).parent().addClass('active');
    this.Muuri.show(this.Muuri.getItems(), {
      instant: true,
    });
  }

  close() {
    $(this.Muuri.getElement()).parent().removeClass('active');
    this.Muuri.hide(this.Muuri.getItems(), {
      instant: true,
    });
  }
}
