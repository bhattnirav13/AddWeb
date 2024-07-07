<?php
/**
 * Plugin Name: Resource filter
 * Description: Plugin for Resource filter.
 * Version: 0.1
 */

// Start to enqueue js
add_action( 'wp_enqueue_scripts', 'resource_filter_enqueue_scripts' );
function resource_filter_enqueue_scripts() {
    wp_enqueue_script( 'custom-addweb', plugins_url( '/js/addweb.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_localize_script( 'custom-addweb', 'my_ajax_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));

}
// End to enqueue js

// Start to create post type
function resource_custom_post_type() {
    register_post_type(
        'resource',
        array(
            'labels'      => array(
                'name'          => __('Resources', 'textdomain'),
                'singular_name' => __('Resource', 'textdomain'),
            ),
            'public'      => true,
            'has_archive' => true,
            'taxonomies'  => array('category', 'post_tag'),
            'supports'    => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        )
    );
}
add_action('init', 'resource_custom_post_type');
// End to create post type

// Start to create custom taxonomy for resource type
function create_resource_type_taxonomy() {
    register_taxonomy(
        'resource_type',
        'resource',
        array(
            'labels' => array(
                'name'          => __('Resource Types', 'textdomain'),
                'singular_name' => __('Resource Type', 'textdomain'),
            ),
            'public'       => true,
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_resource_type_taxonomy');
// End to create custom taxonomy for resource type

// Start to create custom taxonomy for resource topic
function create_resource_topic_taxonomy() {
    register_taxonomy(
        'resource_topic',
        'resource',
        array(
            'labels' => array(
                'name'          => __('Resource Topics', 'textdomain'),
                'singular_name' => __('Resource Topic', 'textdomain'),
            ),
            'public'       => true,
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_resource_topic_taxonomy');
// End to create custom taxonomy for resource topic

// Start to display custom post type filters
function display_custom_post_type_filters()
{
	// Get resource topics
	$resource_topics = get_terms(array(
		'taxonomy'   => 'resource_topic',
		'hide_empty' => false,
	));

	// Get resource types
	$resource_types = get_terms(array(
		'taxonomy'   => 'resource_type',
		'hide_empty' => false,
	));

	// Start form output
	echo '<form id="resource-filter-form">';

	// Resource Topic Dropdown
	if (!empty($resource_topics) && !is_wp_error($resource_topics)) {
		echo '<select name="resource_topic" id="resource-topic-menu">';
		echo '<option value="">Select Resource Topic</option>';
		foreach ($resource_topics as $topic) {
			echo '<option value="' . $topic->term_id . '">' . $topic->name . '</option>';
		}
		echo '</select>';
	}

	// Resource Type Dropdown
	if (!empty($resource_types) && !is_wp_error($resource_types)) {
		echo '<select name="resource_type" id="resource-type-menu">';
		echo '<option value="">Select Resource Type</option>';
		foreach ($resource_types as $type) {
			echo '<option value="' . $type->term_id . '">' . $type->name . '</option>';
		}
		echo '</select>';
	}

	// Filter Button
	echo '<button type="button" class="filter-button" id="filter-btn">Filter</button>';

	// End form output
	echo '</form>';
}
add_action('form_button', 'display_custom_post_type_filters');
// End to display custom post type filters

// Start to create shortcode
function resource_list_grid()
{
	if (defined('DOING_AJAX') && DOING_AJAX) {
		filter_resource();
	} else {
		
		ob_start(); ?>

		<form id="resource-filter-form">

		     <input type="text" name="search_resource" id="search_resource_data" placeholder="search....">
			<select name="resource_topic" id="resource-topic-menu">
				<option value="">Select Resource Topic</option>
				<?php
				$resource_topics = get_terms(array(
					'taxonomy' => 'resource_topic',
					'hide_empty' => false,
				));
				foreach ($resource_topics as $topic) : ?>
					<option value="<?php echo esc_attr($topic->term_id); ?>"><?php echo esc_html($topic->name); ?></option>
				<?php endforeach; ?>
			</select>

			<select name="resource_type" id="resource-type-menu">
				<option value="">Select Resource Type</option>
				<?php
				$resource_types = get_terms(array(
					'taxonomy' => 'resource_type', 
					'hide_empty' => false,
				));
				foreach ($resource_types as $type) : ?>
					<option value="<?php echo esc_attr($type->term_id); ?>"><?php echo esc_html($type->name); ?></option>
				<?php endforeach; ?>
			</select>

			<button type="button" id="filter-btn">Filter</button>
		</form>

		<div id="posts-container"></div>

		<?php
		return ob_get_clean();
	}
}
add_shortcode('resource-list-grid', 'resource_list_grid');
// End to create shortcode

// Start to filter
function filter_resource()
{
	//check_ajax_referer('ajax-nonce', 'nonce');
	$resource_topic = isset($_POST['resource_topic']) ? intval($_POST['resource_topic']) : '';
	$resource_type = isset($_POST['resource_type']) ? intval($_POST['resource_type']) : '';
	$search_resource = isset($_POST['search_resource']) ? sanitize_text_field($_POST['search_resource']) : '';
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

	$args = array(
		'post_type'      => 'resource',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'orderby'        => 'date',
		'order'          => 'ASC',
		'paged'          => $page,
	);

	// Initialize tax_query
	$tax_query = array();

	// If a resource topic is selected, add it to the tax_query
	if (!empty($resource_topic)) {
		$tax_query[] = array(
			'taxonomy' => 'resource_topic',
			'field'    => 'term_id',
			'terms'    => $resource_topic,
		);
	}

	// If a resource type is selected, add it to the tax_query
	if (!empty($resource_type)) {
		$tax_query[] = array(
			'taxonomy' => 'resource_type',
			'field'    => 'term_id',
			'terms'    => $resource_type,
		);
	}

	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}

	if(!empty($search_resource)) {
		$args['s'] = $search_resource;
	}
	$get_resource = new WP_Query($args);

	ob_start();

	if ($get_resource->have_posts()) :
		while ($get_resource->have_posts()) : $get_resource->the_post(); ?>
					<?php
					if (has_post_thumbnail()) {
						the_post_thumbnail('full', array('alt' => get_the_title()));
					}
					?>
					<h6><?php the_title(); ?></h6>
				<p>Resource Topic:
					<?php
					$resource_topics = get_the_terms(get_the_ID(), 'resource_topic');
					if ($resource_topics) {
						$topic_names = array();
						foreach ($resource_topics as $topic) {
							$topic_names[] = esc_html($topic->name);
						}
						echo '<span class="resource-category">' . implode(', ', $topic_names) . '</span>';
					}
					?>
				</p>
				<p>Resource Type:
					<?php
					$resource_types = get_the_terms(get_the_ID(), 'resource_type');
					if ($resource_types) {
						$type_names = array();
						foreach ($resource_types as $type) {
							$type_names[] = $type->name;
						}
						echo implode(', ', $type_names);
					}
					?>
				</p>
			
		<?php endwhile;
			echo '<div class="pagination">';
			echo paginate_links(array(
				'type'      => 'list',
				'format'    => 'page/%#%',
				'current'   => $page,
				'total'     => $get_resource->max_num_pages,
			));
			echo '</div>';
	else :
		echo '<p>No posts found.</p>';
	endif;
	wp_reset_postdata();
	$response = ob_get_clean();
	echo $response;
	wp_die();
}

add_action('wp_ajax_filter_resource', 'filter_resource');
add_action('wp_ajax_nopriv_filter_resource', 'filter_resource');
// End to filter