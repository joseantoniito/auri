<?php
/*
 * This file contains basic utility functions used throughout the theme
 */

if ( ! function_exists( 'inspiry_generate_background' ) ) :
    /**
     * Generate background styles
     *
     * @since 1.0.0
     * @param null $color
     * @param null $url
     */
    function inspiry_generate_background( $color = null, $url = null ) {
        if ( !empty( $url ) && !empty( $color ) ) {
            echo 'background: url(' . esc_url( $url ) . ') ' . $color . ' no-repeat center top; background-size:cover;';
        } elseif ( !empty( $url ) ) {
            echo 'background: url(' . esc_url( $url ) . ') no-repeat center top; background-size:cover;';
        } elseif ( !empty( $color ) ) {
            echo 'background-color:' . $color . ';';
        }
    }

endif;



if ( ! function_exists( 'inspiry_animation_class' ) ) :
    /**
     * Return animation class to enable animation.
     *
     * @since 1.0.0
     * @param   bool $generate
     * @return  string
     */
    function inspiry_animation_class( $generate = false ) {
        global $inspiry_options;
        if ( $generate || ( $inspiry_options['inspiry_animation'] == 1 ) ) {
            return 'animated';
        }
        return '';
    }

endif;



if ( !function_exists( 'inspiry_standard_thumbnail' ) ) :
    /**
     * Generate standard thumbnail for this theme
     *
     * @since 1.0.0
     * @param   string  $size
     */
    function inspiry_standard_thumbnail( $size = 'post-thumbnail' ) {

        global $post;

        if ( has_post_thumbnail( $post->ID ) ) :

            if ( is_single() ) :
                $featured_image_id = get_post_thumbnail_id();
                $original_image_url = wp_get_attachment_url( $featured_image_id );
                ?>
                <figure class="entry-thumbnail">
                    <a  class="swipebox" href="<?php echo esc_url( $original_image_url ); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail( $size, array('class'=>"img-responsive") ); ?>
                    </a>
                </figure>
                <?php
            else :
                ?>
                <figure class="entry-thumbnail">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
                        <?php the_post_thumbnail( $size, array( 'class' => 'img-responsive' ) ); ?>
                    </a>
                </figure>
                <?php
            endif;

        endif;
    }

endif;



if( ! function_exists( 'inspiry_standard_gallery' ) ) :
    /**
     * Get list of gallery images
     *
     * @since 1.0.0
     * @param string $size
     */
    function inspiry_standard_gallery( $size = 'post-thumbnail' ) {

        global $post;

        $gallery_images = inspiry_get_post_meta( 'REAL_HOMES_gallery', array( 'type' => 'image_advanced', 'size' => $size ), $post->ID );

        if( ! empty( $gallery_images ) ) {

            echo '<div class="blog-gallery-slider gallery-slider flexslider">';
                echo '<ul class="slides list-unstyled">';

                foreach( $gallery_images as $gallery_image ) {
                    $caption = ( !empty( $gallery_image['caption'] ) ) ? $gallery_image['caption'] : $gallery_image['alt'];
                    echo '<li><a class="swipebox" data-rel="gallery-' . $post->ID . '" href="' . esc_url( $gallery_image['full_url'] ) . '" title="' . $caption . '" >';
                    echo '<img src="' . esc_url( $gallery_image['url'] ) .'" alt="' . $gallery_image['title'] . '" />';
                    echo '</a></li>';
                }

                echo '</ul>';
            echo '</div>';

        } else if ( has_post_thumbnail( $post->ID ) ) {

            inspiry_standard_thumbnail( $size );

        }

    }

endif;



