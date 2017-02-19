<?php
/**
 * Custom Post Type Class
 *
 * Used to help create and manage custom post types in Wordpress.
 * @link http://github.com/jjgrainger/wp-custom-post-type-class/
 * Inspired by @link https://github.com/jjgrainger/PostTypes
 *
 * @author  dpjustice
 * @link    https://davidparsons.me
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */
class CPT {

	/**
	 * Post type name.
	 *
	 * @var string $post_type_name Holds the name of the post type.
	 */
	public $post_type_name;

	/**
	 * Holds the singular name of the post type. This is a human friendly
	 * name, capitalized with spaces assigned on __construct().
	 *
	 * @var string $singular Post type singular name.
	 */
	public $singular;

	/**
	 * Holds the plural name of the post type. This is a human friendly
	 * name, capitalized with spaces assigned on __construct().
	 *
	 * @var string $plural Singular post type name.
	 */
	public $plural;

	/**
	 * Post type slug. This is a robot friendly name, all lowercase and uses
	 * hyphens assigned on __construct().
	 *
	 * @var string $slug Holds the post type slug name.
	 */
	public $slug;

	/**
	 * User submitted options assigned on __construct().
	 *
	 * @var array $options Holds the user submitted post type options.
	 */
	public $options;

	/**
	 * Taxonomies
	 *
	 * @var array $taxonomies Holds an array of taxonomies associated with the post type.
	 */
	public $taxonomies;

	/**
	 * Taxonomy settings, an array of the taxonomies associated with the post
	 * type and their options used when registering the taxonomies.
	 *
	 * @var array $taxonomy_settings Holds the taxonomy settings.
	 */
	public $taxonomy_settings;

	/**
	 * Exisiting taxonomies to be registered after the posty has been registered
	 *
	 * @var array $exisiting_taxonomies holds exisiting taxonomies
	 */
	public $exisiting_taxonomies;

	/**
	 * Taxonomy filters. Defines which filters are to appear on admin edit
	 * screen used in add_taxonmy_filters().
	 *
	 * @var array $filters Taxonomy filters.
	 */
	public $filters;

	/**
	 * Defines which columns are to appear on the admin edit screen used
	 * in add_admin_columns().
	 *
	 * @var array $columns Columns visible in admin edit screen.
	 */
	public $columns;

	/**
	 * User defined functions to populate admin columns.
	 *
	 * @var array $custom_populate_columns User functions to populate columns.
	 */
	public $custom_populate_columns;

	/**
	 * Sortable columns.
	 *
	 * @var array $sortable Define which columns are sortable on the admin edit screen.
	 */
	public $sortable;

	/**
	 * Textdomain used for translation. Use the set_textdomain() method to set a custom textdomain.
	 *
	 * @var string $textdomain Used for internationalising. Defaults to "cpt" without quotes.
	 */
	public $textdomain = 'cpt';

	public $icon;

	/**
	 * Constructor
	 *
	 * Register a custom post type.
	 *
	 * @param mixed $post_type_names The name(s) of the post type, accepts (post type name, slug, plural, singular).
	 * @param array $options User submitted options.
	 */
	function __construct( $post_type_name, $options = array() ) {
		// Apply to post type name.
		$this->post_type_name = $post_type_name;

		// Set the slug name.
		$this->slug = $this->get_slug();

		// Set the plural name label.
		$this->plural = $this->get_plural();

		// Set the singular name label.
		$this->singular = $this->get_singular();

		// Set the user submitted options to the object.
		$this->options = $options;

		// Register taxonomies.
		// $this->add_action( 'init', array( &$this, 'register_taxonomies' ) );

		// Register the post type.
		$this->add_action( 'init', [$this, 'register_post_type'] );

		// Register exisiting taxonomies.
		// $this->add_action( 'init', array( &$this, 'register_exisiting_taxonomies' ) );
	}

	/**
	 * Add Action
	 *
	 * Helper function to add add_action WordPress filters.
	 *
	 * @param string $action Name of the action.
	 * @param string $function Function to hook that will run on action.
	 * @param integet $priority Order in which to execute the function, relation to other functions hooked to this action.
	 * @param integer $accepted_args The number of arguments the function accepts.
	 */
	function add_action( $action, $function, $priority = 10, $accepted_args = 1 ) {
		// Pass variables into WordPress add_action function
		add_action( $action, $function, $priority, $accepted_args );
	}

