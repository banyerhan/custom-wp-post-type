<?php
// for creating new bank post by Night Fury... *** Don't edit it caused by miss cat, tax injection ***

function themes_taxonomy() {  //assign taxonomy
    register_taxonomy(  
        'bank_tax',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'bank',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'bank_cat',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'bank', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  
}  
add_action( 'init', 'themes_taxonomy');

function filter_post_type_link($link, $post) // calling post link
{
    if ($post->post_type != 'bank')
        return $link;

    if ($cats = get_the_terms($post->ID, 'bank_tax'))
        $link = str_replace('%bank_tax%', array_pop($cats)->slug, $link);
    return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2);

function register_themepost() {
    $labels = array(
        'name' => _x( 'Bank', 'my_custom_post','custom' ),
        'singular_name' => _x( 'Bank', 'my_custom_post', 'custom' ),
        'add_new' => _x( 'Add New Bank', 'my_custom_post', 'custom' ),
        'add_new_item' => _x( 'Add New Bank', 'my_custom_post', 'custom' ),
        'edit_item' => _x( 'Edit Bank', 'my_custom_post', 'custom' ),
        'new_item' => _x( 'New Bank', 'my_custom_post', 'custom' ),
        'view_item' => _x( 'View Bank', 'my_custom_post', 'custom' ),
        'search_items' => _x( 'Search Bank', 'my_custom_post', 'custom' ),
        'not_found' => _x( 'Nothing fucking Bank found here!.', 'my_custom_post', 'custom' ),
        'not_found_in_trash' => _x( 'No Any Bank found in Trash..Die', 'my_custom_post', 'custom' ),
        'parent_item_colon' => _x( 'Parent Bank:', 'my_custom_post', 'custom' ),
        'menu_name' => _x( 'All Bank', 'my_custom_post', 'custom' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Custom Bank Posts',
        'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'post-formats', 'custom-fields' ),
        'taxonomies' => array( 'post_tag','bank_tax'),
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => get_stylesheet_directory_uri() . '/foto/bbank.png',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => true,
        'can_export' => true,
        //'rewrite' => array('slug' => '/bank/%bank_tax%/','with_front' => FALSE),
        //'rewrite' => true,
        'rewrite' => array('slug' => '/banks', 'with_front' => false),
        'public' => true,
        'has_archive' => 'bank',
        'capability_type' => 'post'
    );  
    register_post_type( 'bank', $args );
}
add_action( 'init', 'register_themepost', 20 );

function default_taxonomy_term( $post_id, $post ) { // change default term  and save here take unselected is defalut
    if ( 'publish' === $post->post_status ) {
        $defaults = array(
            'bank_tax' => array( 'Unselected'),   //

            );
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( (array) $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy );
            if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
            }
        }
    }
}
add_action( 'save_post', 'default_taxonomy_term', 100, 2 );
function custom_taxonomy_flush_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
add_action('init', 'custom_taxonomy_flush_rewrite');
?>


<?php
/**
 * Register Multiple Taxonomies
 *
 * @author Banyerhan
 */
function be_register_taxonomies() {
	$taxonomies = array(
		array(
			'slug'         => 'job-department',
			'single_name'  => 'Department',
			'plural_name'  => 'Departments',
			'post_type'    => 'jobs',
			'rewrite'      => array( 'slug' => 'department' ),
		),
		array(
			'slug'         => 'job-type',
			'single_name'  => 'Type',
			'plural_name'  => 'Types',
			'post_type'    => 'jobs',
			'hierarchical' => false,
		),
		array(
			'slug'         => 'job-experience',
			'single_name'  => 'Min-Experience',
			'plural_name'  => 'Min-Experiences',
			'post_type'    => 'jobs',
		),
	);
	foreach( $taxonomies as $taxonomy ) {
		$labels = array(
			'name' => $taxonomy['plural_name'],
			'singular_name' => $taxonomy['single_name'],
			'search_items' =>  'Search ' . $taxonomy['plural_name'],
			'all_items' => 'All ' . $taxonomy['plural_name'],
			'parent_item' => 'Parent ' . $taxonomy['single_name'],
			'parent_item_colon' => 'Parent ' . $taxonomy['single_name'] . ':',
			'edit_item' => 'Edit ' . $taxonomy['single_name'],
			'update_item' => 'Update ' . $taxonomy['single_name'],
			'add_new_item' => 'Add New ' . $taxonomy['single_name'],
			'new_item_name' => 'New ' . $taxonomy['single_name'] . ' Name',
			'menu_name' => $taxonomy['plural_name']
		);
		
		$rewrite = isset( $taxonomy['rewrite'] ) ? $taxonomy['rewrite'] : array( 'slug' => $taxonomy['slug'] );
		$hierarchical = isset( $taxonomy['hierarchical'] ) ? $taxonomy['hierarchical'] : true;
	
		register_taxonomy( $taxonomy['slug'], $taxonomy['post_type'], array(
			'hierarchical' => $hierarchical,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => $rewrite,
		));
	}
	
}
add_action( 'init', 'be_register_taxonomies' );
?>

<?php
/**
 * Rest Featured image 
 *
 * @author Banyerhan
 */

function add_thumbnail_to_JSON() {
//Add featured image
register_rest_field( 
    'post', 
    'featured_image_src', 
    array(
        'get_callback'    => 'get_image_src',
        'update_callback' => null,
        'schema'          => null,
         )
    );
}
add_action( 'rest_api_init', 'add_thumbnail_to_JSON' );

function get_image_src( $object, $field_name, $request ) {
  $feat_img_array = wp_get_attachment_image_src(
    $object['featured_media'], 
    'full',  // Size.  Ex. "thumbnail", "large", "full", etc..
    true // Whether the image should be treated as an icon.
  );
  return $feat_img_array[0];
}
?>
