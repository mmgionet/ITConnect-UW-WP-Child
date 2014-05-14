<?php define( 'DONOTCACHEPAGE', True ); ?>
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
                <div style="text-align:right; color:#777;"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $_SERVER['REMOTE_USER']; ?></div>
                <?php
                    // Only do this work if we have everything we need to get to ServiceNow.
                    if ( defined('SN_USER') && defined('SN_PASS') && defined('SN_URL') ) {
                        $args = array(
                            'headers' => array(
                                'Authorization' => 'Basic ' . base64_encode( SN_USER . ':' . SN_PASS ),
                            )
                        );

                        $states = array(
                            "New" => 'class="label label-success"',
                            "Active" => 'class="label label-success"',
                            "Awaiting User Info" => 'class="label label-success"',
                            "Awaiting Tier 2 Info" => 'class="label label-success"',
                            "Awaiting Vendor Info" => 'class="label label-success"',
                            "Internal Review" => 'class="label label-success"',
                            "Stalled" => 'class="label label-success"',
                            "Delivered" => 'class="label label-success"',
                            "Resolved" => 'class="label label-default"',
                            "Closed" => 'class="label label-default"',
                        );

                        // Requests
                        $url = SN_URL . '/u_simple_requests_list.do?JSONv2&displayvalue=true&sysparm_query=state!=14^u_caller.user_name=' . $_SERVER['REMOTE_USER'];
                        $response = wp_remote_get( $url, $args );
                        $body = wp_remote_retrieve_body( $response );
                        $req_json = json_decode( $body );
                        $has_req = FALSE;
                        if( !empty( $req_json->records ) ) {
                            $has_req = TRUE;
                        }

                        // Incidents
                        $url = SN_URL . '/incident.do?JSONv2&displayvalue=true&sysparm_action=getRecords&sysparm_query=active=true^caller_id.user_name=' . $_SERVER['REMOTE_USER'];
                        $response = wp_remote_get( $url, $args );
                        $body = wp_remote_retrieve_body( $response );
                        $inc_json = json_decode( $body );
                        $has_inc = FALSE;
                        if( !empty( $inc_json->records ) ) {
                            $has_inc = TRUE;
                        }
                ?>

                    <?php if( $has_req || $has_inc ) { ?>
                    <h2 style="margin-top:0;">Services</h2>
                    <?php } ?>

                    <?php if( $has_req ) { ?>
                    <table class="table" style="font-size:.95em;">
                        <thead style="font-size:90%; color:#999;">
                        <tr>
                            <th class="hidden-phone sn_number">Number</th>
                            <th class="hidden-phone sn_service">Service</th>
                            <th class="sn_desc">Description</th>
                            <th class="sn_status">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php
                    usort($req_json->records, 'sortByUpdatedOnDesc');
                    foreach ( $req_json->records as $record ) {

                            if ($record->state == "Resolved" || $record->state == "Closed") {
                                echo "<tr class='resolved_ticket'>";
                            } else {
                                echo "<tr>";
                            }
                    ?>
                            <td class="hidden-phone">
                                <?php
                                $detail_url = site_url() . '/myrequest/' . $record->number;
                                echo "<a href='$detail_url'>$record->number</a>";
                                ?>
                            </td>
                            <td class="hidden-phone">
                                <?php
                                echo "$record->cmdb_ci";
                                ?>
                            </td>
                            <td>
                                <?php
                                echo "$record->short_description";
                                ?>
                            </td>
                            <td class="request_status">
                                <?php
                                    if (array_key_exists($record->state, $states)) {
                                        $class = $states[$record->state];
                                        echo "<span $class style='width:50px;display:inline-block;line-height:15px;'>$record->state</span>";
                                    } else {
                                        echo "<span style='width:50px;display:inline-block;line-height:15px;'>$record->state</span>";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                        </tbody>
                    </table>
                    <?php } else if( $has_inc ) { ?>
                        <p>I'm sorry, you don't have any requests.</p>
                    <?php } ?>

                <?php

                ?>
                    <?php if( $has_req || $has_inc ) { ?>
                    <h2>Incidents</h2>
                    <?php } ?>

                    <?php if( $has_inc ) { ?>
                    <table class="table" style="font-size:.95em;">
                        <thead style="font-size:90%; color:#999;">
                        <tr>
                            <th class="hidden-phone sn_number">Number</th>
                            <th class="hidden-phone sn_service">Service</th>
                            <th class="sn_desc">Description</th>
                            <th class="sn_status">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php
                    usort($inc_json->records, 'sortByUpdatedOnDesc');
                    foreach ( $inc_json->records as $record ) {

                            if ($record->state == "Resolved" || $record->state == "Closed") {
                                echo "<tr class='resolved_ticket'>";
                            } else {
                                echo "<tr>";
                            }
                    ?>
                            <td class="hidden-phone">
                                <?php
                                $detail_url = site_url() . '/myrequest/' . $record->number;
                                echo "<a href='$detail_url'>$record->number</a>";
                                ?>
                            </td>
                            <td class="hidden-phone">
                                <?php
                                echo "$record->cmdb_ci";
                                ?>
                            </td>

                            <td>
                                <?php
                                echo "$record->short_description";
                                ?>
                            </td>
                            <td class="incident_status">
                                <?php
                                    if (array_key_exists($record->state, $states)) {
                                        $class = $states[$record->state];
                                        echo "<span $class style='width:50px;display:inline-block;line-height:15px;'>$record->state</span>";
                                    } else {
                                        echo "<span style='width:50px;display:inline-block;line-height:15px;'>$record->state</span>";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php

                    }
                    ?>
                        </tbody>
                    </table>
                    <?php } else if( $has_req ) { ?>
                        <p>I'm sorry, you don't have any incidents.</p>
                    <?php } ?>

                    <?php if( !$has_req && !$has_inc ) { ?>
                        <p>I'm sorry, you don't have any requests or incidents in the system.</p>
                    <?php } ?>

                <?php } else {?>
                    <p>Whoops! Something went wrong, if this persists, please contact the Administrator.</p>
                <?php } ?>

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
<?php get_footer(); ?>