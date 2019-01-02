<?php 
    $metadata = get_post_meta($artifact->ID); 
    $creators = $metadata['creator(s)'][0];
    $license = $metadata['license'][0];
    $type = $metadata['type'][0];
    $url = $metadata['url'][0];
?>
<article
	@php(post_class('absolute z-1 fl ba bg-white black ma2')) >

	<div class="flex flex-column">
		<div class="thumbnail">
				@if(has_post_thumbnail())
					@php(the_post_thumbnail([200, 150], ['class' => 'db']))
				@else
					<img class="db mw-100" src="//via.placeholder.com/200x150">
				@endif
		</div>
		<div class="meta pv2 ph3 mw-100">
			<div class="author f7 mb2">
				<i class="fa fa-user"></i>
				<?php echo $creators; ?>
			</div>
			<div class="tags f7">
					<i class="fa fa-tag mr1 f7"></i>
					<ul class="list di ttu ma0 pa0 f7">
						
					</ul>
			</div>
			<div class="excerpt f7 dn">
				@php($artifact->post_content)
			</div>
		</div>
	</div>
</article>