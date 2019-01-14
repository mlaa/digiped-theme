// import external dependencies
import 'jquery';

// Import everything from autoload
import "./autoload/**/*"

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import aboutUs from './routes/about';
import singleDigipedKeyword from './routes/singleDigipedKeyword';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About Us page, note the change from about-us to aboutUs.
  aboutUs,
  // Keyword page
  singleDigipedKeyword,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());

// stop infinite loops when updating JS with browsersync on
// https://discourse.roots.io/t/sage-9-changes-in-js-and-browsersync/8893
if (module.hot) { module.hot.accept(); }


// TODO find a way to avoid global namespacing these
window.dpGrids = [];
