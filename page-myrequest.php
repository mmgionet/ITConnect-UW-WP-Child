<?php define( 'DONOTCACHEPAGE', True ); ?>
<?php
if(isset( $_SERVER['REMOTE_USER'])) {
    $user = $_SERVER['REMOTE_USER'];
} else if(isset($_SERVER['REDIRECT_REMOTE_USER'])) {
    $user = $_SERVER['REDIRECT_REMOTE_USER'];
} else if(isset($_SERVER['PHP_AUTH_USER'])) {
    $user = $_SERVER['PHP_AUTH_USER'];
}
?>
<?php
    $error_flag = False;
    $sn_num = get_query_var('ticketID');
    if( $sn_num == '' ) {
        $new_url = site_url() . '/myrequests/';
        wp_redirect( $new_url );
    }

    if( isset( $_POST['submitted'] ) && isset( $_POST['comments'] ) ) {
        $comments = $_POST['comments'];
        $comments_json = array(
            'actor' => $user,
            'record' => $sn_num,
            'comment' => $comments,
        );
        $comments_json = json_encode( $comments_json );
        $comments_url = SN_URL . '/comment.do';

        // If a POST and have comments - create a comment in SN
        if( defined('SN_USER') && defined('SN_PASS') && defined('SN_URL') ) {
            $args = array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( SN_USER . ':' . SN_PASS ),
                    'Content-Type' => 'application/json',
                ),
                'body' => $comments_json,
            );
        }

        $response = wp_remote_post( $comments_url, $args );
        wp_redirect( $_SERVER['REQUEST_URI'] ); exit;
    }
?>

