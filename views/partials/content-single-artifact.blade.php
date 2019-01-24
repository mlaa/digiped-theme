<?php 
  // There are no detail pages, only category based listing pages.
  $taxonomies = get_the_taxonomies();
  $keyword = explode(',',strip_tags(trim(str_replace(array(' and'), ",", str_replace(array('Keyword:',  "."), "", $taxonomies['keyword'])))));
  wp_redirect("/keyword/".$keyword[0]);
