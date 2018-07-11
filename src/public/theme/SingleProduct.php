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
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ccmt\clix
 * @subpackage theme
 * @author     Radhika & Suresh <itsupport.ccmt@chinmayamission.com>
 */

namespace  ccmt\clix\theme;

class SingleProduct 
{
    function displaySingleProductMainContent()
    {
        global $wpdb, $post, $IPcountryName,$cat_slug;
		$cat_res = get_the_terms( $post->ID, 'product_cat');
        $cat_slug = $cat_res[0]->slug;
        
        $current_user = wp_get_current_user();
	     $slug =  get_post_field( 'post_name', $post->ID);
	     $postId = get_page_by_path($slug,OBJECT,"product" );
	     $cntPurchasedVideo=0;
	     $allowAccess = TRUE;
	     $displayPurchasedVideo="none";
	 	 $post_format =  get_post_format($postId->ID);

	     if($current_user != NULL &&  $postId != NULL){
      	  $productDetails = $this -> getUserRequestedProduct($current_user);
	      if($productDetails['cntPurchasedVideo'] > 0){
	        $displayPurchasedVideo="block";
	      }
	       $playbackCountryName = trim($productDetails['playbackRegion']);
	       $countryCode = $this -> getPlaybackRegionCountryCode($playbackCountryName);
	       $IPcountryCode = $_SESSION['country_code'];
	       $IPcountryName =  $_SESSION['country_name'];

	    }
	    
		// Check Access to the requested Product's to Pro URL
		global $custom_value;
		$custom_value = array();
		$cat_terms = get_the_terms( $productDetails['product_id'], 'product_cat');
		$custom_value = $this -> productType($cat_terms);
	    $pro_url = false;
	    if(current_user_can( 'administrator' ) || $this -> isPurchased($productDetails)){
	    	$expired = $this -> isExpired($productDetails['time'],$productDetails['accessExpiry']);
	    	//if access is not expired then check for allowed view limit
	    	if (current_user_can( 'administrator' ) || !$expired){ 
				$isLimitExceeded = $this -> isLimitExceeded($productDetails['downloadCount'],$productDetails['downloadLimit']);
				if(current_user_can( 'administrator' ) || !$isLimitExceeded){
					if(current_user_can( 'administrator' ) || $this -> isAllowedRegion($IPcountryCode,$countryCode,$playbackCountryName)){

							$custom_value = $this -> assignProUrl($custom_value);

					}else{
						$message = "You have purchased this product to play only in ";
						$message .= $productDetails['playbackRegion'];
						$message .= ". Scroll down to read additional informations on playback restriction.";
						$this -> displayMessage($message); }
				}else 
					$this -> displayMessage("You have exceeded your allowed limit on number of views. Please use Add to cart button to buy again"); 
	    	}else 
	    		$this -> displayMessage("Your purchased video has been expired. Please use Add to cart button to buy again");
	    		
			$pro_url = true;
	    	//displayProduct($custom_value, $IPcountryName, $postId->ID, $current_user->ID);
	    }
        if($cat_slug == 'chinmaya-channel' || $pro_url == true){
          	 $header .= '<h2 class="h-style">';
          	 $header .= get_option(THEME_NAME_S.'_translation_book_details','Playlist');
          	 $header .= '</h2>';
       }else{
       		 $header .= '<h2 class="h-style">';
          	 $header .= get_option(THEME_NAME_S.'_translation_book_details','Product Detail');
          	 $header .= '</h2>';
        }?>
        <!-- <header class="header-style" > -->
		<header style = "float: left; width: 97%; border-bottom: solid 2px; padding: 0px; background: none; margin: 20px;">
                <?php echo $header;?>
        </header>
        <?php if ($pro_url == true) {
        	$this -> displayProduct($custom_value, $IPcountryName, $postId->ID, $current_user->ID);
        }else{
        	$this -> displayProduct($custom_value);
        }       
	}
	
    
    public function displaySingleProductSummary(){
       //wp_reset_postdata();
       $custom_value = get_query_var('custom_value');

        if($custom_value['type'] == 'audio'){
			global $post, $wpdb;
            echo  woocommerce_get_product_thumbnail();
			$display_url = get_post_meta($post->ID,$custom_value['audio'], true);
			if ( ! $display_url ) return;
			echo apply_filters( 'woocommerce_short_description', $display_url );

		}
		elseif($custom_value['type'] == 'video') {	
			global $post, $wpdb;
			$display_url = get_post_meta($post->ID,$custom_value['video'], true);
			if ( ! $display_url ) return;
			echo apply_filters( 'woocommerce_short_description', $display_url );
        }
        elseif ($custom_value['type'] == 'bundled') {
            global $post, $wpdb;
			$display_url['audio'] = get_post_meta($post->ID,$custom_value['audio'], true);
			$display_url['video'] = get_post_meta($post->ID,$custom_value['video'], true);
            set_query_var( 'custom_value', $display_url);
            woocommerce_get_template_part( 'content', 'bundledproduct');    
		}
	}


