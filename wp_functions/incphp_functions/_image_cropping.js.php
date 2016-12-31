function ipin_footer_scripts() {
?>
<script>
/* GREAT SCRIPT FOR IMAGE CROPING  -  http://demo.solemone.de/overflow-image-with-vertical-centering-for-responsive-web-design/
OTHER http://stackoverflow.com/questions/7273338/how-to-vertically-align-an-image-inside-div
jQuery(document).ready(function() {

var imageHeight, wrapperHeight, overlap, container = jQuery('.image-wrap');

function centerImage() {
	imageHeight = container.find('img').height();
	wrapperHeight = container.height();
	overlap = (wrapperHeight - imageHeight) / 2;
	container.find('img').css('margin-top', overlap);
}

jQuery(window).on("load resize", centerImage);

var el = document.getElementById('wrapper');
if (el.addEventListener) {
	el.addEventListener("webkitTransitionEnd", centerImage, false); // Webkit event
	el.addEventListener("transitionend", centerImage, false); // FF event
	el.addEventListener("oTransitionEnd", centerImage, false); // Opera event
}
});
*/
</script>
<?php
}
add_action('wp_footer', 'ipin_footer_scripts');
