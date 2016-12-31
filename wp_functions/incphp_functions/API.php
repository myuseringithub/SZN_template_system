<?php
// add_action( 'rest_api_init', 'register_extended_api'  );
//
// function register_extended_api(){
// 		register_api_field('mcq', 'safi',
// 				array(
// 						'get_callback'    => 'register_meta_data',
// 						'update_callback' => null,
// 						'schema'          => null,
// 				)
// 		);
// }
//
// function register_meta_data($object, $field_name, $request){
// 		return $object;
// }
add_action ('plugins_loaded', function (){

	// $timeout_value = 9000;
	// apply_filters ( 'http_request_timeout', $timeout_value );

	add_action( 'rest_api_init', function () {

		register_rest_route( 'szn', '/mcq/', array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'transientExaminationWithQuestions',
		) );

		// register_rest_route( 'szn', '/mcq/(?P<examinationid>\d+)', array(
		// 	'methods' => WP_REST_Server::READABLE,
		// 	'callback' => 'transientExaminationWithQuestions',
		// ) );

	}, 10 );

	function transientExaminationWithQuestions(WP_REST_Request $request) {
		$examinationid = $request['examinationid'];
		$examinationnumber = $request['examinationnumber'];
		// $transientName = 'special_query_resultsy' . $examinationid;
		// if ( false === ( $special_query_results = get_transient( $transientName ) ) ) {
	    // It wasn't there, so regenerate the data and save the transient
	    $special_query_results = examinationWithQuestions($examinationid, $examinationnumber);
	  //   set_transient( $transientName, $special_query_results, 60*60*12 );
		// }
		return $special_query_results;
	}


	/**
	* Grab latest post title by an author!
	*
	* @param array $data Options for the function.
	* @return string|null Post title for the latest,  * or null if none.
	*/
	function examinationWithQuestions( $examinationid = null , $examinationnumber = null) {

		$custom_post_types = array('examination');
		if ($examinationnumber == null) {
			$examinationnumber = 100;
		}
		$args = [
							'post_type' => $custom_post_types,
							'posts_per_page' => $examinationnumber,
							'orderby' => 'menu_order',
							'order'   => 'ASC',
						];
		if($examinationid) {
			$args['p'] = $examinationid;
		}
		$examinationQueryObject = new WP_Query($args);
		$examinationPosts = $examinationQueryObject->get_posts();


		$custom_post_types = array('mcq');
		$args = array (
			'post_type' => $custom_post_types,
			'posts_per_page' => 2000,
			'orderby' => 'date'
		);
		$mcqQueryObject = new WP_Query($args);
		$mcqPosts = $mcqQueryObject->get_posts();


		$DataManipulation = new SZN\DataManipulation($mcqPosts, $examinationPosts);

		// return new WP_REST_Response( $examinationPostsPreparedArray, 200 );
		return $DataManipulation->mergeQuestionsWithExaminations();
	}



/* END Plugins Loaded */
});
