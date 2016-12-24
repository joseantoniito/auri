<?php
if( !function_exists( 'inspiry_property_meta' ) ) :
    /**
     * @param object $inspiry_property
     * @param array $property_meta_array
     */
    function inspiry_property_meta( $inspiry_property, $property_meta_array = array()  ) {

        ?>
        <div class="property-meta entry-meta clearfix <?php if ( isset( $property_meta_array['container_classes'] ) ) { echo esc_attr( $property_meta_array[ 'container_classes' ] ); } ?>"><?php

            $meta_array = array( 'id', 'area', 'beds', 'baths', 'garages', 'type', 'status', 'location' );

            if ( isset( $property_meta_array['meta'] ) && is_array( $property_meta_array['meta'] ) ) {

                $meta_array = $property_meta_array['meta'];

            } elseif ( isset( $property_meta_array['exclude'] ) && is_array( $property_meta_array['exclude'] ) ) {
                $meta_array = array_diff( $meta_array, $property_meta_array['exclude'] );

            }

            $temp_dir = get_template_directory();

            foreach ( $meta_array as $meta_type ) {

                switch ( $meta_type ):

                    case 'id':
                        $inspiry_property_custom_ID = $inspiry_property->get_custom_ID();
                        if ( $inspiry_property_custom_ID ) {
                            ?>
                            <div class="meta-item">
                                <i class="meta-item-icon icon-pid"><?php include( $temp_dir . '/images/svg/icon-pid.svg' ); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e('Property ID', 'inspiry'); ?></span>
                                    <span class="meta-item-value"><?php echo esc_html( $inspiry_property_custom_ID ); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'area':
                        $inspiry_property_area = $inspiry_property->get_area();
                        if ( $inspiry_property_area ) {
                            ?>
                            <div class="meta-item">
                                <i class="meta-item-icon icon-area"><?php include( $temp_dir . '/images/svg/icon-area.svg' ); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e( 'Area', 'inspiry' ); ?></span>
                            <span class="meta-item-value"><?php
                                // area
                                echo esc_html( $inspiry_property_area );

                                // area postfix
                                $inspiry_property_area_postfix = $inspiry_property->get_area_postfix();
                                if ( $inspiry_property_area_postfix ) {
                                    ?><sub class="meta-item-unit"><?php echo esc_html( $inspiry_property_area_postfix ); ?></sub><?php
                                }
                                ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'beds':
                        $inspiry_property_beds = $inspiry_property->get_beds();
                        if ( $inspiry_property_beds ) {
                            ?>
                            <div class="meta-item">
                                <i class="meta-item-icon icon-bed"><?php include( $temp_dir . '/images/svg/icon-bed.svg' ); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e( 'Bedrooms', 'inspiry' ); ?></span>
                                    <span class="meta-item-value"><?php echo esc_html( $inspiry_property_beds ); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'baths':
                        $inspiry_property_baths = $inspiry_property->get_baths();
                        if ( $inspiry_property_baths ) {
                            ?>
                            <div class="meta-item">
                                <i class="meta-item-icon icon-bath"><?php include( $temp_dir . '/images/svg/icon-bath.svg' ); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e( 'Bathrooms', 'inspiry' ); ?></span>
                                    <span class="meta-item-value"><?php echo esc_html( $inspiry_property_baths ); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'garages':
                        $inspiry_property_garages = $inspiry_property->get_garages();
                        if ( $inspiry_property_garages ) {
                            ?>
                            <div class="meta-item">
                                <i class="meta-item-icon icon-garage"><?php include( $temp_dir . '/images/svg/icon-garage.svg' ); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e('Garages', 'inspiry'); ?></span>
                                    <span class="meta-item-value"><?php echo esc_html( $inspiry_property_garages ); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'type':
                        $inspiry_property_types = $inspiry_property->get_types();
                        if ( $inspiry_property_types ) {
                            ?>
                            <div class="meta-item meta-property-type">
                                <i class="meta-item-icon icon-ptype"><?php include( $temp_dir . '/images/svg/icon-ptype.svg'); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e('Type', 'inspiry'); ?></span>
                                    <span class="meta-item-value"><?php echo esc_html( $inspiry_property_types ); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'status':
                        $inspiry_property_status = $inspiry_property->get_status();
                        if ( $inspiry_property_status ) {
                            ?>
                            <div class="meta-item">
                                <i class="meta-item-icon icon-tag"><?php include( $temp_dir . '/images/svg/icon-tag.svg'); ?></i>
                                <div class="meta-inner-wrapper">
                                    <span class="meta-item-label"><?php _e('Status', 'inspiry'); ?></span>
                                    <span class="meta-item-value"><?php echo esc_html( $inspiry_property_status ); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        break;

                    case 'location':
						if ( method_exists( $inspiry_property, 'get_city' ) ) {
							$inspiry_property_city = $inspiry_property->get_city();
							if ( $inspiry_property_city ) {
								?>
								<div class="meta-item">
									<i class="meta-item-icon icon-location"><?php include( $temp_dir . '/images/svg/icon-location.svg'); ?></i>
									<div class="meta-inner-wrapper">
										<span class="meta-item-label"><?php _e('Location', 'inspiry'); ?></span>
										<span class="meta-item-value"><?php echo esc_html( $inspiry_property_city ); ?></span>
									</div>
								</div>
								<?php
							}
						}
                        break;

                endswitch;

            }

            ?>
        </div><!-- .property-meta -->
        <?php

    }

endif;



if( !function_exists( 'inspiry_get_file_icon' ) ) :
    /**
     * Return font awesome icon based on file extnsion
     * @param string $extension file extension
     * @return string
     */
    function inspiry_get_file_icon( $extension ) {

        switch ( $extension ) {
            /* PDF */
            case 'pdf':
                return '<i class="fa fa-file-pdf-o"></i>';

            /* Images */
            case 'jpg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'jpeg':
            case 'tiff':
            case 'tif':
                return '<i class="fa fa-file-image-o"></i>';

            /* Text */
            case 'txt':
            case 'log':
            case 'tex':
                return '<i class="fa fa-file-text-o"></i>';

            /* Documents */
            case 'doc':
            case 'odt':
            case 'msg':
            case 'docx':
            case 'rtf':
            case 'wps':
            case 'wpd':
            case 'pages':
                return '<i class="fa fa-file-word-o"></i>';

            /* Spread Sheets */
            case 'csv':
            case 'xlsx':
            case 'xls':
            case 'xml':
            case 'xlr':
                return '<i class="fa fa-file-excel-o"></i>';

            /* Zip */
            case 'zip':
            case 'rar':
            case '7z':
            case 'zipx':
            case 'tar.gz':
            case 'gz':
            case 'pkg':
                return '<i class="fa fa-file-zip-o"></i>';

            /* Audio */
            case 'mp3':
            case 'wav':
            case 'm4a':
            case 'aif':
            case 'wma':
            case 'ra':
            case 'mpa':
            case 'iff':
            case 'm3u':
                return '<i class="fa fa-file-audio-o"></i>';

            /* Video */
            case 'avi':
            case 'flv':
            case 'm4v':
            case 'mov':
            case 'mp4':
            case 'mpg':
            case 'rm':
            case 'swf':
            case 'wmv':
                return '<i class="fa fa-file-video-o"></i>';

            /* Others */
            default:
                return '<i class="fa fa-file-o"></i>';
        }

    }

endif;



if( !function_exists( 'inspiry_add_profile_fields' ) ) {
    /**
     * Add required fields to user profile
     * @param $user_contact_methods
     * @return mixed
     */
    function inspiry_add_profile_fields( $user_contact_methods ) {

        $user_contact_methods[ 'job_title' ]        = __( 'Job Title', 'inspiry' );
        $user_contact_methods[ 'mobile_number' ]    = __( 'Mobile Number', 'inspiry' );
        $user_contact_methods[ 'office_number' ]    = __( 'Office Number', 'inspiry' );
        $user_contact_methods[ 'fax_number' ]       = __( 'Fax Number', 'inspiry' );
        $user_contact_methods[ 'office_address' ]   = __( 'Office Address', 'inspiry' );
        $user_contact_methods[ 'facebook_url' ]     = __( 'Facebook URL', 'inspiry' );
        $user_contact_methods[ 'twitter_url' ]      = __( 'Twitter URL', 'inspiry' );
        $user_contact_methods[ 'google_plus_url' ]  = __( 'Google Plus URL', 'inspiry' );
        $user_contact_methods[ 'linkedin_url' ]     = __( 'LinkedIn URL', 'inspiry' );
        $user_contact_methods[ 'pinterest_url' ]    = __( 'Pinterest URL', 'inspiry' );
        $user_contact_methods[ 'instagram_url' ]    = __( 'Instagram URL', 'inspiry' );

        return $user_contact_methods;
    }

    add_filter( 'user_contactmethods', 'inspiry_add_profile_fields' );

}



if( !function_exists( 'inspiry_property_gallery' ) ) :
    /**
     * Generated gallery for a property
     * @param int $property_id
     * @param int $gallery_limit
     */
    function inspiry_property_gallery( $property_id = 0, $gallery_limit = 3 ) {

        if ( !$property_id ) {
            $property_id = get_the_ID();
        }

        $gallery_images = inspiry_get_post_meta (
            'REAL_HOMES_property_images',
            array(
                'type' => 'image_advanced',
                'size' => 'inspiry-grid-thumbnail'
            ),
            $property_id
        );

        if ( !empty( $gallery_images ) && 0 < count( $gallery_images ) ) {
            ?>
            <div class="gallery-slider-two flexslider">
                <ul class="slides">
                    <?php
                    $gallery_image_count = 1;
                    foreach( $gallery_images as $gallery_image ) {
                        $caption = ( !empty( $gallery_image['caption'] ) ) ? $gallery_image['caption'] : $gallery_image['alt'];

                        echo '<li>';
                        echo '<a class="swipebox" data-rel="gallery-'. $property_id  .'" href="'. esc_url( $gallery_image['full_url'] ) .'" title="'. $caption .'" >';
                        echo '<img class="img-responsive" src="'. esc_url( $gallery_image['url'] ) .'" alt="'. $gallery_image['title'] .'" />';
                        echo '</a>';
                        echo '</li>';

                        if ( $gallery_image_count == $gallery_limit ) {
                            break;
                        }
                        $gallery_image_count++;
                    }
                    ?>
                </ul>
            </div>
            <?php
        } else {
            inspiry_thumbnail();
        }
    }
endif;


if( !function_exists( 'inspiry_get_location_titles' ) ) :
    /**
     * Get location titles
     *
     * @param int $location_select_count
     * @return array Location titles
     */
    function inspiry_get_location_titles ( $location_select_count = 1 ) {

        // Default location select boxes titles
        $location_titles = array(
            __( 'City', 'inspiry' ),
            __( 'Area', 'inspiry' ),
        );

        if ( $location_select_count == 1 ) {
            $location_titles = array(
                __( 'City', 'inspiry' ),
            );
        } elseif ( $location_select_count == 2 ) {
            $location_titles = array(
                __( 'City', 'inspiry' ),
                __( 'Area', 'inspiry' ),
            );
        } elseif ( $location_select_count == 3 ) {
            $location_titles = array(
                __( 'State', 'inspiry' ),
                __( 'City', 'inspiry' ),
                __( 'Area', 'inspiry' ),
            );
        } elseif ( $location_select_count == 4 ) {
            $location_titles = array(
                __( 'Country', 'inspiry' ),
                __( 'State', 'inspiry' ),
                __( 'City', 'inspiry' ),
                __( 'Area', 'inspiry' ),
            );
        }

        return $location_titles;
    }
endif;



if( !function_exists( 'inspiry_get_location_select_names' ) ) :
    /**
     * Return location select names
     * @return mixed|void
     */
    function inspiry_get_location_select_names() {
        $location_select_names = array( 'location', 'child-location', 'grandchild-location', 'great-grandchild-location' );
        return apply_filters( 'inspiry_location_select_names', $location_select_names );
    }
endif;



if( !function_exists( 'inspiry_get_locations_number' ) ) :
    /**
     * Return number of location boxes required in search form
     *
     * @return int number of locations
     */
    function inspiry_get_locations_number() {
        global $inspiry_options;
        $location_select_count = intval( $inspiry_options[ 'inspiry_search_locations_number' ] );
        if( ! ( $location_select_count > 0 && $location_select_count < 5) ){
            $location_select_count = 1;
        }
        return $location_select_count;
    }
endif;



if( !function_exists( 'inspiry_generate_location_data' ) ) {
    /**
     * Generates locations related data that is consumed by js to product locations related UI
     */
    function inspiry_generate_location_data() {

        if ( ! is_admin() ) {

            // all property city terms
            $all_locations = get_terms( 'property-city', array(
                'hide_empty' => false,
                'orderby' => 'count',
                'order' => 'desc',
            ));

            // select boxes names
            $location_select_names = inspiry_get_location_select_names();
            $location_select_count = inspiry_get_locations_number();

            // location parameters in request, if any
            $locations_in_params = array();
            foreach ( $location_select_names as $location_name ) {
                if( isset( $_GET[ $location_name ] ) ) {
                    $locations_in_params[ $location_name ] = $_GET[ $location_name ];
                }
            }

            // combine all data into one array
            $location_data_array = array(
                'any' => __('(Any)','inspiry'),
                'all_locations' => $all_locations,
                'select_names' => $location_select_names,
                'select_count' => $location_select_count,
                'locations_in_params' => $locations_in_params,
            );

            // provide location data array before property search form script
            wp_localize_script( 'inspiry-search-form', 'locationData', $location_data_array );

        }
    }

    add_action( 'inspiry_after_location_fields', 'inspiry_generate_location_data' );

}



if( !function_exists( 'inspiry_generate_taxonomy_options' ) ){
    /**
     * Output select options for terms in a given taxonomy
     * @param $taxonomy_name    string taxonomy name
     * @param $taxonomy_title   string taxonomy title
     */
    function inspiry_generate_taxonomy_options( $taxonomy_name, $taxonomy_title ) {

        $taxonomy_terms = get_terms( $taxonomy_name, array (
            'hide_empty' => false,
            'parent' => 0,
        ));

        $searched_term = '';

        if( $taxonomy_name == 'property-city' ){
            if( !empty( $_GET['location'] ) ){
                $searched_term = $_GET['location'];
            }
        }

        if( $taxonomy_name == 'property-type' ){
            if( !empty( $_GET['type'] ) ){
                $searched_term = $_GET['type'];
            }
        }

        if( $taxonomy_name == 'property-status' ){
            if( !empty( $_GET['status'] ) ){
                $searched_term = $_GET['status'];
            }
        }

        if ( $searched_term == 'any' || empty( $searched_term ) ) {
            echo '<option value="any" selected="selected">' . $taxonomy_title . ' ' . __( '(Any)', 'inspiry') . '</option>';
        } else {
            echo '<option value="any">' . $taxonomy_title . ' ' . __( '(Any)', 'inspiry') . '</option>';
        }

        if ( ! empty( $taxonomy_terms ) && ! is_wp_error( $taxonomy_terms ) ){
            inspiry_hierarchical_options( $taxonomy_name, $taxonomy_terms, $searched_term);
        }


    }
}



if( !function_exists( 'inspiry_hierarchical_options' ) ){
    /**
     * Output hierarchical select options with selection based on slug
     *
     * @param $taxonomy_name
     * @param $taxonomy_terms
     * @param $searched_term
     * @param string $prefix
     */
    function inspiry_hierarchical_options( $taxonomy_name, $taxonomy_terms, $searched_term, $prefix = " " ){

        if ( ! empty( $taxonomy_terms ) && ! is_wp_error( $taxonomy_terms ) ){

            foreach ( $taxonomy_terms as $term ) {

                if ( $searched_term == $term->slug ) {
                    echo '<option value="' . $term->slug . '" selected="selected">' . $prefix . $term->name . '</option>';
                } else {
                    echo '<option value="' . $term->slug . '">' . $prefix . $term->name . '</option>';
                }

                $child_terms = get_terms( $taxonomy_name, array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => false,
                    'parent' => $term->term_id
                ) );

                if ( ! empty( $child_terms ) && !is_wp_error( $child_terms ) ){
                    /* Recursive Call */
                    inspiry_hierarchical_options( $taxonomy_name, $child_terms, $searched_term, "- ".$prefix );
                }

            }

        }

    }
}



if( !function_exists( 'inspiry_hierarchical_id_options' ) ){
    /**
     * Output hierarchical select options with selection based on term id
     * @param $taxonomy_name
     * @param $taxonomy_terms
     * @param $target_term_id
     * @param string $prefix
     */
    function inspiry_hierarchical_id_options($taxonomy_name, $taxonomy_terms, $target_term_id, $prefix = " " ){

        if ( ! empty( $taxonomy_terms ) && ! is_wp_error( $taxonomy_terms ) ){

            foreach ( $taxonomy_terms as $term ) {
                if ( $target_term_id == $term->term_id ) {
                    echo '<option value="' . $term->term_id . '" selected="selected">' . $prefix . $term->name . '</option>';
                } else {
                    echo '<option value="' . $term->term_id . '">' . $prefix . $term->name . '</option>';
                }
                $child_terms = get_terms( $taxonomy_name, array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => false,
                    'parent' => $term->term_id
                ) );

                if ( ! empty( $child_terms ) && !is_wp_error( $child_terms ) ){
                    /* Recursive Call */
                    inspiry_hierarchical_id_options( $taxonomy_name, $child_terms, $target_term_id, "- ".$prefix );
                }
            }

        }

    }
}



if ( !function_exists( 'inspiry_hierarchical_edit_options' ) ) {
    /**
     * Property edit form hierarchical taxonomy options
     *
     * @param $property_id
     * @param $taxonomy_name
     */
    function inspiry_hierarchical_edit_options( $property_id, $taxonomy_name ){

        $existing_term_id = 0;
        $tax_terms = get_the_terms( $property_id, $taxonomy_name );
        if ( !empty( $tax_terms ) && !is_wp_error( $tax_terms ) ) {
            foreach( $tax_terms as $tax_term ) {
                $existing_term_id = $tax_term->term_id;
                break;
            }
        }

        $existing_term_id = intval( $existing_term_id );
        if ( $existing_term_id == 0 || empty( $existing_term_id ) ) {
            echo '<option value="-1" selected="selected">' . __( 'None', 'inspiry' ) . '</option>';
        } else {
            echo '<option value="-1">' . __( 'None', 'inspiry' ) . '</option>';
        }

        $top_level_terms = get_terms(
            array(
                $taxonomy_name
            ),
            array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => 0,
            )
        );

        inspiry_hierarchical_id_options( $taxonomy_name, $top_level_terms, $existing_term_id );

    }
}



if( !function_exists( 'inspiry_number_options' ) ) {
    /**
     * Output select options for bedrooms and bathrooms
     * @param $options_for  string  Options are generated for ( bedrooms or bathrooms )
     * @param $any_title    string  Title for option with value any
     */
    function inspiry_number_options( $options_for, $any_title  ) {

        $numbers_array = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 );
        $searched_value = '';

        if ( $options_for == 'bedrooms' ) {
            $numbers_array = apply_filters( 'inspiry_search_bedrooms', $numbers_array );
            if( isset( $_GET['bedrooms'] ) ) {
                $searched_value = $_GET['bedrooms'];
            }
        } elseif ( $options_for == 'bathrooms' ) {
            $numbers_array = apply_filters( 'inspiry_search_bathrooms', $numbers_array );
            if(isset($_GET['bathrooms'])) {
                $searched_value = $_GET['bathrooms'];
            }
        }

        if ( $searched_value == 'any' || empty( $searched_value ) ) {
            echo '<option value="any" selected="selected">'.$any_title.'</option>';
        } else {
            echo '<option value="any">'.$any_title.'</option>';
        }

        if ( !empty( $numbers_array ) ) {
            foreach ( $numbers_array as $number ) {
                if( $searched_value == $number ) {
                    echo '<option value="'.$number.'" selected="selected">'.$number.'</option>';
                } else {
                    echo '<option value="'.$number.'">'.$number.'</option>';
                }
            }
        }

    }
}



if( !function_exists( 'inspiry_minimum_prices_options' ) ) {
    /**
     * Output options for minimum price select box in property search form
     */
    function inspiry_minimum_prices_options() {
        global $inspiry_options;
        $min_prices = array( 1000, 5000, 10000, 50000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000, 1500000, 2000000, 2500000, 5000000 );

        // Get values from theme options and convert them to an integer array
        $inspiry_min_prices = $inspiry_options[ 'inspiry_minimum_prices' ];
        if ( !empty( $inspiry_min_prices ) ) {
            $min_prices_str = explode(',',$inspiry_min_prices);
            if ( is_array( $min_prices_str ) && !empty( $min_prices_str ) ) {
                $new_min_prices = array();
                foreach ( $min_prices_str as $price_str ){
                    $price_num = doubleval( $price_str );
                    if ( $price_num > 1 ) {
                        $new_min_prices[] = $price_num;
                    }
                }
                if ( !empty( $new_min_prices ) ) {
                    $min_prices = $new_min_prices;
                }
            }
        }

        $mini_price_parameter = '';
        if(isset($_GET['min-price'])){
            $mini_price_parameter = doubleval( $_GET['min-price'] );
        }

        if( $mini_price_parameter == 'any' || empty( $mini_price_parameter ) ) {
            echo '<option value="any" selected="selected">' . __( 'Min Price (Any)', 'inspiry') . '</option>';
        } else {
            echo '<option value="any">' . __( 'Min Price (Any)', 'inspiry') .'</option>';
        }

        if ( class_exists( 'Inspiry_Property' ) ) {
            if( !empty( $min_prices ) ) {
                foreach( $min_prices as $price ){
                    if( $mini_price_parameter == $price ){
                        echo '<option value="'.$price.'" selected="selected">' . Inspiry_Property::format_price( $price ) . '</option>';
                    }else {
                        echo '<option value="'.$price.'">' . Inspiry_Property::format_price( $price ) . '</option>';
                    }
                }
            }
        }

    }
}



if( !function_exists( 'inspiry_maximum_prices_options' ) ) {
    /**
     * Output options for maximum price select box in property search form
     */
    function inspiry_maximum_prices_options() {
        global $inspiry_options;
        $max_prices = array( 5000, 10000, 50000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000, 1500000, 2000000, 2500000, 5000000, 10000000 );

        // Get values from theme options and convert them to an integer array
        $inspiry_max_prices = $inspiry_options[ 'inspiry_maximum_prices' ];
        if ( !empty( $inspiry_max_prices ) ) {
            $max_prices_strs = explode( ',', $inspiry_max_prices );
            if ( is_array( $max_prices_strs ) && !empty( $max_prices_strs ) ) {
                $new_max_prices = array();
                foreach ( $max_prices_strs as $price_str ) {
                    $price_num = doubleval( $price_str );
                    if ( $price_num > 1 ) {
                        $new_max_prices[] = $price_num;
                    }
                }
                if ( !empty( $new_max_prices ) ) {
                    $max_prices = $new_max_prices;
                }
            }
        }

        $maximum_price = '';
        if ( isset( $_GET['max-price'] ) ) {
            $maximum_price = doubleval( $_GET['max-price'] );
        }

        if ( $maximum_price == 'any' || empty( $maximum_price ) ) {
            echo '<option value="any" selected="selected">' . __( 'Max Price (Any)', 'inspiry' ) . '</option>';
        } else {
            echo '<option value="any">' . __( 'Max Price (Any)', 'inspiry' ) . '</option>';
        }

        if ( !empty ( $max_prices ) ) {
            foreach( $max_prices as $price ) {
                if ( $maximum_price == $price ) {
                    echo '<option value="' . $price . '" selected="selected">' . Inspiry_Property::format_price( $price ) . '</option>';
                } else {
                    echo '<option value="' . $price . '">' . Inspiry_Property::format_price( $price ) . '</option>';
                }
            }
        }

    }
}



if( !function_exists( 'inspiry_property_search' ) ) {
    /**
     * Offers property search functionality
     *
     * @param $search_args  Array   search arguments
     * @return mixed    Array   modified search arguments
     */
    function inspiry_property_search( $search_args ) {

        /* taxonomy query and meta query arrays */
        $tax_query = array();
        $meta_query = array();

        /* Keyword Based Search */
        if( isset ( $_GET['keyword'] ) ) {
            $keyword = trim( $_GET['keyword'] );
            if ( ! empty( $keyword ) ) {
                $search_args['s'] = $keyword;
            }
        }

        /* property type taxonomy query */
        if( ( !empty( $_GET['type'] ) ) && ( $_GET['type'] != 'any') ){
            $tax_query[] = array(
                'taxonomy' => 'property-type',
                'field' => 'slug',
                'terms' => $_GET['type']
            );
        }

        /* property location taxonomy query */
        $location_select_names = inspiry_get_location_select_names();
        $locations_count = count( $location_select_names );
        for ( $l = $locations_count - 1; $l >= 0; $l-- ) {
            if( isset( $_GET[ $location_select_names[$l] ] ) ){
                $current_location = $_GET[ $location_select_names[$l] ];
                if( ( ! empty ( $current_location ) ) && ( $current_location != 'any' ) ){
                    $tax_query[] = array (
                        'taxonomy' => 'property-city',
                        'field' => 'slug',
                        'terms' => $current_location
                    );
                    break;
                }
            }
        }

        /* property feature taxonomy query */
        if ( isset( $_GET['features'] ) ) {
            $required_features_slugs = $_GET['features'];
            if ( is_array ( $required_features_slugs ) ) {

                $slugs_count = count ( $required_features_slugs );
                if ( $slugs_count > 0 ) {

                    /* build an array of existing features slugs to validate required feature slugs */
                    $existing_features_slugs = array();
                    $existing_features = get_terms( 'property-feature', array( 'hide_empty' => false ) );
                    $existing_features_count = count ( $existing_features );
                    if ( $existing_features_count > 0 ) {
                        foreach ($existing_features as $feature) {
                            $existing_features_slugs[] = $feature->slug;
                        }
                    }

                    foreach ( $required_features_slugs as $feature_slug ) {
                        if( in_array( $feature_slug, $existing_features_slugs ) ){  // validate slug
                            $tax_query[] = array (
                                'taxonomy' => 'property-feature',
                                'field' => 'slug',
                                'terms' => $feature_slug
                            );
                        }
                    }
                }
            }

        }

        /* property status taxonomy query */
        if((!empty($_GET['status'])) && ( $_GET['status'] != 'any' ) ){
            $tax_query[] = array(
                'taxonomy' => 'property-status',
                'field' => 'slug',
                'terms' => $_GET['status']
            );
        }

        /* Property Bedrooms Parameter */
        if((!empty($_GET['bedrooms'])) && ( $_GET['bedrooms'] != 'any' ) ){
            $meta_query[] = array(
                'key' => 'REAL_HOMES_property_bedrooms',
                'value' => $_GET['bedrooms'],
                'compare' => '>=',
                'type'=> 'DECIMAL'
            );
        }

        /* Property Bathrooms Parameter */
        if((!empty($_GET['bathrooms'])) && ( $_GET['bathrooms'] != 'any' ) ){
            $meta_query[] = array(
                'key' => 'REAL_HOMES_property_bathrooms',
                'value' => $_GET['bathrooms'],
                'compare' => '>=',
                'type'=> 'DECIMAL'
            );
        }

        /* Property ID Parameter */
        if( isset($_GET['property-id']) && !empty($_GET['property-id'])){
            $property_id = trim($_GET['property-id']);
            $meta_query[] = array(
                'key' => 'REAL_HOMES_property_id',
                'value' => $property_id,
                'compare' => 'LIKE',
                'type'=> 'CHAR'
            );
        }

        /* Logic for Min and Max Price Parameters */
        if( isset($_GET['min-price']) && ($_GET['min-price'] != 'any') && isset($_GET['max-price']) && ($_GET['max-price'] != 'any') ){
            $min_price = doubleval($_GET['min-price']);
            $max_price = doubleval($_GET['max-price']);
            if( $min_price >= 0 && $max_price > $min_price ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_price',
                    'value' => array( $min_price, $max_price ),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN'
                );
            }
        }elseif( isset($_GET['min-price']) && ($_GET['min-price'] != 'any') ){
            $min_price = doubleval($_GET['min-price']);
            if( $min_price > 0 ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_price',
                    'value' => $min_price,
                    'type' => 'NUMERIC',
                    'compare' => '>='
                );
            }
        }elseif( isset($_GET['max-price']) && ($_GET['max-price'] != 'any') ){
            $max_price = doubleval($_GET['max-price']);
            if( $max_price > 0 ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_price',
                    'value' => $max_price,
                    'type' => 'NUMERIC',
                    'compare' => '<='
                );
            }
        }


        /* Logic for Min and Max Area Parameters */
        if( isset($_GET['min-area']) && !empty($_GET['min-area']) && isset($_GET['max-area']) && !empty($_GET['max-area']) ){
            $min_area = intval($_GET['min-area']);
            $max_area = intval($_GET['max-area']);
            if( $min_area >= 0 && $max_area > $min_area ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_size',
                    'value' => array( $min_area, $max_area ),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN'
                );
            }
        }elseif( isset($_GET['min-area']) && !empty($_GET['min-area']) ){
            $min_area = intval($_GET['min-area']);
            if( $min_area > 0 ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_size',
                    'value' => $min_area,
                    'type' => 'NUMERIC',
                    'compare' => '>='
                );
            }
        }elseif( isset($_GET['max-area']) && !empty($_GET['max-area']) ){
            $max_area = intval($_GET['max-area']);
            if( $max_area > 0 ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_size',
                    'value' => $max_area,
                    'type' => 'NUMERIC',
                    'compare' => '<='
                );
            }
        }


        /* if more than one taxonomies exist then specify the relation */
        $tax_count = count( $tax_query );
        if( $tax_count > 1 ){
            $tax_query['relation'] = 'AND';
        }

        /* if more than one meta query elements exist then specify the relation */
        $meta_count = count( $meta_query );
        if( $meta_count > 1 ){
            $meta_query['relation'] = 'AND';
        }

        if( $tax_count > 0 ){
            $search_args['tax_query'] = $tax_query;
        }

        /* if meta query has some values then add it to base home page query */
        if( $meta_count > 0 ){
            $search_args['meta_query'] = $meta_query;
        }

        /* Sort By Price */
        if( (isset($_GET['min-price']) && ($_GET['min-price'] != 'any')) || ( isset($_GET['max-price']) && ($_GET['max-price'] != 'any') ) ){
            $search_args['orderby'] = 'meta_value_num';
            $search_args['meta_key'] = 'REAL_HOMES_property_price';
            $search_args['order'] = 'ASC';
        }

        return $search_args;
    }
    add_filter( 'inspiry_property_search', 'inspiry_property_search' );
}



