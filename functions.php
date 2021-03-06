<?php
remove_filter( 'the_title', 'wptexturize' );
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'the_excerpt', 'wptexturize' );
remove_filter( 'comment_text', 'wptexturize' );

if ( ! function_exists( 'uw_setup' ) ):

  function uw_setup()
  {

      add_theme_support( 'automatic-feed-links' );
      add_theme_support( 'post-thumbnails' );

    add_image_size( 'Thimble', 50, 50, true );
    add_image_size( 'Sidebar', 250, 9999, false );
    add_image_size( 'Body Image', 300, 9999, false );
    add_image_size( 'Full Width', 620, 9999, false );

    add_image_size( 'thumbnail-large', 300, 300, true );

      register_nav_menu( 'primary', __( 'Primary Menu', 'uw' ) );
      register_nav_menu( 'footer', __( 'Footer Menu', 'uw' ) );

    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1280 ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 215 ) );

    $args = array(
            'width'         => 1170,
            'height'        => 100,
            'default-image' => get_stylesheet_directory_uri() . '/img/itconnect-banner3.png',
            'uploads'       => true,
    );

    add_theme_support( 'custom-header', $args);
  }

endif;

if ( ! function_exists( 'uw_enqueue_default_styles' ) ):
/**
 * This is where all the CSS files are registered
 *
 * bloginfo('template_directory')  gives you the url to the parent theme
 * bloginfo('stylesheet_directory')  gives you the url to the child theme
 */
  function uw_enqueue_default_styles() {
      global $current_blog;
      $is_child_theme = get_bloginfo('template_directory') != get_bloginfo('stylesheet_directory');
      wp_register_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css' );
      /*wp_register_style( 'bootstrap-responsive', get_bloginfo('template_directory') . '/css/bootstrap-responsive.css', array('bootstrap'), '2.0.3' );*/
      wp_register_style( 'bootstrap-offcanvas',get_bloginfo('stylesheet_directory') . '/css/bootstrap-offcanvas.css', array(), '1.0.0' );

      wp_register_style( 'google-font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300,600.600italic' );
      wp_register_style( 'itconnect-master', get_bloginfo('stylesheet_directory') . '/style.css', array(), '1.0' );
      wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
      wp_enqueue_style( 'bootstrap' );
      wp_enqueue_style( 'bootstrap-offcanvas' );
      /* wp_enqueue_style( 'bootstrap-responsive' ); */
      wp_enqueue_style( 'google-font-open-sans' );
      wp_enqueue_style( 'itconnect-master' );
      wp_enqueue_style( 'font-awesome' );
  }

endif;

