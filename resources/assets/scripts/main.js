// import external dependencies
import 'jquery';

// Import everything from autoload
import "./autoload/**/*"

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import aboutUs from './routes/about';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About Us page, note the change from about-us to aboutUs.
  aboutUs,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());


// draggable cards
import Muuri from '../../../node_modules/muuri/muuri.min.js';
jQuery(document).ready(function() {
  // Multigrid drag sorting.
  var grids = [];

  // eslint-disable-next-line
  function getAllGrids(item) {
    return grids;
  }

  var recalc = function() {
    grids.forEach(function(muuri) {
      muuri.layout();
    });
  }

  // sidebar collections
  $('.my-digiped div > div').each(function(){
    var muuri = new Muuri(this, {
      items: 'article',
      dragEnabled: true,
      dragSort: getAllGrids,
    })
    .on('dragReleaseEnd', recalc);
    grids.push(muuri);
  });

  // main grid
  var mmuuri = new Muuri('main', {
    dragEnabled: true,
    dragSort: getAllGrids,
  })
  .on('dragReleaseEnd', recalc);

  grids.push(mmuuri);
});