	/**
	 * A function that is used to get the Playback Region's Country Code.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function getPlaybackRegionCountryCode($playbackCountryName){
		global $wpdb;
		if ($playbackCountryName == 'Asia')
			$playbackCountryName = "India', 'Asia";
		$country = "SELECT country_code from gcmw_playback_region where playback_region_name IN ('".$playbackCountryName."')";
		$country_results = $wpdb->get_results($country);
		//print_r($wpdb->last_query);
		foreach ($country_results as $country ) {
			$country_code[] = trim($country->country_code);
		}
		return $country_code;
	}

    /**
	 * A function that is used to get the Product according to User
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function getUserRequestedProduct($current_user){
		$productDetail = $this -> getvariationProductDetail($current_user);
		if (!isset($productDetail)) {
			$productDetail = $this -> getRegularProductDetails($current_user);
		}
		return $productDetail;
	}

	/**
	 * A function that is used to get the Product according to User
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function getvariationProductDetail($current_user){
		global $wpdb, $post;
		$product_id = $post->ID;
		$query   = "SELECT p.id, p.post_parent, p.post_title, COUNT(DISTINCT dp.product_id) as count, dp.access_expires, dp.download_count, dp.product_id from wp_posts as p inner join wp_woocommerce_downloadable_product_permissions as dp on p.id = dp.product_id where user_id = " .$current_user->ID. " and post_parent =".$product_id." AND post_type ='product_variation'";
		$results = $wpdb->get_results($query,ARRAY_A);
		foreach ($results as $prod) {
			$array['product_id'] = $prod['id'];
			$array['cntPurchasedVideo'] = $prod['count'];
			$array['accessExpiry'] = $prod['access_expires'];
			$array['downloadCount']=$prod['download_count'];
			$array['time'] = current_time('mysql', $gmt = 0 );
			$array['downloadLimit'] = get_post_meta( $prod['product_id'], '_download_limit', true);
			$product_title = $prod['post_title'];
			$explodedvalue = explode("-", $product_title);
			$array['playbackRegion'] = end($explodedvalue);
			return $array;
		}
		
	}

	/**
	 * A function that is used to get the Product according to User
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function getRegularProductDetails($current_user){
		global $wpdb, $post;
		$product_id = $post->ID;
		$query = "SELECT COUNT(DISTINCT product_id) as count, access_expires, download_count, product_id from wp_woocommerce_downloadable_product_permissions where user_id = " .$current_user->ID. " AND product_id =".$product_id;
		$results = $wpdb->get_results($query);
		
		foreach ($results as $results) {
			$array['cntPurchasedVideo'] = $results->count;
			$array['accessExpiry'] = $results->access_expires;
			$array['downloadCount']=$results->download_count;
			$array['time'] = current_time('mysql', $gmt = 0 );
			$array['downloadLimit'] = get_post_meta( $results->product_id, '_download_limit', true);
			return $array;
		}
	}

	/**
	 * A function that is used to check the allowed playback region for current user.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function isAllowedRegion($IPcountryCode,$countryCode,$playbackCountryName){
		$isAllowed=false;
		if(in_array($IPcountryCode, $countryCode) || $playbackCountryName == 'Global')
			$isAllowed = true;
		return $isAllowed;
	}

	/**
	 * A function that is used to check whether the video has been purchased by the current user.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function isPurchased($productDetails){
		$isPurchased =false;
		if($productDetails['cntPurchasedVideo'] != NULL && $productDetails['cntPurchasedVideo'] > 0)
			$isPurchased=true;
		return $isPurchased;
	}

	/**
	 * A function that is used to display the Product
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function displayProduct($custom_value, $IPcountryCode=0, $postId=0, $current_user=0){
		global $post;
		while ( have_posts() ) : the_post(); 
				set_query_var( 'custom_value', $custom_value);
				if($custom_value == 'gcmw_pro_url'){
					$this -> videoPlaybackLog($IPcountryCode, $postId, $current_user);
				}
		endwhile;
	}

	/**
	 * A function that is used to check product expiry.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function isExpired($time,$accessExpiry){
		$isExpired=true;
		if (!($time > $accessExpiry) || $accessExpiry == null) {
			$isExpired =false;
		}
		
		return $isExpired;
	}

	/**
	 * A function that is used to get the product download limit.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function isLimitExceeded($downloadCount,$downloadLimit){
		$limitExceeded=false;
		if ($downloadCount >= $downloadLimit && $downloadLimit >= 0){
			$limitExceeded = true;
		}
		return $limitExceeded;
	}

	/**
	 * A function that is used to display messages.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function displayMessage($message){
		echo "<p style = 'font-size: 18px;color:red;'>";
		echo $message;
		echo "</p>";
	}

	/**
	 * A function that is used to create a video playback log entry in WP database. (Table Name = 'video_playback_log')
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function videoPlaybackLog($IPcountryCode, $postId, $current_user){
		global $wpdb;
		$a_add_on = date('Y-m-d H:i:s');
		$requestIP = WC_Geolocation::get_ip_address();
		$log_inserted = array(
							'id' => '',
							'user_id' => $current_user,
							'product_id' => $postId,
							'IPaddress' => $requestIP,
							'country' => $IPcountryCode,
							'date' => $a_add_on,				                
						);
		$wpdb->insert( 'video_playback_log',$log_inserted, array( '%s' ));
	}
	
	/**
	 * A function that is used to get the Product type and assign initial custom values ($custom_value)
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function productType($cat_terms){
		foreach ($cat_terms as $term){
			$cat_heir = get_ancestors( $term->term_id, 'product_cat' );
			$parent_cat = get_the_category_by_ID($cat_heir[0]);
			$cat_slug = $term->slug;
			if($parent_cat == 'Video' or $cat_slug == 'video'){
				$custom_value['type'] = 'video';
				break;
			}
			elseif($parent_cat == 'Audio' or $cat_slug == 'audio'){
				$custom_value['type'] = 'audio';
				break;
			}
			elseif($parent_cat == 'Bundled' or $cat_slug == 'bundled'){
				$custom_value['type'] = 'bundled';
				break;
			}
			else
			continue;	
		}
		$custom_value['video'] = 'gcmw_teaser_url';
		$custom_value['audio'] = 'gcmw_teaser_url_audio';
		return $custom_value;
	}

	/**
	 * A function that is used to assign pro_url for user access after necessary checks have been cleared.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function assignProUrl($custom_value){
		if($custom_value['type'] == 'video'){
			$custom_value['video'] = 'gcmw_pro_url';
			$custom_value['audio'] = '';
		}
		elseif($custom_value['type'] == 'audio'){
			$custom_value['audio'] = 'gcmw_pro_url_audio';
			$custom_value['video'] = '';
		}
		elseif($custom_value['type'] == 'bundled'){
			$custom_value['video'] = 'gcmw_pro_url';
			$custom_value['audio'] = 'gcmw_pro_url_audio';
		}
		return $custom_value;
	}
}
?>