if( ! function_exists( 'inspiry_get_post_meta' ) ) :
    /**
     * Get post meta
     *
     * @since 1.0.0
     * @param string   $key     Meta key. Required.
     * @param int|null $post_id Post ID. null for current post. Optional
     * @param array    $args    Array of arguments. Optional.
     *
     * @return mixed
     */
    function inspiry_get_post_meta( $key, $args = array(), $post_id = null ) {

        $post_id = empty( $post_id ) ? get_the_ID() : $post_id;
        $args = wp_parse_args( $args, array( 'type' => 'text', 'multiple' => false, ) );

        // Always set 'multiple' true for following field types
        if ( in_array( $args['type'], array('checkbox_list', 'file', 'file_advanced', 'image', 'image_advanced', 'plupload_image', 'thickbox_image') ) ) {
            $args['multiple'] = true;
        }

        $meta = get_post_meta( $post_id, $key, !$args['multiple'] );

        // Get uploaded files info
        if (in_array($args['type'], array('file', 'file_advanced'))) {

            if ( is_array( $meta ) && !empty( $meta ) ) {
                $files = array();
                foreach ($meta as $id) {
                    // Get only info of existing attachments
                    if (get_attached_file($id)) {
                        $files[$id] = inspiry_get_file_info($id);
                    }
                }
                $meta = $files;
            }

        // Get uploaded images info
        } elseif (in_array($args['type'], array('image', 'plupload_image', 'thickbox_image', 'image_advanced'))) {

            if (is_array($meta) && !empty($meta)) {
                $images = array();
                foreach ($meta as $id) {
                    // Get only info of existing attachments
                    if (get_attached_file($id)) {
                        $images[$id] = inspiry_get_file_info($id, $args);
                    }
                }
                $meta = $images;
            }

        // Get terms
        } elseif ('taxonomy_advanced' == $args['type']) {

            if (!empty($args['taxonomy'])) {
                $term_ids = array_map('intval', array_filter(explode(',', $meta . ',')));
                // Allow to pass more arguments to "get_terms"
                $func_args = wp_parse_args(array(
                    'include' => $term_ids,
                    'hide_empty' => false,
                ), $args);
                unset($func_args['type'], $func_args['taxonomy'], $func_args['multiple']);
                $meta = get_terms($args['taxonomy'], $func_args);
            } else {
                $meta = array();
            }

        // Get post terms
        } elseif ( 'taxonomy' == $args['type'] ) {

            $meta = empty( $args['taxonomy'] ) ? array() : wp_get_post_terms( $post_id, $args['taxonomy'] );

        }

        return $meta;
    }

endif;



if( ! function_exists( 'inspiry_get_file_info' ) ) :
    /**
     * Get uploaded file information
     *
     * @since 1.0.0
     * @param int   $file_id Attachment image ID (post ID). Required.
     * @param array $args    Array of arguments (for size).
     *
     * @return array|bool False if file not found. Array of image info on success
     */
    function inspiry_get_file_info( $file_id, $args = array() ) {

        $args = wp_parse_args( $args, array(
            'size' => 'thumbnail',
        ) );

        $img_src = wp_get_attachment_image_src( $file_id, $args['size'] );
        if ( ! $img_src ) {
            return false;
        }

        $attachment = get_post( $file_id );
        $path       = get_attached_file( $file_id );
        return array(
            'ID'          => $file_id,
            'name'        => basename( $path ),
            'path'        => $path,
            'url'         => $img_src[0],
            'width'       => $img_src[1],
            'height'      => $img_src[2],
            'full_url'    => wp_get_attachment_url( $file_id ),
            'title'       => $attachment->post_title,
            'caption'     => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'alt'         => get_post_meta( $file_id, '_wp_attachment_image_alt', true ),
        );
    }

endif;



if ( !function_exists('inspiry_nothing_found') ) :
    /**
     * Display nothing found message
     *
     * @param $message
     */
    function inspiry_nothing_found() {
        ?>
        <section class="no-results not-found">
            <h2><?php esc_html_e( 'Nothing Found', 'inspiry' ); ?></h2>
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'inspiry' ); ?></p>
            <?php get_search_form(); ?>
        </section>
        <!-- .no-results -->
        <?php
    }

endif;



if ( ! function_exists( 'inspiry_pagination' ) ) :
    /**
     * Output pagination
     *
     * @param $query
     */
    function inspiry_pagination( $query ) {

        echo "<div class='pagination'>";

        $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url ( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'prev_text' => __( '<i class="fa fa-angle-left"></i>', 'inspiry' ),
            'next_text' => __( '<i class="fa fa-angle-right"></i>', 'inspiry' ),
            'current' => max( 1, get_query_var ( 'paged' ) ),
            'total' => $query->max_num_pages,
        ) );

        echo "</div>";

    }

