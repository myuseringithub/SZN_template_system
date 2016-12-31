<?php
namespace SZN\_Data; // should be on top.

if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

// Those files which need to be included directly have problem using Namespaces - WP_Query doesn't work


/* EXAMPLE
if( self::checkPlatform(array('desktop', 'mobile', 'tablet')) ) {
  $Data = new Data();
  $Data->saveMainQuery();
  $Data->customQuery('book-archivelist.queryargs.php');
  $Data->printQuery('book-archivelist.template.php');
  $Data->resetMainQuery();
}
*/

class DataController extends \SZN\TemplateSystem {
  use QueryTests;
  
  public $queryObject;
  public $tempQuery;

  public $currentQueries = [];
  public $previousQueries = [];

  function __construct() { // runs immediatly when class is instintiated. Beginning connections.
  }

  public function createQueries($queryargsFiles) {
    foreach ($queryargsFiles as $queryPositionInView => $queryFile) {
      if($queryFile && $queryPositionInView) {
        $data[$queryPositionInView] = $this->createQuery($queryFile);
      }
    }
    $this->clearCurrentQueries();
    return $data;
  }

  public function createConditionalQueries($conditionalQueryFiles) {
    foreach ($conditionalQueryFiles as $queryPositionInView => $conditionsFile) {
        $queryFile = include self::getFileDirectoryPath('conditions', $conditionsFile);
        if($queryFile != FALSE && $queryFile && $queryFile != 1) {
          $data[$queryPositionInView] = $this->createQuery($queryFile);
        }
    }
    $this->clearCurrentQueries();
    return $data;
  }

  public function createQuery($queryFile) {
      $this->saveMainQuery();
      $queries['queryObject'] = $this->customQuery($queryFile);
      $queries['postsObjects'] = $queries['queryObject']->get_posts();
      $queries['queryFile'] = $queryFile;
      $this->resetMainQuery();
      return $queries;
  }

  public function clearCurrentQueries() {
    $this->currentQueries = NULL;
  }

  public function saveMainQuery() {
    //Solve next link - http://wordpress.stackexchange.com/questions/20424/wp-query-and-next-posts-link
    $this->tempQuery = $wp_query; // Save old query
		$wp_query->is_single = false; // wouldn't treat it as singlepage.
		$wp_query= null; //clear $wp_query;
  }

  public function resetMainQuery() {
    $wp_query = null; //clear again
    $wp_query = $this->tempQuery; //reset
    wp_reset_postdata(); // Restore original Post Data
  }

  public function rewindPosts () {
    $this->queryObject->rewind_posts();
  }

  public function customQuery($queryFile) {
    require( self::join_paths(self::$locations['queries']['path'], $queryFile) );
    $this->queryObject = new \WP_Query($args); // Create new query
    $wp_query = $this->queryObject; // Trick wordpress to use new query as default inorder to use global parameters i.e. next_posts_link(); - ALLOW NEXT LINK TO WORK
    return $this->queryObject;
  }

  public function getPostsObjectsArray() {
    return $this->queryObject->get_posts();
  }

  public function conditionalQuery($conditionalQueryArgs) {

  }

}
trait QueryTests {
  public function testQuery() {

      if (class_exists('WP_Query'))
      {
        echo 'true';
        // The Query
        $the_query = new \WP_Query( array ('post_type' => 'article') );

        // The Loop
        if ( $the_query->have_posts() ) {
          echo '<ul>';
          while ( $the_query->have_posts() ) {
            $the_query->the_post();
            echo '<li>' . get_the_title() . '</li>';
          }
          echo '</ul>';
        } else {
          // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();

      } else {
      }
  }

  public function testNormalQuery() {
    if (have_posts()) :
      while (have_posts()) : the_post();
      include ( SZN_template_directory('query','variables.php') );

          ?>

          <reference-listitem
                  referencetitle="<?php echo '' . $title . $title . $title; ?>"
                  referencelink=" <?php echo $permalink; ?>"
              ></reference-listitem>

          <?php
              $x ++; // increase order num
      endwhile;


    else : //if no POSTS in QUERY return error or do something else.
      // show error
    endif; // if has posts in query
  }

}
