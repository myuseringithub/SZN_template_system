<?php

/* Remove "Capabilities" & "Additional capabilities" metabox from profile page */
/*
function remove_additional_capabilities_func()
{
    return false;
}
add_filter('additional_capabilities_display', 'remove_additional_capabilities_func');
*/


/* REMOVE PROFILE FIELDS IN PROFILE PAGE */
/*
function remove_website_row_wpse_94963() {
    if(!current_user_can('manage_options')){
        // hide only for non-admins
        echo "
		<script>
		jQuery(document).ready(function(){
			//jQuery('#url').parents('tr').remove();
			jQuery('#nickname').parents('tr').remove();
			jQuery('#pinterest').parents('tr').remove();
			jQuery('#flickr').parents('tr').remove();
			jQuery('#digg').parents('tr').remove();
			jQuery('#stumbleupon').parents('tr').remove();
			jQuery('#yelp').parents('tr').remove();
			jQuery('#delicious').parents('tr').remove();
			jQuery('#im').parents('tr').remove();
			jQuery('#jabber').parents('tr').remove();
			jQuery('#aim').parents('tr').remove();
			jQuery('#googletalk').parents('tr').remove();
			jQuery('#reddit').parents('tr').remove();
			jQuery('#yim').parents('tr').remove();
			
			// REMOVE h3 and first table section
			jQuery('h3').remove();
			jQuery('.show-admin-bar').parents('table').remove();
		});
		</script>";
    }
}
// add_action('admin_head-user-edit.php','remove_website_row_wpse_94963');
add_action('admin_head-profile.php','remove_website_row_wpse_94963');
*/



/*// Disables !! not removes PROFILE FIELDS IN PROFILE PAGE
add_action('admin_init', 'user_profile_fields_disable');
function user_profile_fields_disable() {
    global $pagenow;
    // apply only to user profile or user edit pages
    if ($pagenow!=='profile.php' && $pagenow!=='user-edit.php') {
        return;
    }
    // do not change anything for the administrator
    if (current_user_can('administrator')) {
        return;
    }
    add_action( 'admin_footer', 'user_profile_fields_disable_js' );
}
*
 * Disables selected fields in WP Admin user profile (profile.php, user-edit.php)
 
function user_profile_fields_disable_js() {
?>
    <script>
        jQuery(document).ready( function($) {
            var fields_to_disable = ['email', 'role'];
            for(i=0; i<fields_to_disable.length; i++) {
                if ( $('#'+ fields_to_disable[i]).length ) {
                    $('#'+ fields_to_disable[i]).attr("disabled", "disabled");
                }
            }
        });
    </script>
<?php
}
*/





?>