endif;



if( !function_exists( 'inspiry_excerpt' ) ) {
    /**
     * Output excerpt for given number of words
     * @param int $len
     * @param string $trim
     */
    function inspiry_excerpt( $len=15, $trim = "&hellip;" ) {
        echo get_inspiry_excerpt( $len, $trim );
    }
}



if( !function_exists( 'get_inspiry_excerpt' ) ) {
    /**
     * Return excerpt for given number of words.
     * @param int $len
     * @param string $trim
     * @return string
     */
    function get_inspiry_excerpt( $len=15, $trim = "&hellip;" ) {
        $limit = $len+1;
        $excerpt = explode( ' ', get_the_excerpt(), $limit );
        $num_words = count( $excerpt );
        if ( $num_words >= $len ) {
            $last_item = array_pop( $excerpt );
        } else {
            $trim="";
        }
        $excerpt = implode( " ", $excerpt ) . $trim ;
        return $excerpt;
    }
}



if( !function_exists( 'get_inspiry_custom_excerpt' ) ) {
    /**
     * Return excerpt for given number of words from custom contents
     * @param string $contents
     * @param int $len
     * @param string $trim
     * @return array|string
     */
    function get_inspiry_custom_excerpt( $contents, $len = 15, $trim = "&hellip;" ){
        $limit = $len+1;
        $excerpt = explode( ' ', $contents, $limit );
        $num_words = count( $excerpt );
        if( $num_words >= $len ){
            $last_item = array_pop( $excerpt );
        } else {
            $trim = "";
        }
        $excerpt = implode( " ", $excerpt ) . $trim;
        return $excerpt;
    }
}


if( !function_exists( 'inspiry_col_animation_class' ) ) {
    /**
     * Provide animation class based on columns and index
     * @param int $number_of_cols   number of columns
     * @param int $col_index    column's index
     * @return string   animation class
     */
    function inspiry_col_animation_class($number_of_cols = 3, $col_index ) {

        // For 1 Column Layout
        if ( $number_of_cols == 1 ) {
            return 'fade-in-up';
        }

        // For 2 Columns Layout
        if ( $number_of_cols == 2 ) {
            if ( $col_index % 2 == 0 ) {
                return 'fade-in-right';
            } else {
                return 'fade-in-left';
            }
        }

        // For 3 Columns Layout
        if ( $number_of_cols == 3 ) {
            if ( $col_index % 3 == 0 ) {
                return 'fade-in-right';
            } else if ( $col_index % 3 == 1 ) {
                return 'fade-in-left';
            } else {
                return 'fade-in-up';
            }
        }

        // For 4 Columns Layout
        if ( $number_of_cols == 4 ) {
            if ( $col_index % 4 == 0 ) {
                return 'fade-in-right';
            } else if ( $col_index % 4 == 1 ) {
                return 'fade-in-left';
            } else {
                return 'fade-in-up';
            }
        }

        return 'fade-in-up';

    }
}



/*-----------------------------------------------------------------------------------*/
// Featured image place holder
/*-----------------------------------------------------------------------------------*/
if( !function_exists('get_inspiry_image_placeholder')){
    /**
     * Return place holder image
     * @param $image_size string    image size
     * @param $image_class string   image class
     * @return string   image tag
     */
    function get_inspiry_image_placeholder( $image_size, $image_class = 'img-responsive' ){

        global $_wp_additional_image_sizes;

        $holder_width = 0;
        $holder_height = 0;
        $holder_text = get_bloginfo('name');

        if ( in_array( $image_size , array( 'thumbnail', 'medium', 'large' ) ) ) {

            $holder_width = get_option( $image_size . '_size_w' );
            $holder_height = get_option( $image_size . '_size_h' );

        } elseif ( isset( $_wp_additional_image_sizes[ $image_size ] ) ) {

            $holder_width = $_wp_additional_image_sizes[ $image_size ]['width'];
            $holder_height = $_wp_additional_image_sizes[ $image_size ]['height'];

        }

        if( intval( $holder_width ) > 0 && intval( $holder_height ) > 0 ) {
            $place_holder_final_url = esc_url( add_query_arg( array(
                'text' => urlencode( $holder_text )
            ), sprintf(
                '//placehold.it/%dx%d',
                $holder_width,
                $holder_height
            ) ) );
            return sprintf( '<img class="%s" src="%s" />', $image_class, $place_holder_final_url );
        }

        return '';
    }
}