	/**
	 * Get slug
	 *
	 * Creates an url friendly slug.
	 *
	 * @param  string $name Name to slugify.
	 * @return string $name Returns the slug.
	 */
	 function get_slug( $name = null ) {
		// If no name set use the post type name.
		if ( ! isset( $name ) ) {
			$name = $this->post_type_name;
		}

		// Name to lower case.
		$name = strtolower( $name );

		// Replace spaces with hyphen.
		$name = str_replace( " ", "-", $name );

		// Replace underscore with hyphen.
		$name = str_replace( "_", "-", $name );

		return $name;
	}

	/**
	 * Get plural
	 *
	 * Returns the friendly plural name.
	 *
	 *    ucwords      capitalize words
	 *    strtolower   makes string lowercase before capitalizing
	 *    str_replace  replace all instances of _ to space
	 *
	 * @param  string $name The slug name you want to pluralize.
	 * @return string the friendly pluralized name.
	 */
	function get_plural( $name = null ) {
		// If no name is passed the post_type_name is used.
		if ( ! isset( $name ) ) {
			$name = $this->post_type_name;
		}

		// Return the plural name. Add 's' to the end.
		return $this->get_human_friendly( $name ) . 's';
	}

	/**
	 * Get singular
	 *
	 * Returns the friendly singular name.
	 *
	 *    ucwords      capitalize words
	 *    strtolower   makes string lowercase before capitalizing
	 *    str_replace  replace all instances of _ to space
	 *
	 * @param string $name The slug name you want to unpluralize.
	 * @return string The friendly singular name.
	 */
	function get_singular( $name = null ) {
		// If no name is passed the post_type_name is used.
		if ( ! isset( $name ) ) {
			$name = $this->post_type_name;
		}

		// Return the string.
		return $this->get_human_friendly( $name );
	}

	/**
	 * Get human friendly
	 *
	 * Returns the human friendly name.
	 *
	 *    ucwords      capitalize words
	 *    strtolower   makes string lowercase before capitalizing
	 *    str_replace  replace all instances of hyphens and underscores to spaces
	 *
	 * @param string $name The name you want to make friendly.
	 * @return string The human friendly name.
	 */
	function get_human_friendly( $name = null ) {
		// If no name is passed the post_type_name is used.
		if ( ! isset( $name ) ) {
			$name = $this->post_type_name;
		}

		// Return human friendly name.
		return ucwords( strtolower( str_replace( "-", " ", str_replace( "_", " ", $name ) ) ) );
	}

