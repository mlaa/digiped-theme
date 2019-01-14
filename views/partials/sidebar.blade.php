<?php 
	$is_keyword_single = is_single() && 'digiped_keyword' === get_post_type();
	$sidebar_class = is_keyword_single?"digi-keyword-sidebar":""; 
?>
<aside class="sidebar order-1 mw5 br <?php echo $sidebar_class;?>">
	<div class="aside-sticky">
		<div>
			<div class="pa2">
				<?php 
				$top75 = 'top-75';
					if($is_keyword_single) {
						$top75 = '';
							$the_artifacts = new WP_Query( 'post_type=digiped_artifact', 'tag='.get_the_title() );
							?> 
								<div class="digi-keyword-page">
									<header>
										<h2 class="sub-title">
											<div class="artifacts-sidebar">Artifacts in this keyword</div>
										</h2>
									</header>
								<div>
							<?php 
								if ( $the_artifacts->have_posts() ) {
									echo '<ul>';
									while ( $the_artifacts->have_posts() ) {
										$the_artifacts->the_post();
										echo "<li><a class='link dim mid-gray sidebar-links' href='#artifact-".get_the_ID()."'>" . get_the_title() . "</a></li>"; 
									}
									echo '</ul>';
									wp_reset_postdata();
								}
					}
				?>
			</div>
		</div>	
	</div>		
	@include('partials/my-collections')
	<div class="sidebar-hidden hidden">
		<?php 
			// hack to allow for cross page collections
			$collections = DigiPed_Collection::list(); 
			if(!empty($collections)) {
				$col = array();
				foreach($collections as $collection) { 
					$col[] = $collection->artifacts[0];
				}
				$col = array_unique($col);
				if(count($col) > 0) {
					$artifacts = new WP_Query( array( 'post_type' => 'any', 'post__in' => $col) );
					if ( $artifacts->have_posts() ) {
						while ( $artifacts->have_posts() ) {
							$artifacts->the_post(); ?> 
							@include('partials.content', array('top75' => $top75))
						<?php  } 
						wp_reset_postdata();
					}	
				} 
		} ?>
	</div>
	<div class="sticky bottom-95"></div>
</aside>