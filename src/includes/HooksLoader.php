<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://www.chinmayaclix.com/radhika
 * @since      1.0.0
 *
 * @package    ccmt\clix
 * @subpackage 
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    ccmt\clix
 * @subpackage 
 * @author     Radhika & Suresh <itsupport.ccmt@chinmayamission.com>
 */

namespace ccmt\clix;

 class HooksLoader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $registeractions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $registeractions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $registerfilters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $registerfilters;

	/**
	 * The array of actions to remove with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $removeactions 
	 */
	protected $removeactions;

	/**
	 * The array of filters to remove with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $removefilters
	 */
	protected $removefilters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->registeractions = array();
		$this->registerfilters = array();
		$this->removeactions = array();
		$this->removefilters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->registeractions = $this->add( $this->registeractions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->registerfilters = $this->add( $this->registerfilters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Remove an action from the collection in WordPress.
	 *
	 * @since    1.0.0
	 * @param string   				  $tag                The action hook to which the function to be removed is hooked.
     * @param callable 				  $function_to_remove The name of the function which should be removed.
     * @param int      				  $priority           Optional. The priority of the function. Default 10.
	 * 
	 */
	public function remove_action( $tag, $function_to_remove, $priority = 10 ) {
		$this->removeactions = $this->remove( $this->removeactions, $tag, $function_to_remove, $priority);
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param string   $tag                The filter hook to which the function to be removed is hooked.
	 * @param callable $function_to_remove The name of the function which should be removed.
	 * @param int      $priority           Optional. The priority of the function. Default 10.
	 */
	public function remove_filter( $tag, $function_to_remove, $priority = 10 ) {
		$this->removefilters = $this->remove( $this->removefilters, $tag, $function_to_remove, $priority );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $remove_hooks            The collection of hooks that is being removed (that is, actions or filters).
	 * @param    string               $tag             		   The action hook to which the function to be removed is hooked.
	 * @param callable 				  $function_to_remove 	   The name of the function which should be removed.
	 * @param    int                  $priority         	   The priority at which the function should be fired.
	 * @return   array                                 	 	   The collection of actions and filters to remove with WordPress.
	 */
	private function remove( $remove_hooks, $tag, $function_to_remove, $priority ) {

		$remove_hooks[] = array(
			'tag'          			 => $tag,
			'function_to_remove'     => $function_to_remove,
			'priority'      		 => $priority,

		);

		return $remove_hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		//write_log("***** Registered Filters*******");
		foreach ( $this->registerfilters as $hook ) {
			//write_log($hook['hook'].":". $hook['callback'] .":". $hook['priority'].":".$hook['accepted_args']);
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
       //write_log("***** Registered actions*******");
		foreach ( $this->registeractions as $hook ) {
			//write_log($hook['hook'].":". $hook['callback'] .":". $hook['priority'].":".$hook['accepted_args']);
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
		//write_log("***** Remove actions*******");
		foreach ( $this->removeactions as $tag ) {
			remove_action( $tag['tag'], $tag['function_to_remove'], $tag['priority']);
		}
		//write_log("***** Remove filters*******");
		foreach ( $this->removefilters as $tag ) {
			remove_filter( $tag['tag'], $tag['function_to_remove'], $tag['priority']);
		}

	}

}
