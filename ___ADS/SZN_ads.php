<?php
/*
Plugin Name: SZN - Advertisement
Plugin URI: http://www.dentrist.com/
Description: Custom plugin for enqueuing Google Ads & others in my WordPress theme
Version: 1.0
Author: SZN
Author URI: http://www.dentrist.com/
License: Help yourselves to the shelves
*/

/* Plugins Loaded */
add_action ('plugins_loaded', function (){

	/*
		PARAMETERS:
			SIZE - small, medium, large, default
			SHAPE - square, rectangle, mobilebanner, leaderboard, skyscraper
			PLATFORM - tablet, desktop, mobile
	*/


	// return ads code according to size & type of google ads.
	function SZN_ads($showADS, $size, $shape, $array_platform) {
		$showADS = false;

		foreach ($array_platform as &$platform) {
			switch ($platform) { // get condition to check which platform is required
			case "desktop":
				$platform_condition = (!is_mobile() && !is_tablet());
			break;
			case "mobile":
				$platform_condition = is_mobile();
			break;
			case "tablet":
				$platform_condition = is_tablet();
			break;
			}
			if ($platform_condition) {break;}
		}

		if($showADS && $platform_condition) { // if platform is true - use function only for specific platform in condition
			ob_start(); // start saving all html after this.
			?>

			<div id="adsbygoogle-<?php echo $size; ?>-<?php echo $shape; ?>" >
			<center style="margin-bottom: 15px; margin-top: 10px;">
				<?php
				include plugin_dir_path(__FILE__) . 'google-ads/ads-'.$size.'-'.$shape.'.php';
				?>
			</center>
			</div>

			<?php
			$ads = ob_get_contents(); // gets content
			ob_end_clean(); // Discards buffer
		} else {
			return ''; // return nothing.
		}

	return $ads;
	}

/* END Plugins Loaded */
});
?>