<?php get_header(); ?>
    <div id="wrap">
        <div id="primary">
			<div id="content" class="it_container">


			<div class="row row-offcanvas row-offcanvas-left">
				<div id="secondary" class="span3 sidebar-offcanvas" role="complementary">
					<div class="stripe-top"></div><div class="stripe-bottom"></div>
                      <div class="" id="sidebar" role="navigation" aria-label="Sidebar Menu">
                      <?php dynamic_sidebar('servicenow-sidebar'); ?>
                      </div>
				</div>
			    <?php while ( have_posts() ) : the_post(); ?>
				<p id="mobile_image" class="span9 visible-phone" <?php custom_main_image();?>>
                    <span id='overlay'></span>
                    <span class='category'>
                    <?php $ancestor_list = array_reverse(get_post_ancestors($post->ID));
                    $is_top = false;
                    if (sizeof($ancestor_list) > 0) {
                        $top_parent = get_page($ancestor_list[0]);
                        echo get_the_title($top_parent);
                    }
                    else {
                        echo get_the_title();
                        $is_top = true;
                    }?>
                    </span>
                </p>
                <?php include('outages.php'); ?>
                <p class="pull-left visible-phone"><a href="#sidebar" class="btn btn-primary btn-offcanvas" data-toggle="offcanvas"></a><span><?php if(!$is_top) { echo get_the_title(); }?></span></p>
				<div id='tertiary' class="span9">

      <span id="arrow-mark" <?php the_blogroll_banner_style(); ?> ></span>

      <?php uw_breadcrumbs(); ?>
            <div id="main_content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title hidden-phone"><?php apply_filters('italics', get_the_title()); ?></h1>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) );
                    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    // prompt the user to log in and leave feedback if appropriate
                    if (is_plugin_active('document-feedback/document-feedback.php') && !is_user_logged_in()): ?>
                    <p id='feedback_prompt'><?php printf(__('<a href="%s">Log in</a> to leave feedback.'), wp_login_url( get_permalink() . '#document-feedback' ) ); ?></p>
                    <?php endif;?>
				</div><!-- .entry-content -->

                <?php
            if(isset($user)) {
                if( isset( $response ) ) {
                    $status = json_decode($response['body'], true);
                    if( $status['Error']['Status'] !== '200' ) {
                        echo '<div class="alert alert-warning" style="margin-top:2em;">';
                        echo 'Attention! Your comment could not be posted: ' . $status['Error']['Text'] . ' (' . $status['Error']['Status'] . ')';
                        echo '</div>';
                        $error_flag = True;
                    }
                }
                ?>
                <div style="text-align:right; color:#777; margin-bottom:2em;"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $user; ?></div>
                <?php
                    //Only do this work if we have everything we need to get to ServiceNow
                    //TODO: this work is repeated above, this should be refactored so we don't do that
                    if( defined('SN_USER') && defined('SN_PASS') && defined('SN_URL') ) {
                        $args = array(
                            'headers' => array(
                                'Authorization' => 'Basic ' . base64_encode( SN_USER . ':' . SN_PASS ),
                            )
                        );

                        $sn_type = substr($sn_num, 0, 3);
                        if( $sn_type == 'REQ' ) {
                            $url = SN_URL . '/u_simple_requests_list.do?JSONv2&displayvalue=true&sysparm_query=number=' . $sn_num . '^u_caller.user_name=' . $user;
                            $sn_type = 'request (REQ)';
                        } else if( $sn_type == 'INC' ) {
                            $url = SN_URL . '/incident.do?JSONv2&displayvalue=true&sysparm_query=number=' . $sn_num . '^caller_id.user_name=' . $user;
                            $sn_type = 'incident (INC)';
                        } else {
                            echo "Unrecognized type";
                            $error_flag = True;
                        }
                        $response = wp_remote_get( $url, $args );
                        $body = wp_remote_retrieve_body( $response );
                        $JSON = json_decode( $body );
                        $record = $JSON->records[0];

                        // Get the comments
                        $url = SN_URL . '/sys_journal_field.do?displayvalue=true&JSONv2&sysparm_cation=getRecords&sysparm_query=active=true^element=comments^element_id=' . $record->sys_id;
                        $response = wp_remote_get( $url, $args );
                        $body = wp_remote_retrieve_body( $response );
                        $JSON = json_decode( $body );
                        $comments = $JSON->records;

                        if ($sn_num !== $record->number) {
                            echo "<div class='alert alert-danger'>$sn_num is not one of your current requests.</div>";
                            $error_flag = True;
                        } else  {
                        echo "<h2 style='margin-top:0;'>$record->short_description&nbsp;&nbsp;<span style='color:#999;'>($record->number)</span></h2>";
                        echo "<h3 class='assistive-text'>Details:</h3>";
                        echo "<table class='table'>";
                        if( !empty( $record->caller_id ) ) {
                            $caller = $record->caller_id;
                        } else if( !empty( $record->u_caller ) ) {
                            $caller = $record->u_caller;
                        } else {
                            $caller = 'UNKNOWN';
                        }

                        // Array of record states and their corresponding classes
                        $states = array(
                            "New" => 'class="label label-success"',
                            "Active" => 'class="label label-success"',
                            "Awaiting User Info" => 'class="label label-warning"',
                            "Awaiting Tier 2 Info" => 'class="label label-success"',
                            "Awaiting Vendor Info" => 'class="label label-success"',
                            "Internal Review" => 'class="label label-success"',
                            "Stalled" => 'class="label label-success"',
                            "Delivered" => 'class="label label-success"',
                            "Resolved" => 'class="label label-default"',
                            "Closed" => 'class="label label-default"',
                        );

                        if ($record->state != "Resolved" && $record->state != "Awaiting User Info") {
                            $record->state = "Active";
                        }


                        echo "<tr><td>Status:</td><td class='request_status'>";
                                if (array_key_exists($record->state, $states)) {
                                    $class = $states[$record->state];
                                    echo "<span $class>$record->state</span>";
                                } else {
                                    echo "<span>$record->state</span>";
                                }
                        echo "</td></tr>";
                        echo "<tr><td>Service:</td> <td>$record->cmdb_ci</td></tr>";
                        echo "<tr><td>Opened on:</td> <td>$record->opened_at</td></tr>";
                        echo "<tr><td>Last Updated:</td> <td>$record->sys_updated_on</td></tr>";
                        echo "</table>";
                        echo "<h3 style='margin-top:2em;'>Description:</h3><div><pre>" . stripslashes($record->description) . " </pre></div>";

                        if(!$error_flag) {
                            $submit_url = site_url() . '/myrequest/' . $sn_num . '/'; ?>
                            <form role='form' action="<?php $submit_url; ?>" method='post'>
                            <div class='form-group' style='margin-bottom:1em;'>
                                <label for='exampleInputPassword1'>Respond to UW-IT:</label>
                                <textarea name='comments' class='form-control' rows='3' style='resize:vertical;'></textarea>
                            </div>
                            <button type='submit' class='btn btn-primary'>Submit</button>
                            <input type="hidden" name="submitted" id="submitted" value="true" />
                        </form>
                        <?php 
                        } else {
                          echo "<h3>Status 403: Unauthorized</h3>";
                          echo "<p>Please log in to your UW NETID in order to view your Requests and Incidents</p>";
                        } 

                        echo "<h3 style='margin-top:2em;'>Additional comments:</h3>";
                       
                        usort( $comments, 'sortByCreatedOnDesc' );
                        echo "<ol style='margin-left:0;'>";
                        
                        
                        foreach( $comments as $comment ) {
                            echo "<li class='media'>";
                                                                            
                            $display_user = $user ;
                                                                                
                            if ($comment->sys_created_by == $user ) {
                                echo "<div class='media-body caller-comments'>";
                            } else {
                                $display_user = "UW-IT SUPPORT STAFF";
                                echo "<div class='media-body support-comments'>";
                            }
                            
                            echo "<div class='comment-timestamp'><strong class='user_name'>$display_user</strong> <span class='create-date'>$comment->sys_created_on</span></div>";
                            echo "<pre>";
                            echo stripslashes($comment->value);
                            echo "</pre>";
                            echo "</div>";
                            echo "</li>";
                        }
                        echo "</ol>";
                        } //end if else to see if incident/request number doesn't match
                      }
                   }
                ?>


				<footer class="entry-meta">
					<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-meta -->
            </article><!-- #post-<?php the_ID(); ?> -->
          </div>

			<?php endwhile; // end of the loop. ?>
				</div>
 			 </div>
			</div><!-- #content -->
		</div><!-- #primary -->
        <div class="push"></div>
   </div><!-- #wrap -->
   <script>
$(document).ready(function(){
    $("form").submit(function(){
        $('button[type=submit], input[type=submit]').attr('disabled',true);
    })
});
   </script>
<?php get_footer(); ?>