if( !function_exists( 'inspiry_sort_properties' ) ){
    /**
     * Add sorting parameters to query arguments
     *
     * @param $properties_query_args  Array   query arguments
     * @return mixed    Array   modified query arguments
     */
    function inspiry_sort_properties( $properties_query_args ) {

        global $inspiry_options;
        $sort_by = null;

        if ( isset( $_GET['sortby'] ) ) {
            $sort_by = $_GET['sortby'];
        } else {
            if ( is_page_template( 'page-templates/properties-search.php' ) ) {
                $sort_by = $inspiry_options[ 'inspiry_search_order' ];
            } elseif ( is_page_template( array (
                'page-templates/properties-list.php',
                'page-templates/properties-list-with-sidebar.php',
                'page-templates/properties-grid.php',
                'page-templates/properties-grid-with-sidebar.php' ) ) ) {
                $sort_by = get_post_meta( get_the_ID(), 'inspiry_properties_order', true );
            } elseif ( is_tax( 'property-city' ) || is_tax( 'property-status' ) || is_tax( 'property-type' ) || is_tax( 'property-feature' ) ) {
                $sort_by = $inspiry_options[ 'inspiry_archive_order' ];
            } elseif ( is_post_type_archive( 'property' ) ) {
                $sort_by = $inspiry_options[ 'inspiry_archive_order' ];
            }
        }

        if ( $sort_by == 'price-asc' ) {
            $properties_query_args['orderby'] = 'meta_value_num';
            $properties_query_args['meta_key'] = 'REAL_HOMES_property_price';
            $properties_query_args['order'] = 'ASC';
        } elseif ( $sort_by == 'price-desc' ) {
            $properties_query_args['orderby'] = 'meta_value_num';
            $properties_query_args['meta_key'] = 'REAL_HOMES_property_price';
            $properties_query_args['order'] = 'DESC';
        } elseif ( $sort_by == 'date-asc' ) {
            $properties_query_args['orderby'] = 'date';
            $properties_query_args['order'] = 'ASC';
        } elseif ( $sort_by == 'date-desc' ) {
            $properties_query_args['orderby'] = 'date';
            $properties_query_args['order'] = 'DESC';
        }

        return $properties_query_args;
    }
    add_filter( 'inspiry_sort_properties', 'inspiry_sort_properties' );
}


