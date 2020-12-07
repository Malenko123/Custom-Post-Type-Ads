<?php get_header(); ?>

<div class="section-inner container">
	<div class="row">
		<div <?php post_class(); ?>>

			<div class="breadcrumbs">
				<a href="<?php echo site_url(); ?>">Home</a>
				/
				<a href="<?php echo site_url(); ?>/ads">Ads</a>
				/
				<span class="current"><?php the_title(); ?></span>
			</div>

			<h1 class="single-ad-title"><?php the_title(); ?></h1>

			<div class="ad-section">		
			<?php
				if ( have_posts() ) : ?>
				  <div class="ad-row">
				    <?php 
				      while ( have_posts() ) : the_post(); ?>

				      	<?php $price = get_post_meta( get_the_ID(), 'price', true ); ?>

				      	<div class="single-ad-content">
				            <?php 
					          $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'full' ); ?>

					          <?php if( $url ) : ?>
						          <div class="single-full-featured">
						              <img style="width:100%;" src="<?php echo $url; ?>" />
						          </div>
						      <?php endif; ?>

						      <div class="ads-body">
					      		<div class="ads-header">
						      		<p class="ads-date"><i class="far fa-calendar"></i> <?php echo get_the_date('j. F Y') ?></p>

						      		<div class="categories">
							      		<p class="category">In
							      			<span class="city">
								      			<?php 
									      			$ads_locations = get_the_terms( $post->ID,'ads_locations');
													foreach ( $ads_locations as $location ) {
													  echo $location->name;
													}
								      			?>
								      		</span>
							      		</p>
							      		<p class="ads-price">$<?php echo number_format($price); ?></p>
							      	</div>
						      	</div>

						      	<?php the_content(); ?>
						      </div>
					  	</div>
					<?php endwhile; ?> <!-- end while -->
				</div>
				<?php endif; ?> <!-- end if -->
				<?php wp_reset_postdata(); ?>			
			</div>


			<!-- single post navigation -->
			<?php
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ( $next_post || $prev_post ) {

				$pagination_classes = '';

				if ( ! $next_post ) {
					$pagination_classes = ' only-one only-prev';
				} elseif ( ! $prev_post ) {
					$pagination_classes = ' only-one only-next';
				}

				?>

				<nav class="single-nav section-inner<?php echo esc_attr( $pagination_classes ); ?>" aria-label="<?php esc_attr_e( 'Post', 'twentytwenty' ); ?>" role="navigation">


					<div class="pagination-single-inner">

						<?php
						if ( $prev_post ) {
							?>

							<a class="previous-post" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
								<span class="arrow" aria-hidden="true">&larr;</span>
								<span class="title"><span class="title-inner"><?php echo wp_kses_post( get_the_title( $prev_post->ID ) ); ?></span></span>
							</a>

							<?php
						}

						if ( $next_post ) {
							?>

							<a class="next-post" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
								<span class="arrow" aria-hidden="true">&rarr;</span>
									<span class="title"><span class="title-inner"><?php echo wp_kses_post( get_the_title( $next_post->ID ) ); ?></span></span>
							</a>
							<?php
						}
						?>

					</div><!-- .pagination inner -->

				</nav><!-- .pagination-single -->

				<?php
			} ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
