export default {
  init() {
    $("article").each(function () {
      $(this).addClass('panel');
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
    console.log('here');
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
  },
};
