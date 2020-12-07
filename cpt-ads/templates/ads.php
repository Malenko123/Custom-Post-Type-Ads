<?php get_header(); ?>

<div class="section-inner container">
	<div class="row">

		<h1 class="ad-main-title">ADs</h1>

		<div class="ad-section">
					
			<?php $ads_locations = get_terms('ads_locations', array('hide_empty' => false, 'parent' =>0, 'order' => 'ASC' )); ?>
			
			<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">		
				<div class="ad-filter">
					<div class="location-filter">	
						<label class="location-filter-title"><?php echo (!empty(get_option( 'location_label' )) ? get_option( 'location_label' ) : 'Locations'); ?></label>	

						<select id="ad-location-select" name="locationfilter">
							<option value="">All</option>

							<?php foreach($ads_locations as $location) : ?>		
									<option value="<?php echo $location->term_id; ?>" class="opt"><?php echo $location->name; ?></option>	
							<?php endforeach; ?>
						</select>		
					</div>

					<div class="price-filter">
						<label class="price-filter-title"><?php echo (!empty(get_option( 'price_label' )) ? get_option( 'price_label' ) : 'Price'); ?></label>	

						<div class="price-slider">
							<input type="number" name="price_min" placeholder="Min price" class="price-min" />
							<input type="number" name="price_max" placeholder="Max price" class="price-max" />
							<input value="25000" min="0" max="120000" step="500" type="range"/>
							<input value="50000" min="0" max="120000" step="500" type="range"/>
						</div>
					</div>

					<div class="ad-filter-btn">
						<button class="apply-filter btn"><?php echo (!empty(get_option( 'filter_btn_text' )) ? get_option( 'filter_btn_text' ) : 'Apply'); ?></button>
						<a href="" id="clear" class="btn"><?php echo (!empty(get_option( 'clear_btn_text' )) ? get_option( 'clear_btn_text' ) : 'Reset'); ?></a>

						<input type="hidden" name="action" value="adsfilter">
					</div>											
				</div><!-- end ad filter -->
			</form>
			

			
		<?php
			$args = array(
			    'post_type' => 'ads',
			    'posts_per_page'   => -1,
			    'orderby' => 'publish_date',
				'order' => 'DESC'
			);
		
			$ads_query = new WP_Query( $args );

			if ( $ads_query->have_posts() ) :
			  ?>
			  <div class="ad-row first">
			    <?php 
			      while ( $ads_query->have_posts() ) : $ads_query->the_post(); ?>

			      	<?php $price = get_post_meta( get_the_ID(), 'price', true ); ?>

			      	<div class="col ads-col">
			            <?php 
				          $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' ); ?>

				          <?php if( $url ) : ?>
					          <div class="ads-img">
					          	  <a href="<?php the_permalink(); ?>" class="img-link"></a>
					              <img style="width:100%;" src="<?php echo $url; ?>" />
					          </div>
					      <?php endif; ?>

					      <div class="ads-body">
				      		<div class="ads-header">
					      		<p class="ads-date"><?php echo get_the_date('j. F Y') ?></p>
					      		<p class="ads-price"><strong>$<?php echo number_format($price); ?></strong></p>
					      	</div>

					      	<a href="<?php the_permalink(); ?>" class="ads-link">
					      		<h2 class="ads-title"><?php the_title(); ?> <span class="post-go-to"><i class="fas fa-arrow-right"></i></span></h2>
					      	</a>
					      </div>
				  	</div>
				<?php endwhile; ?> <!-- end while -->
			</div>
			<?php endif; ?> <!-- end if -->
			<?php wp_reset_postdata(); ?>			
		</div>
	</div>
</div>

<?php get_footer(); ?>