if ( ! function_exists( 'uw_enqueue_default_scripts' ) ):
/**
 * This is where all the JS files are registered
 *
 * bloginfo('template_directory')  gives you the url to the parent theme
 * bloginfo('stylesheet_directory')  gives you the url to the child theme
 */
  function uw_enqueue_default_scripts() {
    wp_deregister_script('jquery'); //we use googles CDN below
    wp_deregister_script('header'); //we use our own below
    wp_register_script( 'jquery','//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', array(), '1.7.2' );
    wp_register_script( 'header', get_bloginfo('stylesheet_directory') . '/js/header.js', array('jquery'), '1.4.1' );
    wp_register_script( 'jquery.firenze', get_bloginfo('template_directory') . '/js/jquery.firenze.js', array('jquery'), '1.0.1' );
    wp_register_script( 'jquery.weather', get_bloginfo('template_directory') . '/js/jquery.weather.js', array('jquery'), '1.1' );
    wp_register_script( 'jquery.placeholder', get_bloginfo('template_directory') . '/js/jquery.placeholder.js', array('jquery'), '1.0' );
    wp_register_script( 'jquery.imageexpander', get_bloginfo('template_directory') . '/js/jquery.imageexpander.js', array('jquery'), '1.0.5' );
    wp_register_script( 'jquery.waypoints', get_bloginfo('template_directory') . '/js/jquery.waypoints.min.js', array('jquery'), '1.1.7' );
    wp_register_script( 'jquery.imagesloaded', get_bloginfo('template_directory') . '/js/jquery.imagesloaded.min.js', array('jquery'), '2.1.1' );
    wp_register_script( 'jquery.parallax', get_bloginfo('template_directory') . '/js/jquery.parallax.min.js', array('jquery'), '1.0' );
    wp_register_script( 'jquery.404', get_bloginfo('stylesheet_directory') . '/js/404.js', array('jquery'), '1.0' );
    wp_register_script( 'jquery.masonry', get_bloginfo('template_directory') . '/js/jquery.masonry.min.js', array('jquery') );
    wp_register_script( 'offcanvas', get_bloginfo('stylesheet_directory') . '/js/bootstrap-offcanvas.js', array('jquery') );
    wp_register_script( 'jquery.tablesorter', get_bloginfo('stylesheet_directory') . '/js/jquery.tablesorter.js', array('jquery') );
    wp_register_script( 'itconnect', get_bloginfo('stylesheet_directory') . '/js/itconnect.js', array('jquery') );

    wp_register_script( 'widget-youtube-playlist', get_bloginfo('template_directory') . '/js/widget-youtube-playlist.js', array('jquery','swfobject','jquery.imagesloaded') );
    wp_register_script( 'uw-gallery', get_bloginfo('template_directory') . '/js/gallery.js', array('jquery','jquery.imagesloaded'), '1.1' );
    wp_register_script( 'trumba', '//www.trumba.com/scripts/spuds.js' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery.tablesorter' );
    wp_enqueue_script( 'header' );
    wp_enqueue_script( 'jquery.firenze' );
    wp_enqueue_script( 'jquery.placeholder' );
    wp_enqueue_script( 'jquery.imageexpander' );

    wp_enqueue_script( 'offcanvas' );
    wp_enqueue_script( 'itconnect' );

    wp_enqueue_script('uw-gallery');
    wp_enqueue_script( 'trumba' );


    if( is_404() || (isset($_REQUEST['status']) && $_REQUEST['status'] == 401)) {

      wp_enqueue_script( 'jquery.imagesloaded' );
      wp_enqueue_script( 'jquery.parallax' );
      wp_enqueue_script( 'jquery.404' );

    }
  }

endif;

if (! function_exists ( 'it_widgets_init' )):

  function it_widgets_init()
  {
    $args = array(
      'name' => 'News Sidebar',
      'id' => 'news-sidebar',
      'description' => 'Widgets for the left column of the archives page on ITConnect',
      'before_widget' => '<div id="%1$s class="widget %2$s">',
      'after_widget' => '</div>'
    );

    register_sidebar($args);

    $args2 = array(
      'name' => 'Search Sidebar',
      'id' => 'search-sidebar',
      'description' => 'Widgets for the left column of the search page on ITConnect',
      'before_widget' => '<div id="%1$s class="widget %2$s">',
      'after_widget' => '</div>'
    );

    register_sidebar($args2);

    $args3 = array(
      'name' => 'HuskyBytes Sidebar',
      'id' => 'huskybytes-sidebar',
      'description' => 'Widgets for the left column of the HuskyBytes page on ITConnect',
      'before_widget' => '<div id="%1$s class="widget %2$s">',
      'after_widget' => '</div>'
    );

    register_sidebar($args3);

    $args4 = array(
      'name' => 'ServiceNow Sidebar',
      'id' => 'servicenow-sidebar',
      'description' => 'Widgets for the left column of the ServiceNow pages on ITConnect',
      'before_widget' => '<div id="%1$s class="widget %2$s">',
      'after_widget' => '</div>'
    );

    register_sidebar($args4);


  }
endif;

// Tegrity embed code
// [tegrity url='https://tegr.it/y/XXXXX']
if (! function_exists ( 'tegrity_func' )):
  function tegrity_func( $atts ) {
    extract( shortcode_atts( array(
        'url' => '',
    ), $atts ) );

    $tegrity = "<script type='text/javascript' src='{$url}'></script>";

    return $tegrity;
  }
  add_shortcode( 'tegrity', 'tegrity_func' );
endif;

// Trumba spuds
// [spud webname='foo' spudtype='bar']
if (! function_exists ( 'spud_func' )):
  function spud_func( $atts ) {
    extract( shortcode_atts( array(
        'webname' => 'sea_campus',
        'spudtype' => 'main',
    ), $atts ) );

    //Trumba alerts user if they didn't set webname or spudtype
    unset($atts['webname'], $atts['spudtype']);
    $keys = array_keys($atts);
    $values = array_values($atts);
    $i = 0;
    foreach($keys as $key) {
        if ($key === "teaserbase" || $key === "detailbase") {
            if ($key === "teaserbase") {
                $key = "teaserBase";
            } elseif ($key === "detailbase") {
                $key = "detailBase";
            }
            $embedSpud .= ', '.$key.': \''.$values[$i].'\'';
        }
        elseif ($key === "url") {
            $urlArgumentValues = explode(' ', trim($values[$i]));
            $count = 0;
            $urlAddToEmbedSpud = "";
            foreach($urlArgumentValues as $urlOneValue) {
                if($count == 0) {
                    $urlAddToEmbedSpud = '{'.$urlOneValue.': "';
                    $count++;
                } else {
                    $urlAddToEmbedSpud .= $urlOneValue." ";
                }
            }
            $embedSpud .= ', '.$key.': '.$urlAddToEmbedSpud."\"} ";
        }
        $i++;
    }

    $spud = "<script type='text/javascript'>\$Trumba.addSpud({webName: '{$webname}', spudType: '{$spudtype}'$embedSpud});</script>";
    return $spud;
    }
    add_shortcode( 'spud', 'spud_func' );
endif;

add_action( 'widgets_init', 'it_widgets_init' );

require('main-image-options.php' );

if ( ! function_exists( 'is_custom_main_image' ) ):
  function is_custom_main_image()
  {
    $option = get_option('main_image'); 

    if ( ! is_array( $option) )
      return false;

    $main_image = (array) $option['main_image'];
    if ( isset($main_image['custom'] ))  
      return true;

    return false;
  }
endif;

if ( ! function_exists( 'custom_main_image' ) ):  
  function custom_main_image() 
  {
    $option = get_option('main_image');

    if ( ! is_array( $option) )
      return;

    $main_image = (array) $option['main_image'];
    if ( isset($main_image['custom'] )) {
      echo ' style="background:url('.$main_image['custom']['url'].') no-repeat; background-size:cover;" ' ;
    }   
  }
endif;

require('outages_options.php');

if ( ! function_exists( 'outages_active' ) ):
  function outages_active() {
    return (get_option('outages') == 'yes');
  }
endif;

function in_comment_blacklisted_words($string, $array) { 
    foreach($array as $ref) { if(strstr($string, $ref)) { return true; } } 
    return false;
}

function drop_bad_comments() {
    if (!empty($_POST['comment'])) {
        $post_comment_content = $_POST['comment'];
        $lower_case_comment = strtolower($_POST['comment']);
        $comment_blacklist_words = array(
            '<script>'
        );
        if (in_comment_blacklisted_words($lower_case_comment, $comment_blacklist_words)) {
            wp_die( __('JavaScript is not allowed in comments.  Please resubmit comment without embedded JavaScript.') );
        }
    }
}
add_action('init', 'drop_bad_comments');

if ( ! function_exists( 'custom_prev_next_links') ) : 
  function custom_prev_next_links( $nav_id='prev-next' ) { 
    global $query;

    if ( $query->max_num_pages > 1 ) : 

        $big = 999999999; // need an unlikely integer
        $current = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $links = paginate_links( array(
          'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format' => '?paged=%#%',
          'type' => 'array',
          'current' => max( 1, get_query_var('paged') ),
          'total' => $query->max_num_pages
        ) );  

      echo '<div class="pagination pagination-centered"><ul>';

      foreach ($links as $index=>$link) :

        $link = str_replace('span', 'a', $link);
           if ( strip_tags($link) == $current ) 
          echo "<li class=\"disabled\"><a href='javascript:void(0);'>$current</a></li>";
        else
          echo "<li>$link</li>";

      endforeach;

      echo '</ul></div>';

   endif;
  }
endif;

/*
function add_query_vars($qvars) {
    $qvars[] = "ticketID";
    return $qvars;
}
add_filter('query_vars', 'add_query_vars');

function add_rewrite_rules($aRules) {
    $aNewRules = array('myrequest/([^/]+)/?$' => 'index.php?pagename=myrequest&ticketID=$matches[1]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}
add_filter('rewrite_rules_array', 'add_rewrite_rules');

// Takes two datetime objects and sorts descending by sys_updated_on
function sortByUpdatedOnDesc($a, $b) {
    $dt_a = DateTime::createFromFormat('m-d-Y H:i:s', $a->sys_updated_on);
    $dt_b = DateTime::createFromFormat('m-d-Y H:i:s', $b->sys_updated_on);
    return $dt_a < $dt_b;
}

// Takes two datetime objects and sorts descending by sys_created_on
function sortByCreatedOnDesc($a, $b) {
    $dt_a = DateTime::createFromFormat('m-d-Y H:i:s', $a->sys_created_on);
    $dt_b = DateTime::createFromFormat('m-d-Y H:i:s', $b->sys_created_on);
    return $dt_a < $dt_b;
}

// Takes two strings and sorts descending by number
function sortByNumberDesc($a, $b) {
    return $a->number < $b->number;
}
*/

$template_dir = get_stylesheet_directory();
require( $template_dir . '/inc/documentation.php' );

function custom_error_pages() {
    global $wp_query;
    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 401) {
        $wp_query->is_404 = FALSE;
        $wp_query->is_page = TRUE;
        $wp_query->is_singular = TRUE;
        $wp_query->is_single = FALSE;
        $wp_query->is_home = FALSE;
        $wp_query->is_archive = FALSE;
        $wp_query->is_category = FALSE;
        add_filter('wp_title', 'custom_error_titles', 65000, 2);
        status_header(401);
        get_template_part('401');
        exit;
    }
}
// Enable an ajax function in WP. Based on http://premium.wpmudev.org/blog/how-to-use-ajax-with-php-on-your-wp-site-without-a-plugin/
/*function enable_ajax() {
    wp_localize_script( 'attach', 'get_attach', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'attach', get_stylesheet_directory_uri().'/js/get_attach.js', 'jquery', true);
}
add_action('template_redirect', 'enable_ajax');

//Builds a request to Service Now and returns results as a JSON object.
function get_SN($url, $args) {
    $url = SN_URL . $url;
    $response = wp_remote_get( $url, $args );
    $body = wp_remote_retrieve_body( $response );
    $json = json_decode( $body );
    return $json;
}

function service_status() {
    $SN_URL = SN_URL;
    $hash = base64_encode( SN_USER . ':' . SN_PASS );
    $args = array(
        'headers' => array(
            'Authorization' => 'Basic ' . $hash,
        ),
        'timeout' => 25,
    );
    // All active, Medium and High Impacted Incidents
    $url = $SN_URL . '/incident_list.do?JSONv2&sysparm_query=active%3Dtrue%5EstateNOT%20IN6%2C7%5Eimpact%3D2%5EORimpact%3D1%5Eu_sectorNOT%20INK20%2CPNWGP%2CPWave%5EORu_sector%3D&displayvalue=true';
 
    $response = wp_remote_get( $url, $args );
    $body = wp_remote_retrieve_body( $response );
    $JSON = json_decode( $body );
        if(!$body) {
            echo "<div class='alert alert-warning' style='margin-top:2em;'>We are currently experiencing problems retrieving the status of our services. Please try again in a few minutes.</div>";
        }
        elseif(empty($JSON->records)) {
            echo "<div class='alert alert-warning' style='margin-top:2em;'>All services are operational.</div>";
        } 

        if ( !empty( $JSON->records ) ) {
            $sn_data = array();
            foreach( $JSON->records as $record ) { 
                if( !isset( $sn_data[$record->cmdb_ci] ) ) { 
                    $sn_data[$record->cmdb_ci] = array();
                    unset($first);
                }
                $create = $record->sys_created_on;
                if( !isset( $first ) ) { 
                    $first = $create;
                }
                if($create < $first) {
                    $first = $create;
                }
                $sn_data[$record->cmdb_ci][] = $record;
                $sn_data[$record->cmdb_ci][] = $first;
            }

                echo "<h2 class='assistive-text' id='impact_headeing'>Impacted Services</h2>";

                # put the services into a single ordered list
                echo "<ol style='list-style:none;padding-left:0;margin-left:0;' aria-labelledby='impact_heading'>";

                foreach( $sn_data as $ci) {
                    $service = array_search($ci, $sn_data);

                    // handle the case of blank services and switches who's 'name' is a sequence of 5 or more numbers
                    if ( $service !== '' && !preg_match('/^\d{5,}$/', $service) ) { 
                        $time = end($ci);
                        echo "<li style='margin-top:10px;' class='clearfix'><span style='display:inline-block; max-width:50%;font-weight:bold;' class='pull-left'>$service</span><span style='color:#aaa;font-size:95%;' class='pull-right'> <span class='hidden-phone hidden-tablet'>Reported at</span> $time </span></li>";
                    }

                }

                echo "</ol>";
        }

            echo "<p class='alert alert-info' style='margin-top: 2em;'>Experiencing IT problems not listed on this page? Need more information about a service impact? Want to provide feedback about this page? <a href='/itconnect/help'>Get help.</a></p>";
          die();
}

add_action('wp_ajax_nopriv_service_status', 'service_status');
add_action('wp_ajax_service_status', 'service_status');
*/

