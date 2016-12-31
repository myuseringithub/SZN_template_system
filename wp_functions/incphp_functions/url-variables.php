<?php

/* When the function is hooked into query_vars, WordPress passes the existing array of query variables to the function.
http://www.rlmseo.com/blog/passing-get-query-string-parameters-in-wordpress-url/
*/

function themeslug_query_vars( $qvars ) {
  $qvars[] = 'tx';
  return $qvars;
}
add_filter( 'query_vars', 'themeslug_query_vars' , 10, 1 );

// Usage:
/*
global $wp_query;
if (isset($wp_query->query_vars['tx']))
{
	$tx = $wp_query->query_vars['tx'];
}
*/
?>