if( !function_exists( 'inspiry_image_placeholder' ) ) {
    /*
     * Display place holder image.
     */
    function inspiry_image_placeholder( $image_size, $image_class = 'img-responsive' ) {
        echo get_inspiry_image_placeholder( $image_size, $image_class );
    }
}



if( !function_exists( 'inspiry_thumbnail' ) ) :
    /**
     * Display thumbnail
     * @param string $size
     */
    function inspiry_thumbnail( $size = 'inspiry-grid-thumbnail' ) {
        ?>
        <a href="<?php the_permalink(); ?>">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( $size, array( 'class' => 'img-responsive' ) );
            } else {
                inspiry_image_placeholder( $size, 'img-responsive' );
            }
            ?>
        </a>
    <?php
    }
endif;



if( !function_exists( 'inspiry_message' ) ) :
    /**
     * Output given message for visitor
     *
     * @param string $heading
     * @param string $message
     */
    function inspiry_message( $heading = '', $message = '' ) {

        echo '<div class="inspiry-message">';
        if ( !empty( $heading ) ) {
            echo '<h3>' . $heading . '</h3>';
        }
        if ( !empty( $message ) ) {
            echo '<p>' . $message . '</p>';
        }
        echo '</div>';
    }
endif;



if( !function_exists( 'inspiry_highlighted_message' ) ) :
    /**
     * Output given message for visitor with highlighted background
     *
     * @param string $heading
     * @param string $message
     */
    function inspiry_highlighted_message( $heading = '', $message = '' ) {

        echo '<div class="inspiry-highlighted-message">';
        if ( !empty( $heading ) ) {
            echo '<h4>' . $heading . '</h4>';
        }
        if ( !empty( $message ) ) {
            echo '<p>' . $message . '</p>';
        }
        echo '<i class="fa fa-times close-message"></i>';
        echo '</div>';

    }
endif;


if ( !function_exists( 'inspiry_log' ) ) {
    /**
     * Log a given message to wp-content/debug.log file, if debug is enabled from wp-config.php file
     *
     * @param $message
     */
    function inspiry_log( $message ) {
        if ( WP_DEBUG === true ) {
            if ( is_array( $message ) || is_object( $message ) ) {
                error_log( print_r( $message, true ) );
            } else {
                error_log( $message );
            }
        }
    }
}