function custom_error_titles() {
    if (isset($_REQUEST['status']) && $_REQUEST['status'] == 401) {
        return "Unauthorized User.";
    }
}

add_action('wp', 'custom_error_pages');

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

function edit_admin_menus() {
    remove_submenu_page('themes.php', 'uw-patch-band');
}

add_action('admin_menu', 'edit_admin_menus', 999);

//Footer Options
add_action('admin_menu', 'custom_footer_fields');

function custom_footer_fields() {
    add_submenu_page('themes.php','Footer Content', 'Footer Content', 'administrator', __FILE__, 'build_options_page');
}

add_action('admin_init', 'reg_build_options');

function build_options_page() {
   ?>
   <div>
    <h2>Footer Content</h2>
    <p>Change ITConnect footer content here. Please <b>do not</b> enter any HTML in to the fields</p>
    <form method="POST" action="options.php" enctype="multipart/form-data">
        <?php settings_fields('footer_options'); ?>
        <?php do_settings_sections(__FILE__); ?>
        <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form>
   </div>
   <?php
}

function reg_build_options() {
    register_setting('footer_options', 'footer_options', 'validate_setting');
    add_settings_section('main_section', 'Options', 'section_cb', __FILE__);
    add_settings_field('online', 'Contact form (URL) <br /><em style="font-weight: 300;">Example: http://site.com/page</em>', 'set_online', __FILE__, 'main_section');
    add_settings_field('email', 'Email <br /><em style="font-weight: 300;">Example: user@uw.edu</em>', 'set_email', __FILE__, 'main_section');
    add_settings_field('phone', 'Phone <br /><em style="font-weight: 300;">Example: 999-999-9999', 'set_phone', __FILE__, 'main_section');
    add_settings_field('inperson_text','In-Person Service Center Text (Name of location) <br /><em style="font-weight: 300;">Example: UW Tower, C-3000</em>', 'set_inperson_text', __FILE__, 'main_section');
    add_settings_field('inperson_hours','In-Person Service Center Hours (Enter Text) <br /><em style="font-weight: 300;">Example: M-F 8-8</em>', 'set_inperson_hours', __FILE__, 'main_section');
    add_settings_field('inperson_url','In-Person Service Center URL (Link to page) <br /><em style="font-weight: 300;">Example: http://site.com/page</em>', 'set_inperson_url', __FILE__, 'main_section');
    add_settings_field('services_url', 'Service Catalog URL<br /><em style="font-weight: 300;">Example: http://site.com</em>', 'set_services_url', __FILE__, 'main_section');

    add_settings_field('twitter','Twitter (URL) <br /><em style="font-weight:300;">Exapmle:http://site.com/page</em>', 'set_twitter',__FILE__,'main_section');
    add_settings_field('youtube','Youtube (URL) <br /><em style="font-weight:300;">Exapmle:http://site.com/page</em>', 'set_youtube',__FILE__,'main_section');
}