if ( !function_exists( 'inspiry_generate_cpt_options' ) ) {
    /**
     * Inspiry generate options based on given query arguments or CPT name
     *
     * @param $post_args
     * @param int $selected
     */
    function inspiry_generate_cpt_options( $post_args, $selected = 0 ) {

        $defaults = array( 'posts_per_page' => -1 );

        if ( is_array( $post_args ) ) {
            $post_args = wp_parse_args( $post_args, $defaults );
        } else {
            $post_args = wp_parse_args( array( 'post_type' => $post_args ), $defaults );
        }

        $posts = get_posts( $post_args );
        foreach ( $posts as $post ) :
            ?><option value="<?php echo esc_attr( $post->ID ); ?>" <?php if( isset( $selected ) && ( $selected == $post->ID ) ) { echo "selected"; } ?>><?php echo esc_html( $post->post_title ); ?></option><?php
        endforeach;
    }
}



if( !function_exists( 'inspiry_properties_filter' ) ) {
    /**
     * Add properties filter parameters to given query arguments
     *
     * @param $properties_query_args  Array   query arguments
     * @return mixed    Array   modified query arguments
     */
    function inspiry_properties_filter( $properties_query_args ) {

        $page_id = get_the_ID();
        $tax_query = array();
        $meta_query = array();

        /*
         * number of properties on each page
         */
        $number_of_properties = get_post_meta( $page_id, 'inspiry_posts_per_page', true );
        if ( $number_of_properties ) {
            $number_of_properties = intval( $number_of_properties );
            if( $number_of_properties < 1 ) {
                $properties_query_args['posts_per_page'] = 6;
            } else {
                $properties_query_args['posts_per_page'] = $number_of_properties;
            }
        } else {
            $properties_query_args['posts_per_page'] = 6;
        }


        /*
         * Locations
         */
        $locations = get_post_meta( $page_id, 'inspiry_properties_locations', false );
        if ( !empty( $locations ) && is_array( $locations ) ) {
            $tax_query[] = array (
                'taxonomy' => 'property-city',
                'field' => 'slug',
                'terms' => $locations
            );
        }

        /*
         * Statuses
         */
        $statuses = get_post_meta( $page_id, 'inspiry_properties_statuses', false );
        if ( !empty( $statuses ) && is_array( $statuses ) ) {
            $tax_query[] = array (
                'taxonomy'  => 'property-status',
                'field'     => 'slug',
                'terms'     => $statuses
            );
        }

        /*
         * Types
         */
        $types = get_post_meta( $page_id, 'inspiry_properties_types', false );
        if ( !empty( $types ) && is_array( $types ) ) {
            $tax_query[] = array (
                'taxonomy'  => 'property-type',
                'field'     => 'slug',
                'terms'     => $types
            );
        }

        /*
         * Features
         */
        $features = get_post_meta( $page_id, 'inspiry_properties_features', false );
        if ( !empty( $features ) && is_array( $features ) ) {
            $tax_query[] = array (
                'taxonomy'  => 'property-feature',
                'field'     => 'slug',
                'terms'     => $features
            );
        }

        // if more than one taxonomies exist then specify the relation
        $tax_count = count( $tax_query );
        if( $tax_count > 1 ){
            $tax_query['relation'] = 'AND';
        }
        if( $tax_count > 0 ){
            $properties_query_args['tax_query'] = $tax_query;
        }

        /*
         * Minimum Bedrooms
         */
        $min_beds = get_post_meta( $page_id, 'inspiry_properties_min_beds', true );
        if ( !empty( $min_beds ) ) {
            $min_beds = intval( $min_beds );
            if ( $min_beds > 0 ) {
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_bedrooms',
                    'value' => $min_beds,
                    'compare' => '>=',
                    'type'=> 'DECIMAL'
                );
            }
        }

        /*
         * Minimum Bathrooms
         */
        $min_baths = get_post_meta( $page_id, 'inspiry_properties_min_baths', true );
        if ( !empty( $min_baths ) ) {
            $min_baths = intval( $min_baths );
            if ( $min_baths > 0 ) {
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_bathrooms',
                    'value' => $min_baths,
                    'compare' => '>=',
                    'type'=> 'DECIMAL'
                );
            }
        }

        /*
         * Min & Max Price
         */
        $min_price = get_post_meta( $page_id, 'inspiry_properties_min_price', true );
        $max_price = get_post_meta( $page_id, 'inspiry_properties_max_price', true );
        if( !empty( $min_price ) && !empty( $max_price ) ) {
            $min_price = doubleval( $min_price );
            $max_price = doubleval( $max_price );
            if ( $min_price >= 0 && $max_price > $min_price ) {
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_price',
                    'value' => array( $min_price, $max_price ),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN'
                );
            }
        } elseif ( !empty( $min_price ) ) {
            $min_price = doubleval( $min_price );
            if ( $min_price > 0 ) {
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_price',
                    'value' => $min_price,
                    'type' => 'NUMERIC',
                    'compare' => '>='
                );
            }
        } elseif ( !empty( $max_price ) ) {
            $max_price = doubleval( $max_price );
            if( $max_price > 0 ){
                $meta_query[] = array(
                    'key' => 'REAL_HOMES_property_price',
                    'value' => $max_price,
                    'type' => 'NUMERIC',
                    'compare' => '<='
                );
            }
        }

        // if more than one meta query elements exist then specify the relation
        $meta_count = count( $meta_query );
        if ( $meta_count > 1 ) {
            $meta_query['relation'] = 'AND';
        }
        if ( $meta_count > 0 ) {
            $properties_query_args['meta_query'] = $meta_query;
        }

        return $properties_query_args;
    }

    add_filter( 'inspiry_properties_filter', 'inspiry_properties_filter' );
}



