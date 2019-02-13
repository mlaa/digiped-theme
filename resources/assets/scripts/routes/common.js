import DigiPed from '../digiped';
import Grid from '../digiped/grid';
import Collection from '../digiped/collection';

export default {
  init() {
    // JavaScript to be fired on all pages
    new DigiPed;
    $(".collections .grid").each(function () {
      let inst = new Grid(this);
      $(inst.Muuri.getElement()).parent().find('h3').on('click', (e) => {
        e.preventDefault();
        Collection.removeItemEvent();
        Collection.toggle(inst);
      });
    });

    $("article").each(function () {
      $(this).addClass('panel');
    });

  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
    $(".read-more-keyword-description").click(function (e) {
      e.preventDefault();
      let content = $(".the-keyword-content");
      let button_text = "Read More...";

      if (content.hasClass('read-more-show')) {
        content.removeClass("read-more-show");
        button_text = "Read Less...";
      } else {
        content.addClass("read-more-show");
        button_text = "Read More...";
      }

      $(this).html(button_text);
    });

    // JavaScript to be fired on the home page, after the init JS
    $(".view-list").click(function (e) {
      e.preventDefault();
      $("article").each(function () {
        $(this).addClass('panel');
      });
      window.dpGrids[0].refreshItems().layout();
    });

    $(".view-module").click(function (e) {
      e.preventDefault();
      $(".panel").each(function () {
        $(this).removeClass('panel');
      });
      window.dpGrids[0].refreshItems().layout();
    });

    $(".read-more-card-link ").click(function (e) {
      e.preventDefault();
      let article = $(this).closest('article');
      const post_id = ".post-" + article.data('id');
      $(".panel").each(function () {
        $(this).removeClass('panel');
      });
      article.addClass('panel');
      window.dpGrids[0].refreshItems().layout();


      // on click we line the panel to the middle of the page.
      const el = $(post_id);
      const elOffset = el.offset().top;
      const elHeight = el.height();
      const windowHeight = $(window).height();
      let offset;
      if (elHeight < windowHeight) {
        offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
      } else {
        offset = elOffset;
      }

      $('html, body').stop().animate({
        scrollTop: offset,
      }, 600);
    });
  },
};