if ( ! function_exists( 'inspiry_theme_comment' ) ) {
    /**
     * Theme Custom Comment Template
     */
    function inspiry_theme_comment( $comment, $args, $depth ) {

        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                ?>
                <li class="pingback">
                    <p><?php _e('Pingback:', 'inspiry'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'inspiry'), ' '); ?></p>
                </li>
                <?php
                break;

            default :
                ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>" class="comment-body">

                    <div class="author-photo">
                        <a class="avatar" href="<?php comment_author_url(); ?>">
                            <?php echo get_avatar( $comment, 68, '', '', array( 'class' => 'img-circle', ) ); ?>
                        </a>
                    </div>

                    <div class="comment-wrapper">
                        <div class="comment-meta">
                            <div class="comment-author vcard">
                                <h5 class="fn"><?php echo get_comment_author_link(); ?></h5>
                            </div>
                            <div class="comment-metadata">
                                <time datetime="<?php comment_time('c'); ?>"><?php printf( __( '%1$s', 'inspiry' ), get_comment_date() ); ?></time>
                            </div>
                        </div>

                        <div class="comment-content">
                            <?php comment_text(); ?>
                        </div>

                        <div class="reply">
                            <?php comment_reply_link( array_merge( array( 'before' => '' ), array( 'depth' => $depth , 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </div>
                    </div>

                </article>
                <!-- end of comment -->
                <?php
                break;

        endswitch;
    }
}



if( !function_exists( 'real_places_gallery_desc' ) ) :
    /**
     * Filter for gallery images meta box description
     *
     * @return string|void
     */
    function real_places_gallery_desc() {
        return __( 'Images should have minimum size of 850px by 600px. Bigger size images will be cropped automatically.', 'inspiry' );
    }
    add_filter( 'inspiry_gallery_description', 'real_places_gallery_desc' );
endif;



if( !function_exists( 'real_places_slider_desc' ) ) :
    /**
     * Filter for slider image meta box description
     *
     * @return string|void
     */
    function real_places_slider_desc() {
        return __( 'The recommended image size is 2000px by 1000px. You can use a bigger or smaller size but keep the same height to width ratio and use exactly same size images for all slider entries.', 'inspiry' );
    }
    add_filter( 'inspiry_slider_description', 'real_places_slider_desc' );
endif;



if( !function_exists( 'real_places_video_desc' ) ) :
    /**
     * Filter for video image meta box description
     *
     * @return string|void
     */
    function real_places_video_desc() {
        return __( 'Provided image will be used as a video place holder and when user will click on it the video will be opened in a lightbox. Minimum required image size is 850px by 600px.', 'inspiry' );
    }
    add_filter( 'inspiry_video_description', 'real_places_video_desc' );
endif;



if( !function_exists( 'inspiry_home_body_classes' ) ) :
    /**
     * Filter to add header and slider variation classes to body
     */
    function inspiry_home_body_classes( $classes ) {

        global $inspiry_options;

        // class for sticky header
        if ( $inspiry_options[ 'inspiry_sticky_header' ] == '1' ) {
            $classes[] = 'inspiry-sticky-header';
        }

        if ( is_page_template( 'page-templates/home.php' ) ) {

            // For Demo Purposes
            if ( isset( $_GET['module_below_header'] ) ) {
                $inspiry_options[ 'inspiry_home_module_below_header' ] = $_GET['module_below_header'];
                if ( isset( $_GET['module_below_header'] ) ) {
                    $inspiry_options[ 'inspiry_slider_type' ] = $_GET['slider_type'];
                }
            }

            // class for header variation
            if ( $inspiry_options[ 'inspiry_header_variation' ] == '1' ) {
                $classes[] = 'inspiry-header-variation-one';
            } elseif ( $inspiry_options[ 'inspiry_header_variation' ] == '2' ) {
                $classes[] = 'inspiry-header-variation-two';
            } else {
                $classes[] = 'inspiry-header-variation-three';
            }

            // class for module below header
            if ( $inspiry_options[ 'inspiry_home_module_below_header' ] == 'slider' ) {
                $classes[] = 'inspiry-slider-header';

                // class for slider type
                if ( $inspiry_options[ 'inspiry_slider_type' ] == 'revolution-slider' ) {
                    $classes[] = 'inspiry-revolution-slider';
                } elseif ( $inspiry_options[ 'inspiry_slider_type' ] == 'properties-slider-two' ) {
                    $classes[] = 'inspiry-slider-two';
                } elseif ( $inspiry_options[ 'inspiry_slider_type' ] == 'properties-slider-three' ) {
                    $classes[] = 'inspiry-slider-three';
                } else {
                    $classes[] = 'inspiry-slider-one';
                }

            } else if ( $inspiry_options[ 'inspiry_home_module_below_header' ] == 'google-map' ) {
                $classes[] = 'inspiry-google-map-header';
            } else {
                $classes[] = 'inspiry-banner-header';
            }

        } elseif ( is_page_template( 'page-templates/properties-search.php' ) ) {

            if ( $inspiry_options[ 'inspiry_header_variation' ] == '1' ) {
                if ( $inspiry_options[ 'inspiry_search_module_below_header' ] == 'google-map' ) {
                    $classes[] = 'inspiry-google-map-header';
                } else {
                    $classes = inspiry_revolution_slider_class ( $classes );
                }
            }

        } elseif ( is_page_template( 'page-templates/properties-list.php' )
                    || is_page_template( 'page-templates/properties-list-with-sidebar.php' )
                    || is_page_template( 'page-templates/properties-grid.php' )
                    || is_page_template( 'page-templates/properties-grid-with-sidebar.php' ) ) {

            if ( $inspiry_options[ 'inspiry_header_variation' ] == '1' ) {
                $display_google_map = get_post_meta( get_the_ID(), 'inspiry_display_google_map', true );
                if ( $display_google_map ) {
                    $classes[] = 'inspiry-google-map-header';
                } else {
                    $classes = inspiry_revolution_slider_class ( $classes );
                }
            }

        } elseif ( is_page() || is_singular( 'agent' ) ) {

            if ( $inspiry_options[ 'inspiry_header_variation' ] == '1' ) {
                $classes = inspiry_revolution_slider_class ( $classes );
            }

        }

        return $classes;
    }

    add_filter( 'body_class', 'inspiry_home_body_classes' );

endif;


if( !function_exists( 'inspiry_revolution_slider_class' ) ) :
    /**
     * @param $classes
     * @return array
     */
    function inspiry_revolution_slider_class ( $classes ) {
        $revolution_slider_alias = get_post_meta ( get_the_ID (), 'REAL_HOMES_rev_slider_alias', true );
        if ( function_exists ( 'putRevSlider' ) && ( ! empty( $revolution_slider_alias ) ) ) {
            $classes[] = 'inspiry-revolution-slider';
        }
        return $classes;
    }
endif;


if ( ! function_exists( 'inspiry_plugin_update_notice' ) ) :
/**
 * Displays a notice if an update is required for Inspiry Real Estate Plugin
 */
function inspiry_plugin_update_notice() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    if ( is_plugin_active( 'inspiry-real-estate/inspiry-real-estate.php' ) ) {

        $required_version = '1.2.2';
        $inspiry_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/inspiry-real-estate/inspiry-real-estate.php' );
        $current_version = $inspiry_plugin_data['Version'];

        if ( $current_version != $required_version ) {
            add_action( 'admin_notices', function () use ( $current_version, $required_version ) {
                ?>
                <div class="update-nag notice is-dismissible">
                    <p><?php printf( __( 'You are using version %s of Inspiry Real Estate plugin, Required version is %s.', 'inspiry' ), $current_version, $required_version ); ?></p>
                    <p><em><?php _e( 'You can simply deactivate and remove it, After that follow the plugin installation notices to install the updated one included with in the theme.', 'inspiry' ); ?></em></p>
                    <p><?php _e( '* Make sure to save its settings after the installation.', 'inspiry' ); ?></p>
                </div>
                <?php
            } );

        }

    }

}

inspiry_plugin_update_notice();

endif;


if ( ! function_exists( 'inspiry_update_taxonomy_pagination' ) ) {
    /**
     * Update Taxonomy Pagination Based on Number of Properties Provided in Theme Options
     *
     * @param $query
     */
    function inspiry_update_taxonomy_pagination( $query ) {
        if ( is_tax( 'property-type' )
                || is_tax( 'property-status' )
                || is_tax( 'property-city' )
                || is_tax( 'property-feature' ) ) {
            global $inspiry_options;
            if ( $query->is_main_query() ) {
                $number_of_properties = intval( $inspiry_options[ 'inspiry_archive_properties_number' ] );
                if ( !$number_of_properties ) {
                    $number_of_properties = 6;
                }
                $query->set ( 'posts_per_page', $number_of_properties );
            }
        }
    }

    add_action( 'pre_get_posts', 'inspiry_update_taxonomy_pagination' );

}


if ( ! function_exists( 'inspiry_pagination_fix' ) ) :
    /**
     * Pagination fix for agent page
     *
     * @param $redirect_url
     * @return bool
     */
    function inspiry_pagination_fix( $redirect_url ) {
        if ( is_singular( 'agent' ) || is_front_page() ) {
            $redirect_url = false;
        }
        return $redirect_url;
    }

    add_filter( 'redirect_canonical', 'inspiry_pagination_fix' );
endif;