function validate_setting($footer_options) {
    return $footer_options;
}

function section_cb() {
//empty callback, just needed for function argument
}

function set_online() {
    $options = get_option('footer_options'); 
    $url_pattern = '(http|https|ftp)://[a-zA-Z0-9_\-\.\+]+\.[a-zA-Z0-9]+([/a-zA-z0-9_\-\.\+\?=%]*)?';
    $warning = 'Example: http://example.com/page';
   
    echo "<input name='footer_options[online]' pattern='$url_pattern' title='$warning' type='text' size='45' value='{$options['online']}' />";
}

function set_email() {
    $options = get_option('footer_options');
    $email_pattern = '[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9_\.\-]+\.[a-zA-Z0-9]+)';
    $warning = 'Example: user@uw.edu';
    echo "<input name='footer_options[email]' pattern='$email_pattern' title='$warning'type='text' value='{$options['email']}' />";
}

function set_phone() {
    $options = get_option('footer_options');
    $phone_pattern = '(\d{3}?\-?\d{3}\-?\d{4})';
    $warning = 'Example: 999-999-9999';
    echo "<input name='footer_options[phone]' pattern='$phone_pattern' title='$warning' type='text'  value='{$options['phone']}' />";
}

function set_inperson_text() {
    $options = get_option('footer_options');
    echo "<input name='footer_options[inperson_text]' type='text' size='45' value='{$options['inperson_text']}' />";
}