	/**
	 * Register Post Type
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function register_post_type() {
		// Friendly post type names.
		$plural     = $this->plural;
		$singular   = $this->singular;
		$singular_l = strtolower( $singular );
		$slug       = $this->slug;
		$icon       = $this->icon;
		$textdomain = $this->textdomain;

		$labels = array(
			'name'               => _x( $plural, 'post type general name', $textdomain ),
			'singular_name'      => _x( $singular, 'post type singular name', $textdomain ),
			'menu_name'          => _x( $plural, 'admin menu', $textdomain ),
			'name_admin_bar'     => _x( $singular, 'add new on admin bar', $textdomain ),
			'add_new'            => _x( 'Add New', $singular_l, $textdomain ),
			'add_new_item'       => __( 'Add New ' . $singular, $textdomain ),
			'new_item'           => __( 'New ' . $singular, $textdomain ),
			'edit_item'          => __( 'Edit ' . $singular, $textdomain ),
			'view_item'          => __( 'View ' . $singular, $textdomain ),
			'all_items'          => __( 'All ' . $plural, $textdomain ),
			'search_items'       => __( 'Search ' . $plural, $textdomain ),
			'parent_item_colon'  => __( 'Parent ' . $plural . ':', $textdomain ),
			'not_found'          => __( 'No ' . $singular_l . ' found.', $textdomain ),
			'not_found_in_trash' => __( 'No ' . $singular_l . ' found in Trash.', $textdomain )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $slug ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => $icon,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		// Check that the post type doesn't already exist.
		if ( ! post_type_exists( $this->post_type_name ) ) {
			register_post_type( $this->post_type_name, $args );
		}
	}


	/**
	 * Register taxonomy
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_taxonomy
	 *
	 * @param string $taxonomy_name The slug for the taxonomy.
	 * @param array  $options Taxonomy options.
	 */
	function register_taxonomy($taxonomy_names, $options = array()) {
		// Post type defaults to $this post type if unspecified.
		$post_type = $this->post_type_name;

		// An array of the names required excluding taxonomy_name.
		$names = array(
			'singular',
			'plural',
			'slug'
		);

		// if an array of names are passed
		if ( is_array( $taxonomy_names ) ) {
			// Set the taxonomy name
			$taxonomy_name = $taxonomy_names['taxonomy_name'];

			// Cycle through possible names.
			foreach ( $names as $name ) {
				// If the user has set the name.
				if ( isset( $taxonomy_names[ $name ] ) ) {
					// Use user submitted name.
					$$name = $taxonomy_names[ $name ];
				} else { // Else generate the name.
					// Define the function to be used.
					$method = 'get_' . $name;
					// Generate the name
					$$name = $this->$method( $taxonomy_name );
				}
			}
		} else { // Else if only the taxonomy_name has been supplied.
			// Create user friendly names.
			$taxonomy_name = $taxonomy_names;
			$singular = $this->get_singular( $taxonomy_name );
			$plural   = $this->get_plural( $taxonomy_name );
			$slug     = $this->get_slug( $taxonomy_name );
		}

		// Default labels.
		$labels = array(
			'name'                       => sprintf( __( '%s', $this->textdomain ), $plural ),
			'singular_name'              => sprintf( __( '%s', $this->textdomain ), $singular ),
			'menu_name'                  => sprintf( __( '%s', $this->textdomain ), $plural ),
			'all_items'                  => sprintf( __( 'All %s', $this->textdomain ), $plural ),
			'edit_item'                  => sprintf( __( 'Edit %s', $this->textdomain ), $singular ),
			'view_item'                  => sprintf( __( 'View %s', $this->textdomain ), $singular ),
			'update_item'                => sprintf( __( 'Update %s', $this->textdomain ), $singular ),
			'add_new_item'               => sprintf( __( 'Add New %s', $this->textdomain ), $singular ),
			'new_item_name'              => sprintf( __( 'New %s Name', $this->textdomain ), $singular ),
			'parent_item'                => sprintf( __( 'Parent %s', $this->textdomain ), $plural ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', $this->textdomain ), $plural ),
			'search_items'               => sprintf( __( 'Search %s', $this->textdomain ), $plural ),
			'popular_items'              => sprintf( __( 'Popular %s', $this->textdomain ), $plural ),
			'separate_items_with_commas' => sprintf( __( 'Seperate %s with commas', $this->textdomain ), $plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', $this->textdomain ), $plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from most used %s', $this->textdomain ), $plural ),
			'not_found'                  => sprintf( __( 'No %s found', $this->textdomain ), $plural ),
		);

		// Default options.
		$defaults = array(
			'labels' => $labels,
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => $slug
			)
		);

		// Merge default options with user submitted options.
		$options = array_replace_recursive( $defaults, $options );

		// Add the taxonomy to the object array, this is used to add columns and filters to admin panel.
		$this->taxonomies[] = $taxonomy_name;

		// Create array used when registering taxonomies.
		$this->taxonomy_settings[ $taxonomy_name ] = $options;
	}


	/**
	 * Register taxonomies
	 *
	 * Cycles through taxonomies added with the class and registers them.
	 */
	function register_taxonomies() {
		if ( is_array( $this->taxonomy_settings ) ) {
			// Foreach taxonomy registered with the post type.
			foreach ( $this->taxonomy_settings as $taxonomy_name => $options ) {
				// Register the taxonomy if it doesn't exist.
				if ( ! taxonomy_exists( $taxonomy_name ) ) {
					// Register the taxonomy with Wordpress
					register_taxonomy( $taxonomy_name, $this->post_type_name, $options );
				} else {
					// If taxonomy exists, register it later with register_exisiting_taxonomies
					$this->exisiting_taxonomies[] = $taxonomy_name;
				}
			}
		}
	}


	/**
	 * Register Exisiting Taxonomies
	 *
	 * Cycles through exisiting taxonomies and registers them after the post type has been registered
	 */
	function register_exisiting_taxonomies() {
		if( is_array( $this->exisiting_taxonomies ) ) {
			foreach( $this->exisiting_taxonomies as $taxonomy_name ) {
				register_taxonomy_for_object_type( $taxonomy_name, $this->post_type_name );
			}
		}
	}


	/**
	 * Set menu icon
	 *
	 * @link http://melchoyce.github.io/dashicons/
	 *
	 * @param string $icon dashicon name
	 */
	function menu_icon( $icon = 'dashicons-admin-page' ) {
		if ( is_string( $icon ) && stripos( $icon, "dashicons" ) !== false ) {
			$this->icon = $icon;
		} else {
			$this->icon = 'dashicons-admin-page';
		}
	}


	/**
	 * Flush
	 *
	 * Flush rewrite rules programatically
	 */
	function flush() {
		flush_rewrite_rules();
	}
}
