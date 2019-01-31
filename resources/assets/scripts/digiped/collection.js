import Grid from './grid';

export default class Collection extends Grid {
  // Initialize a new collection.
  constructor(id = '', name = '', artifacts = []) {
    // Create the collection element.

    var container = $('<div class="collection bg-light-gray">');

    container
      .appendTo('.my-digiped .collections')
      .append('<h3 class="pt3 pl3 pr3">' + name + '</h3>')
      .append('<div class="grid relative cf" data-collection-id="' + id + '"></div>');

    // Copy artifacts from the main grid to this collection's grid.
    for (var i in artifacts) {
      let artifactItem = $('.sidebar-hidden .post-' + artifacts[i]);

      if (0 === artifactItem.length) {
        artifactItem = $('.main-grid .post-' + artifacts[i])
      }

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
    const inst = this;

    // Toggle artifact visibility by clicking collection name.
    $(inst.Muuri.getElement()).parent().find('h3').on('click', (e) => {
      e.preventDefault();
      Collection.removeItemEvent();
      Collection.toggle(inst);
    });
  }

  static removeItemEvent() {
    $(".remove-artifact").click(function (e) {
      e.preventDefault();
      let elem = $(this).closest('article');
      let collectionId = $(this).closest('.grid').data('collectionId');

      $.ajax({
        method: "DELETE",
        url: '/wp-json/digiped/v1/collections/' + collectionId + '/artifact/' + elem.data('id'),
      }).done(function () {

        let gridPosition = 0;
        let gridID = 0
        window.dpGrids.forEach(function (grid) {
          const elemLocal = $(grid.getElement()).data('collectionId');
          if (collectionId === elemLocal) {
            gridID = gridPosition;
            return false;
          }
          gridPosition++;
        });
        window.dpGrids[gridID].remove(elem[0], {
          removeElements: true,
        });
      });
    });
  }

  static toggle(inst) {
    if (inst.Muuri.getItems(inst.Muuri.getItems(), 'visible').length) {
      inst.close();
    } else {
      inst.open();
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
