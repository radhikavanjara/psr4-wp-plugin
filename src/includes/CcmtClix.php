<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ccmt\clix
 * @author     Radhika & Suresh <itsupport.ccmt@chinmayamission.com>
 */

 namespace ccmt\clix;

 use ccmt\clix\PluginActivator;
 use ccmt\clix\PluginDeactivator;
 use ccmt\clix\HooksLoader;
 use ccmt\clix\ClixPublic;
 use ccmt\clix\ClixAdmin;


 class CcmtClix {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

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
	 * Facade class plubic facing functionality of the plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var          $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $clixPublic;

	/**
	 * Facade class plubic facing functionality of the plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var          $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $clixAdmin;


    /**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-ccmt-clix';
		$this->hooksLoader = new  HooksLoader();
		$this->clixPublic = new ClixPublic($this->plugin_name, $this->version, $this->hooksLoader);
		$this->clixAdmin = new ClixAdmin($this->plugin_name, $this->version, $this->hooksLoader);
		$this->clixWidget = new ClixWidget($this->plugin_name, $this->version, $this->hooksLoader);
		//$this->load_dependencies();
		//$this->set_locale();
		//$this->define_admin_hooks();
		//$this->define_public_hooks();

	}

    /**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function init() {
        $this->hooksLoader->run();
		register_activation_hook( __FILE__, PluginActivator::activate());
		register_deactivation_hook(__FILE__, PluginDeactivator::deactivate());

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    ccmt\clix\HooksLoader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->hooksLoader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
