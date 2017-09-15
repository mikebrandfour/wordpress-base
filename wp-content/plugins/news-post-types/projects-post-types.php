<?php
/*
Plugin Name: Projects Post Type
Description: Project Items Post Type
Version: 0.2.0
Author: BrandFour
Author URI: http://brandfour.com
*/


// Register Custom Post Type
function project_post_type() {

  $labels = array(
    'name'                  => _x( 'Projects', 'Post Type General Name', 'text_domain' ),
    'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'text_domain' ),
    'menu_name'             => __( 'Projects', 'text_domain' ),
    'name_admin_bar'        => __( 'Projects', 'text_domain' ),
    'archives'              => __( 'Item Archives', 'text_domain' ),
    'attributes'            => __( 'Item Attributes', 'text_domain' ),
    'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
    'all_items'             => __( 'All Items', 'text_domain' ),
    'add_new_item'          => __( 'Add New Item', 'text_domain' ),
    'add_new'               => __( 'Add New', 'text_domain' ),
    'new_item'              => __( 'New Item', 'text_domain' ),
    'edit_item'             => __( 'Edit Item', 'text_domain' ),
    'update_item'           => __( 'Update Item', 'text_domain' ),
    'view_item'             => __( 'View Item', 'text_domain' ),
    'view_items'            => __( 'View Items', 'text_domain' ),
    'search_items'          => __( 'Search Item', 'text_domain' ),
    'not_found'             => __( 'Not found', 'text_domain' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
    'featured_image'        => __( 'Featured Image', 'text_domain' ),
    'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
    'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
    'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
    'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
    'items_list'            => __( 'Items list', 'text_domain' ),
    'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
    'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
  );
  $args = array(
    'label'                 => __( 'projects', 'text_domain' ),
    'description'           => __( 'Post Type Description', 'text_domain' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author', 'post-formats' ),
    'taxonomies'            => array( 'group', 'post_tag' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,    
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
    'menu_icon'             => 'dashicons-desktop',
  );
  register_post_type( 'projects', $args );

}
add_action( 'init', 'project_post_type', 0 );



add_action( 'init', 'create_tax_type', 0 );

function create_tax_type() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Property Type', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Property Type', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Property Types', 'textdomain' ),
		'all_items'         => __( 'All Property Type', 'textdomain' ),
		'parent_item'       => __( 'Parent Property Type', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Property Type:', 'textdomain' ),
		'edit_item'         => __( 'Edit Property Type', 'textdomain' ),
		'update_item'       => __( 'Update Property Type', 'textdomain' ),
		'add_new_item'      => __( 'Add New Property Type', 'textdomain' ),
		'new_item_name'     => __( 'New Property Type Name', 'textdomain' ),
		'menu_name'         => __( 'Property Type', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'property-type' ),
	);

	register_taxonomy( 'property-type', array('projects'), $args );
}


wp_enqueue_script( 'function-projects', plugin_dir_url(__FILE__) . 'function-projects.js', array(), '1.0.0', true );
wp_localize_script( 'function-projects', 'ajax_posts', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'ppp' => __(8, 'function-projects'), ));




function cs_filter(){
     header("Content-Type: text/html");
     

        $type = stripslashes($_POST['type']);
        // $tax_array = explode( ',', $type );
        $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 99999;
        $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
        $args = array (
            'suppress_filters' => true,
            'post_type'         => 'projects',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'posts_per_page' => $ppp,
            'tax_query' => array(
                array(
                    'taxonomy' => 'property-type',
                    'field' => 'slug',
                    'terms' => $type,
                )
            )
        );

        $loop = new WP_Query( $args );
        if( $loop->have_posts() ): ?>

        <?php
        while ( $loop->have_posts() ) : $loop->the_post(); 

        if ( has_post_thumbnail() ) :
            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'square-thumb' ); 
        endif; ?>
        <div class="col-12 col-sm-6 col-md-4 archive-item">
            <div class="card">
                <a href="<?php the_permalink(); ?>">
                    <div class="card-header" style="background:url(<?php echo $large_image_url[0]; ?>)no-repeat center; background-size:cover;">
                        <div class="overlay-card">
                            <div class="overlay-content">
                                <h1><?php _e('View Project'); ?></h1>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="card-block">
                    <div class="card-title">
                        <h2><?php the_title(); ?></h2>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <h4 class="col-8"><?php print_taxonomic_ranks( get_the_terms( $id, 'property-type' ) ); ?></h4>
                        <?php if(get_field('cf_year_of_completion')) : ?>
                        <h5 class=col-4><?php the_field('cf_year_of_completion'); ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
        <?php
        wp_reset_postdata();
        die();

}

add_action('wp_ajax_nopriv_cs_filter', 'cs_filter');
add_action('wp_ajax_cs_filter', 'cs_filter');



//AJAX LOAD MORE
function more_news_ajax(){
    
        $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 12;
        $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
        $type = $_POST['type'];
    
        header("Content-Type: text/html");
    
        $args = array (
            'suppress_filters' => true,
            'post_type'         => 'projects',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'posts_per_page' => $ppp,
            'paged'    => $page,
        );
    
        $loop = new WP_Query($args);
    
        $out = '';
    
        if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
        $id = get_the_ID();
            if ( has_post_thumbnail() ) :
                $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large_thumb' ); 
            endif; ?>

        <div class="col-12 col-sm-6 col-md-4 archive-item">
            <div class="card">
                <a href="<?php the_permalink(); ?>">
                    <div class="card-header" style="background:url(<?php echo $large_image_url[0]; ?>)no-repeat center; background-size:cover;">
                        <div class="overlay-card">
                            <div class="overlay-content">
                                <h1><?php _e('View Project'); ?></h1>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="card-block">
                    <div class="card-title">
                        <h2><?php the_title(); ?></h2>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <h4 class="col-8"><?php print_taxonomic_ranks( get_the_terms( $id, 'property-type' ) ); ?></h4>
                        <?php if(get_field('cf_year_of_completion')) : ?>
                        <h5 class=col-4><?php the_field('cf_year_of_completion'); ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    
        <?php
        endwhile;
        endif;
        wp_reset_postdata();
        die();
    }
    
    add_action('wp_ajax_nopriv_more_news_ajax', 'more_news_ajax');
    add_action('wp_ajax_more_news_ajax', 'more_news_ajax');
