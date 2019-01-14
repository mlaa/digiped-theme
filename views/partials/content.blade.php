<?php 
	$author = preg_replace("/\([^)]+\)/", '', get_post_meta(get_the_id(), 'creator(s)', true));
	// keyword metadata
	$keyword = get_post_meta(get_the_id(), 'keyword', true);
	$keyword_post = get_page_by_title( $keyword, OBJECT, 'digiped_keyword' );
	$keyword_curator = get_metadata('post', $keyword_post->ID, 'creator(s)', true);
	$is_keyword = "digiped_keyword" === get_post_type();
	$is_artifact = "digipad_artifact" === get_post_type();

?>
<article id="artifact-<?php echo get_the_ID(); ?>" @php(post_class('absolute z-1 fl bg-white black ma2'))
		data-id="@php(the_id())"
		data-tag="{{json_encode(array_map(function($term){return $term->name;}, get_the_tags()))}}"
		data-type="{{json_encode(get_post_meta(get_the_id(), 'type'))}}"
		data-keyword="{{json_encode(get_post_meta(get_the_id(), 'keyword'))}}"
		data-author="{{json_encode([preg_replace("/\([^)]+\)/", '', get_post_meta(get_the_ID(), 'creator(s)', true))])}}"
		data-curator="{{json_encode(explode(";",$keyword_curator))}}"
>
	<div class="link-wrapper mb2">
		<?php if ($is_keyword) { ?>
		<div class="keyword-label f7 pl2 pr2">Keyword</div>
		<?php }?>
		<div class="mid-gray title pa2<?= $is_keyword?" b":"";?>">
			<?php 
				// todo temp fix to dirty data
				cropWords(get_the_title(),4);
			?></div>
		<div class="remove-artifact-wrapper hidden"><a class="remove-artifact" href="#"><i class="material-icons black f6">clear</i></a></div>
	</div>
	<div class="card-content hidden fl black pl2">
			<?= get_the_content() ?>
	</div>
	<div class="flex flex-column kw-card pl2 pr2">
		<div  class="kw-card-inner-wrapper">
				<div class="thumbnail">
					@if(has_post_thumbnail())
						@php(the_post_thumbnail([300, 150], ['class'=> 'db']))
					@else
					<img class="db mw-100" src="//via.placeholder.com/300x150">
					@endif
				</div>
				<div class="meta mt2 ph3">
					<div class="card-meta h-30">
						<div class="fl w-50">
							<div class="author f60 mb2 h-33">
								<i class="fl material-icons ml1 mr1 f7">person</i>
								<div class="fl h-100">
									<?php 
											// todo temp fix to dirty data
											cropWords($author,3);
											?>
								</div>
							</div>
							<div class="curator hidden f7 mb2">
									<i class="fa fa-user"></i>
									<?php echo explode(";",strtolower($keyword_curator))[0]; ?>
							</div>
							<div class="add-to-collection f60 mb2 h-33">
								<i class="fl material-icons ml1 mr1 f7">add_circle</i>
								<div class="fl h-100">Add to Collection</div>
							</div>
							<div class="download-artifact f60 mb2 h-33">
								<i class="fl material-icons ml1 mr1 f7">cloud_download</i>
								<div class="fl h-100">Download artifact</div>
							</div>
						</div>
						<div class="fl w-50">
							<div class="types f60 mb2 h-33">
								<i class="fl material-icons ml1 mr1 f60">folder</i>
								<div class="fl h-100">
									<?= strtolower(get_post_meta(get_the_id(), 'type', true)) ?>
								</div>
							</div>
							<div class="tags f60 mb2 h-33">
								<i class="fl material-icons ml1 mr1 f7">local_offer</i>
								<div class="fl h-100">
										<?php 
											$posttags = array_map(function($term){return $term->name;}, get_the_tags());
											if ($posttags) { 
												echo implode(", ", $posttags);
											} ?>
								</div>
							</div>
							<div class="types f60 mb2 h-33">
								<i class="fl material-icons ml1 mr1 f7">link</i>
								<div class="fl h-100">View orginal</div>
							</div>
						</div>
					</div>
					<div class="card-excerpt mt2 h-65 f60 clear">
						<div>
							<?= cropWords(get_the_content(), 35, false)."..."; ?>
						</div>
					</div>
				</div>
				<div class="read-more-card f6 dib v-btm"><a href="#" class="read-more-card-link link mid-gray title">Read more...</a></div>
		</div>
	</div>
</article>
