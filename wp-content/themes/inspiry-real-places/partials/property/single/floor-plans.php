<?php
global $post;
$property_floor_plans = get_post_meta( $post->ID, 'inspiry_floor_plans', true );

    if( !empty( $property_floor_plans ) && is_array( $property_floor_plans ) && !empty( $property_floor_plans[0]['inspiry_floor_plan_name'] ) ) {

        ?>
        <div class="floor-plans">

	        <?php
	        /*
	         * Floor Plans Title
	         */
	        global $inspiry_options;
	        $property_floor_plans_title = $inspiry_options[ 'inspiry_property_floor_plans_title' ];
	        if( !empty( $property_floor_plans_title ) ) {
		        ?><h4 class="fancy-title"><?php echo esc_html( $property_floor_plans_title ); ?></h4><?php
	        }
	        ?>

			<div class="floor-plans-accordions">
                <?php
                /*
                 * Floor Plans Contents
                 */
                foreach ( $property_floor_plans as $i => $floor ) {
	                ?>
					<div class="floor-plan">
						<div class="floor-plan-title clearfix">
							<i class="fa fa-plus"></i>
							<h3><?php echo esc_html( $floor['inspiry_floor_plan_name'] ); ?></h3>
							<div class="floor-plan-meta">
								<?php
								/*
                                 * Size
                                 */
								if( ! empty( $floor['inspiry_floor_plan_size'] ) ) {
									$floor_size = $floor['inspiry_floor_plan_size'];
									echo '<div>';
									echo esc_html( $floor_size );
									if( ! empty( $floor['inspiry_floor_plan_size_postfix'] ) ){
										$floor_size_postfix = $floor['inspiry_floor_plan_size_postfix'];
										echo ' ' . $floor_size_postfix;
									}
									echo '</div>';
								}

								/*
                                 * Bedrooms
                                 */
								if( ! empty( $floor['inspiry_floor_plan_bedrooms'] ) ) {
									$floor_bedrooms = floatval( $floor['inspiry_floor_plan_bedrooms'] );
									$bedrooms_label = ( $floor_bedrooms > 1 ) ? __( 'Bedrooms', 'inspiry' ) : __( 'Bedroom', 'inspiry' );
									echo '<div>';
									echo esc_html( $floor_bedrooms . ' ' . $bedrooms_label );
									echo '</div>';
								}

								/*
                                 * Bathrooms
                                 */
								if( ! empty( $floor['inspiry_floor_plan_bathrooms'] ) ) {
									$floor_bathrooms = floatval( $floor['inspiry_floor_plan_bathrooms'] );
									$bathrooms_label = ( $floor_bathrooms > 1 ) ? __( 'Bathrooms', 'inspiry' ): __( 'Bathroom', 'inspiry' );
									echo '<div>';
									echo esc_html( $floor_bathrooms . ' ' . $bathrooms_label );
									echo '</div>';
								}

								/*
                                 * Price
                                 */
								if( ! empty( $floor['inspiry_floor_plan_price'] ) ) {
									/* Get price postfix */
									$floor_price_postfix = $floor[ 'inspiry_floor_plan_price_postfix' ];
									echo '<div class="floor-price">' . Inspiry_Property::format_price( doubleval( $floor[ 'inspiry_floor_plan_price' ] ) ) . ' ' . $floor_price_postfix . '</div>';
								}
								?>
							</div>
						</div>

						<div class="floor-plan-content">

		                    <?php if( ! empty( $floor['inspiry_floor_plan_descr'] ) ) {?>
	                            <div class="floor-plan-desc">
		                            <?php echo apply_filters( 'the_content', $floor['inspiry_floor_plan_descr'] ); ?>
	                            </div>
		                    <?php } ?>

		                    <?php if( ! empty( $floor['inspiry_floor_plan_image'] ) ) {?>
		                        <div class="floor-plan-map">
		                            <a href="<?php echo esc_url( $floor['inspiry_floor_plan_image'] ); ?>" class="swipebox" rel="gallery_floor_plans" >
		                                <img src="<?php echo esc_url( $floor['inspiry_floor_plan_image'] ); ?>" >
		                            </a>
		                        </div>
		                    <?php } ?>

						</div>

					</div>
	                <?php
                }
                ?>
			</div>
        </div>
        <?php
    }
?>