if ( !function_exists( 'inspiry_get_terms_array' ) ) {
    /**
     * Returns terms array for a given taxonomy containing key(slug) value(name) pair
     *
     * @param $tax_name
     * @param $terms_array
     */
    function inspiry_get_terms_array( $tax_name, &$terms_array ) {
        $tax_terms = get_terms( $tax_name, array (
            'hide_empty' => false,
        ) );
        inspiry_add_term_children( 0, $tax_terms, $terms_array );
    }
}



if( !function_exists( 'inspiry_add_term_children' ) ) :
    /**
     * A recursive function to add children terms to given array
     *
     * @param $parent_id
     * @param $tax_terms
     * @param $terms_array
     * @param string $prefix
     */
    function inspiry_add_term_children( $parent_id, $tax_terms, &$terms_array, $prefix = '' ) {
        if ( !empty( $tax_terms ) && !is_wp_error( $tax_terms ) ) {
            foreach ( $tax_terms as $term ) {
                if ( $term->parent ==  $parent_id ) {
                    $terms_array[ $term->slug ] = $prefix . $term->name;
                    inspiry_add_term_children( $term->term_id, $tax_terms, $terms_array, $prefix . '- ' );
                }
            }
        }
    }
endif;



if( !function_exists( 'inspiry_home_properties_filter' ) ) {
    /**
     * Add home properties filter parameters to given query arguments
     *
     * @param $properties_query_args  Array   query arguments
     * @return mixed    Array   modified query arguments
     */
    function inspiry_home_properties_filter( $properties_query_args ) {

        global $inspiry_options;

        /*
         * Sorting
         */
        $sort_by = null;
        if ( isset( $inspiry_options[ 'inspiry_home_properties_order' ] ) ) {
            $sort_by = $inspiry_options[ 'inspiry_home_properties_order' ];
            if ( $sort_by == 'price-asc' ) {
                $properties_query_args['orderby'] = 'meta_value_num';
                $properties_query_args['meta_key'] = 'REAL_HOMES_property_price';
                $properties_query_args['order'] = 'ASC';
            } elseif ( $sort_by == 'price-desc' ) {
                $properties_query_args['orderby'] = 'meta_value_num';
                $properties_query_args['meta_key'] = 'REAL_HOMES_property_price';
                $properties_query_args['order'] = 'DESC';
            } elseif ( $sort_by == 'date-asc' ) {
                $properties_query_args['orderby'] = 'date';
                $properties_query_args['order'] = 'ASC';
            } elseif ( $sort_by == 'date-desc' ) {
                $properties_query_args['orderby'] = 'date';
                $properties_query_args['order'] = 'DESC';
            }
        }

        $properties_kind = $inspiry_options[ 'inspiry_home_properties_kind' ];

        if ( $properties_kind == 'default' ) {
            /*
             * Exclude Featured Properties
             */
            if( ( $inspiry_options[ 'inspiry_home_exclude_featured' ] ) ) {
                $properties_query_args['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'REAL_HOMES_featured',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'REAL_HOMES_featured',
                        'value' => 0,
                        'compare' => '=',
                        'type' => 'NUMERIC'
                    ));
            }
        } elseif ( $properties_kind == 'featured' ) {
            /*
             * Featured Properties
             */
            $properties_query_args['meta_query'] = array(
                array(
                    'key'       => 'REAL_HOMES_featured',
                    'value'     => 1,
                    'compare'   => '=',
                    'type'      => 'NUMERIC'
                )
            );

        } elseif ( $properties_kind == 'selection' ) {
            /*
             * custom selection
             */
            $tax_query = array();

            // Locations
            if( !empty( $inspiry_options[ 'inspiry_home_properties_locations' ] ) ) {
                $tax_query[] = array(
                    'taxonomy'  => 'property-city',
                    'field'     => 'term_id',
                    'terms'     => $inspiry_options[ 'inspiry_home_properties_locations' ]
                );
            }

            // Statuses
            if( !empty( $inspiry_options[ 'inspiry_home_properties_statuses' ] ) ) {
                $tax_query[] = array(
                    'taxonomy'  => 'property-status',
                    'field'     => 'term_id',
                    'terms'     => $inspiry_options[ 'inspiry_home_properties_statuses' ]
                );
            }

            // Types
            if( !empty( $inspiry_options[ 'inspiry_home_properties_types' ] ) ) {
                $tax_query[] = array(
                    'taxonomy'  => 'property-type',
                    'field'     => 'term_id',
                    'terms'     => $inspiry_options[ 'inspiry_home_properties_types' ]
                );
            }

            $tax_count = count( $tax_query );

            // Add relation
            if( $tax_count > 1 ){
                $tax_query['relation'] = 'AND';
            }

            // Add taxonomy query to main query
            if( $tax_count > 0 ){
                $properties_query_args['tax_query'] = $tax_query;
            }

        }

        return $properties_query_args;
    }

    add_filter( 'inspiry_home_properties', 'inspiry_home_properties_filter' );
}


if( !function_exists( 'inspiry_wpml_translated_page_id' ) ) :
    /**
     * Returns translated page id if exists otherwise default page id
     *
     * @param $default_page_id
     * @return int
     */
    function inspiry_wpml_translated_page_id( $default_page_id ) {

	    /* WPML filter to get translated page id if translation exists otherwise default id */
        return apply_filters( 'wpml_object_id', $default_page_id, 'page', true  );

    }

endif;



if( !function_exists( 'inspiry_mortgage_calculator_amount' ) ) :
    /**
     * Function to pass property price value to mortgage calculator
     */
    function inspiry_mortgage_calculator_amount( $mortgage_amount ) {
        if ( is_singular( 'property' ) ) {
            global $post;
            /* get property price */
            $price_digits = doubleval( get_post_meta( $post->ID, 'REAL_HOMES_property_price', true ) );
            if ( $price_digits ) {
                return $price_digits;
            }
        }
        return $mortgage_amount;
    }
    add_filter( 'mc_total_amount', 'inspiry_mortgage_calculator_amount' );
endif;