function set_inperson_hours() {
    $options = get_option('footer_options');
    echo "<input name='footer_options[inperson_hours]' type='text' size='45' value='{$options['inperson_hours']}' />";
}

function set_inperson_url() {
    $options = get_option('footer_options');
    $url_pattern = '(http|https|ftp)://[a-zA-Z0-9_\-\.\+]+\.[a-zA-Z0-9]+([/a-zA-z0-9_\-\.\+\?=%]*)?';
    $warning = 'Example: http://example.com/page';
    echo "<input name='footer_options[inperson_url]'  pattern='$url_pattern' title='$warning'  type='text' size='45'  value='{$options['inperson_url']}' />";
}

function set_services_url() {
    $options = get_option('footer_options');
    $url_pattern = '(http|https|ftp)://[a-zA-Z0-9_\-\.\+]+\.[a-zA-Z0-9]+([/a-zA-z0-9_\-\.\+\?=%]*)?';
    $warning = 'Example: http://example.com';
    echo "<input name='footer_options[services_url]' pattern='$url_pattern' title='$warning' type='text' size='45' value='{$options['services_url']}' />";

}

function set_twitter() {
    $options = get_option('footer_options');
    $url_pattern = '(http|https|ftp)://[a-zA-Z0-9_\-\.\+]+\.[a-zA-Z0-9]+([/a-zA-z0-9_\-\.\+\?=%]*)?';
    $warning = 'Example: http://example.com/page';
    echo "<input name='footer_options[twitter]' pattern='(http|ftp)://[a-zA-Z0-9_\-\.\+]+\.[a-zA-Z0-9]+([/a-zA-z0-9_\-\.\+\?=%]*)?' title='$warning' type='text' size='45' value='{$options['twitter']}' />";
}

function set_youtube() {
    $options = get_option('footer_options');
    $url_pattern = '(http|https|ftp)://[a-zA-Z0-9_\-\.\+]+\.[a-zA-Z0-9]+([/a-zA-z0-9_\-\.\+\?=%]*)?';
    $warning = 'Example: http://example.com/page';
    echo "<input name='footer_options[youtube]'  pattern='$url_pattern' title='$warning' type='text' size='45' value='{$options['youtube']}' />";
}
//End Footer Options
?>
