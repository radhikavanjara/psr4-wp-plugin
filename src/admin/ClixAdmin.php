<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.chinmayaclix.com/radhika
 * @since      1.0.0
 *
 * @package    ccmt\clix
 * @subpackage 
 */

/**
 * The admin-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ccmt\clix
 * @subpackage 
 * @author     Radhika & Suresh <itsupport.ccmt@chinmayamission.com>
 */

 namespace ccmt\clix;

 use ccmt\clix\HooksLoader;

 class ClixAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	 /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      HooksLoader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $hooksLoader;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $hooksLoader) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->hooksLoader = $hooksLoader;
		$this->registerHooks();
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function registerHooks() {

		$this->hooksLoader->add_action( 'admin_enqueue_scripts', $this, 'enqueueStyles' );
		$this->hooksLoader->add_action( 'admin_enqueue_scripts', $this, 'enqueueScripts' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ccmt_Clix_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ccmt_Clix_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'src/admin/css/ClixAdmin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ccmt_Clix_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ccmt_Clix_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'src/admin/js/ClixAdmin.js', array( 'jquery' ), $this->version, false );

	}

}
