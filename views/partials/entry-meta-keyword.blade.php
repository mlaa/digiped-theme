<?php 
    $metadata = get_post_meta(get_the_ID()); 
    $creators = $metadata['creator(s)'][0];
    $license = $metadata['license'][0];
    $type = $metadata['type'][0];
    $url = $metadata['url'][0];
if ($creators) { ?>
  <h2 class='sub-title'><div class='curator'>Curator(s)</div></h1>
      <h3 class='curator-name'><?php echo str_replace("and", "<br/>", $creators); ?></h3>
<?php } ?>
