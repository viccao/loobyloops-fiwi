<?php
/**
 * Functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package Smores
 * @since Smores 2.0
 */

define("THEME_ROOT", get_stylesheet_directory());

// Composer dependencies
require_once __DIR__ . '/lib/vendor/autoload.php';

use Smores\Smores;
use Smores\TopBarPageWalker;
use Smores\TopBarWalker;


$rand = rand();

$smores = new Smores(
    array( // Includes
        'lib/admin',         // Add admin scripts
        'lib/ajax',          // Add ajax scripts
        'lib/classes',       // Add classes
        'lib/custom-fields', // Add custom field scripts
        'lib/forms',         // Add form scripts
        'lib/images',        // Add images scripts
        'lib/post-types',    // Add post type scripts
        'lib/shortcodes',    // Add shortcode scripts
        'lib/widgets',       // Add widget scripts
    ),
    array( // Assets
        'css'             => '/dist/css/styles.min.css?rand=' .$rand,
        'js'              => '/dist/js/scripts.min.js?rand=' .$rand,
        'modernizr'       => '/dist/js/vendor/modernizr.min.js',
        'jquery'          => '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.js',
        'jquery_fallback' => '/dist/js/vendor/jquery.min.js',
)
);


$client_id = '377766867212-ran700439ndgd7m1rnbpv4pbl8l2lc7o.apps.googleusercontent.com';
$Email_address = 'fiwi-conf-calendars@fiwi-request-sender.iam.gserviceaccount.com';
$key_file_location = __DIR__ . '/keys/fiwi-request-sender-186b0c9345b8.p12';

$client = new Google_Client();
$client->setApplicationName("FIWI_Conf_Calendars");
$key = file_get_contents($key_file_location);

// separate additional scopes with a comma

$scopes ="https://www.googleapis.com/auth/calendar.readonly";
$cred = new Google_Auth_AssertionCredentials(
  $Email_address,
  array($scopes),
  $key
  );
$client->setAssertionCredentials($cred);
if($client->getAuth()->isAccessTokenExpired()) {
  $client->getAuth()->refreshTokenWithAssertion($cred);
}
$service = new Google_Service_Calendar($client);


date_default_timezone_set('America/New_York');
/**
 * [smores_numeric_pagination description]
 *
 * @param  [type] $custom_query [description]
 * @param  string $classes      [description]
 * @return [type]               [description]
 */
function smores_numeric_pagination($custom_query = false, $classes = '')
{
    $query = null;

    if ($custom_query) {
        $query = $custom_query;
    } else {
        global $wp_query;

        $query = $wp_query;

        if (is_singular()) {
            return;
        }
    }

    /** Stop execution if there's only 1 page */
    if ($query->max_num_pages <= 1) {
        return;
    }

    $paged = (get_query_var('paged')) ? absint( get_query_var('paged')) : 1;
    $max   = $query->max_num_pages;

    /** Add current page to the array */
    if ($paged >= 1) {
        $links[] = $paged;
    }

    /** Add the pages around the current page to the array */
    if ($paged >= 3) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if (($paged + 2) <= $max) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    echo "<ul class=\"pagination {$classes}\" role=\"menubar\" aria-label=\"Pagination\">\n";

    /** Previous Post Link */
    if (get_previous_posts_link()) {
        printf("<li class=\"arrow show-for-medium-up previous-link\">%s</li>\n", get_previous_posts_link());
    } else {
        echo '<li class="arrow unavailable previous-link"><a href="#">&laquo; Previous Page</a></li>';
    }

    /** Link to first page, plus ellipses if necessary */
    if (!in_array(1, $links)) {
        $class = ($paged === 1) ? ' class="current"' : '';

        printf("<li%s><a href=\"%s\">%s</a></li>\n", $class, esc_url(get_pagenum_link(1)), '1');

        if (!in_array(2, $links)) {
            echo '<li>…</li>';
        }
    }

    /** Link to current page, plus 2 pages in either direction if necessary */
    sort($links);

    foreach ((array) $links as $link) {
        $class = ($paged === $link) ? ' class="current"' : '';

        printf("<li%s><a href=\"%s\">%s</a></li>\n", $class, esc_url(get_pagenum_link($link)), $link);
    }

    /** Link to last page, plus ellipses if necessary */
    if (!in_array($max, $links)) {
        if (!in_array($max - 1, $links)) {
            echo "<li>…</li>\n";
        }

        $class = ($paged === $max) ? ' class="current"' : '';

        printf("<li%s><a href=\"%s\">%s</a></li>\n", $class, esc_url(get_pagenum_link($max)), $max);
    }

    /** Next Post Link */
    if (get_next_posts_link()) {
        printf("<li class=\"arrow next-link\">%s</li>\n", get_next_posts_link());
    } else {
        echo '<li class="arrow unavailable show-for-medium-up next-link"><a href="#">Next Page &raquo;</a></li>';
    }

    echo "</ul>\n";
}


if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array('page_title' => 'Site Options', 'icon_url' =>'dashicons-admin-generic','position' => '2'));
}



/**
*	Smores Included Plugin Installs
*   Advanced Custom Fields PRO
*   Advanced Custom Fields: Font Awesome
*   Advanced Custom Fields: Contact Form 7
*   Contact Form 7
*   EWWW Image Optimizer
*   Grunt Sitemap Generator
*   Regenerate Thumbnails
*   WP Security Audit Log
**/

add_action('after_switch_theme' , 'smores_install_plugin_pack');

// First check it make sure this function has not been done
function smores_install_plugin_pack(){
//    if (get_option('smores_plugin_installer_ran') != "yes") {
//
//        smores_run_install_plugin_pack();
//
//    // Now that function has been run, set option so it wont run again
//        update_option( 'smores_plugin_installer_ran', 'yes' );
//
//    }
    smores_run_install_plugin_pack();
}

add_action('switch_theme', 'smores_reset_installer_switch');
function smores_reset_installer_switch(){
    if(get_option('smores_plugin_installer_ran') == "yes"){
       // update_option( 'smores_plugin_installer_ran', 'no');
    }
}

function smores_run_install_plugin_pack(){
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $zippedThemePlugins = get_template_directory() . '/smores_pu/plugins/';
    $pluginsDirectory =  get_home_path() . 'wp-content/plugins/';

    smores_check_for_and_install_plugins( $zippedThemePlugins, $pluginsDirectory );
    //smores_plugin_directory_scanner( $pluginsDirectory, $activate = true );
    smores_clean_tmp_directory( $zippedThemePlugins . 'tmp' );

    return true;
}



/**
 * Checks for installed plugins. If not previously installed. Installs and activates plugin.
 * @param  string $zippedPath           Path to zipped plugins required for the theme.
 * @param  string $installedPluginsPath Path to currently installed plugins.
 * @return bool                       true if function complets.
 */
function smores_check_for_and_install_plugins( $zippedPath, $installedPluginsPath ){
    $alreadyInstalled = array();
    $tmpPlugins = array();
    $tmpUnzipPath = $zippedPath . 'tmp/';


    if( !file_exists($tmpUnzipPath) ){
        mkdir( $tmpUnzipPath , 0774 );
    }

    if( file_exists( $tmpUnzipPath ) == false ){
        echo "ERROR, tmp directory does not exist!"; //This needs to be dealt with properly later return an error
    } else {
        smores_unpack_plugins($zippedPath , $tmpUnzipPath);

        $tmpPlugins = smores_plugin_directory_scanner($tmpUnzipPath , $activate = false);
        $alreadyInstalled = smores_plugin_directory_scanner($installedPluginsPath , $activate = false);
    }

    foreach ($tmpPlugins as $tmpPlugin) {
        $i = 1;

        if(sizeof($alreadyInstalled) == 0 ){
            $alreadyInstalled[] = array('Name' => null , 'FilePath' => null , 'DirectoryPath' => null);
        }

        foreach ($alreadyInstalled as $installedPlugin) {
            if( $installedPlugin['Name'] == $tmpPlugin['Name'] ){

                //Do Nothing
                break;

            } else {

                if($i == sizeof($alreadyInstalled)){
                    $getDirectoryToCopy = explode('/', $tmpPlugin['DirectoryPath']);
                    $directoryToCopy = array_pop($getDirectoryToCopy);
                    $fileToCopyPath = explode('/', $tmpPlugin['FilePath']);
                    $fileToCopy = '/' . array_pop($fileToCopyPath);
                    $newCompleteDirectory = $installedPluginsPath . $directoryToCopy;

                    if(!file_exists( $newCompleteDirectory)){
                        mkdir( $newCompleteDirectory);
                    }

                    smores_recursive_copy($tmpPlugin['DirectoryPath'] ,  $newCompleteDirectory);

                    $activateMe[] = $installedPluginsPath . $directoryToCopy . $fileToCopy;
                }
            }

            $i++;

        }
    }

    $pluginsToActivate = sizeof($activateMe);
    for($i=0; $i<$pluginsToActivate; $i++){
        activate_plugin($activateMe[$i]);
    }

    unset($tmpUnzipPath);
    return true;
}

/**
 * Scans the given directory for .zip files.
 * Extracts zips to specified directory
 *
 * @param string $zippedPath	Path to directory with zip files.
 * @param string $unzippedPath	Path to unzip files to.
 * @return bool true
 */
function smores_unpack_plugins( $zippedPath , $unzippedPath ){

    foreach ( scandir($zippedPath) as $zippedPluginFile ) {
        $zip = new ZipArchive();

        if ( substr($zippedPluginFile, -4) == '.zip' ){
            $zip->open($zippedPath . $zippedPluginFile );
            $zip->extractTo( $unzippedPath);
            //$zip->close; add if exists for close() method
            unset($zip);
        }
    }

    return true;
}


/**
 * Scans a given directory for php files which contain WP plugin info.
 * @param  string  $dir      Wordpress Plugins directory usually /wp-content/plugins
 * @param  boolean $activate When set to true found plugins are activated.
 * @return array           returns a multi-dementional array containing plugin Name, php file path and directory path
 */
function smores_plugin_directory_scanner($dir , $activate = false){
    $pluginInfoArray = array();

    foreach (scandir($dir) as $directoryContent) {

        if(is_dir($dir . $directoryContent)){

            if($directoryContent == '.' || $directoryContent == '..'){
                //do nothing

            } else {

                foreach (scandir($dir . $directoryContent) as $file) {

                    if (substr($file, -4) == '.php'){

                        $pluginDirectoryPath =  $dir . $directoryContent;
                        $fullPluginPath = $dir . $directoryContent  .'/'.  $file;

                        $pluginInfo =  get_plugin_data($fullPluginPath);

                        if(strlen($pluginInfo['Name']) > 0 ){
                            $pluginInfoArray[] = array('Name' => $pluginInfo['Name'] , 'FilePath' => $fullPluginPath , 'DirectoryPath' => $pluginDirectoryPath);
                            //echo $pluginInfoArray['Name'] . '<- Name<br>';
                            if($activate == true){
                                activate_plugin($fullPluginPath);
                            }
                        }

                    }

                    unset($pluginInfo);
                    unset($newActivePlugin);
                }
            }
        }
    }

    return $pluginInfoArray;

}


/**
 * Recursivly copies directories and contents to a new location.
 * @param  string  $src   Path to the source directory.
 * @param  string  $dst   Path to the destination directory.
 * @param  integer $depth A safty to avoid infinte loops.
 * @return bool true;
 */
function smores_recursive_copy( $src , $dst, $depth = 1000 ){
    global $loopStop;
    $loopStop++;
    if($loopStop > $depth){
        return;
    } else {
        $files = glob($src . "/*");
        foreach($files as $file){
            if($file != '.' || $file != '..'){
                if(is_dir($file)){
                    $fileName = explode('/' , $file);
                    $newFile = array_pop($fileName);
                    $newPath = $dst . '/' . $newFile;
                    if(!file_exists($newPath)){
                        mkdir($dst . '/' . $newFile, 0775 );
                    }

                    $newSrc = $src . '/' . $newFile;
                    $newDst = $dst . '/' . $newFile;
                    smores_recursive_copy($newSrc, $newDst);
                } else {
                    $fileName = explode('/' , $file);
                    $newFile = array_pop($fileName);
                    copy($file , $dst . '/' . $newFile);
                }
            }
        }
    }

    return true;

}

function smores_clean_tmp_directory($zippedThemePlugins){

    foreach (scandir( $zippedThemePlugins ) as $file){
        if ($file == '.' || $file == '..'){
            //do nothing
        } else {
            if (is_dir( $zippedThemePlugins . '/' . $file )){
                $newDirectoryPath = $zippedThemePlugins . '/' .$file;
                smores_clean_tmp_directory( $newDirectoryPath );
            } else {
                unlink( $zippedThemePlugins . '/' . $file );
            }
        }
    }

    rmdir( $zippedThemePlugins );

}

/**
*	END Smores Included Plugin Installs
*   ACF Pro
*   ACF Font Awesome
*   ACF CF7
*   Contact Form 7
*   EWWW Image Optimizer
*   Regenerate Thumbnails
*   WP Security Audit Log
**/



/** Smores Admin Theme Function **/

function add_favicon() {
      $favicon_url = get_template_directory_uri() . '/admin/img/favicon.ico';
    echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}

// Now, just make sure that function runs when you're on the login page and admin pages
add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');


function fiwi_admin_styles() {
wp_enqueue_style( 'admin', get_template_directory_uri() . '/admin/css/styles.min.css', false, 'all' );
wp_enqueue_script( 'admin', get_template_directory_uri() . '/admin/js/scripts.js', array( 'jquery' ), 'all' );
}

add_action( 'admin_enqueue_scripts', 'fiwi_admin_styles' );
add_action( 'login_enqueue_scripts', 'fiwi_admin_styles' );

//$counter = 0;
//function fiwi_admin_fonts() {
//
//if( have_rows('font','options') ):
//while ( have_rows('font','options') ) : the_row();
//
//wp_enqueue_style( 'font', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700', false, 'all' );
//
//endwhile; endif;
//
//}
//
//add_action( 'admin_enqueue_scripts', 'fiwi_admin_fonts' );
//add_action( 'login_enqueue_scripts', 'fiwi_admin_fonts' );

function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_welcome', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}
add_action( 'admin_init', 'remove_dashboard_meta' );

add_action('init', 'my_init_function');
function my_init_function() {
    if (function_exists('acf_add_options_page')) {
        $page = acf_add_options_page(array(
            'menu_title' => 'Admin Settings',
            'menu_slug' => 'admin-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        ));
    }

}
add_action('init', 'acf_fields_admin');
function acf_fields_admin() {

}


add_action( 'admin_head', 'client_logos' );
add_action( 'login_head', 'client_logos' );
function client_logos() {
//    include( 'admin/css/style.php' );
}

add_filter('get_user_option_admin_color', 'change_admin_color');
function change_admin_color($result) {
//return 'midnight';
}

function add_display_options_widget() {

    wp_add_dashboard_widget(
                 'display_options_widget',         // Widget slug.
                 'Display Options',         // Title.
                 'display_options_widget_function' // Display function.
        );
}
add_action( 'wp_dashboard_setup', 'add_display_options_widget' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function display_options_widget_function() {?>
<div class="iframe-container"><iframe id="display-options" style="width:100%; min-height: 50vh" src="/wp-admin/admin.php?page=acf-options-display-options"></iframe></div>

<?php }


function add_display_clients_widget() {

    wp_add_dashboard_widget(
                 'display_clients_widget',         // Widget slug.
                 'Clients',         // Title.
                 'display_clients_widget_function' // Display function.
        );
}
add_action( 'wp_dashboard_setup', 'add_display_clients_widget' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function display_clients_widget_function() {?>

<div class="wrap">
    <h1 class="wp-heading-inline">Recently Added Clients</h1>

    <a href="/wp-admin/post-new.php?post_type=clients" class="page-title-action">Add New</a>
    <a href="/wp-admin/edit.php?post_type=clients" class="page-title-action">All Clients</a>
    <hr class="wp-header-end">
    <form id="posts-filter" method="get">
        <input type="hidden" name="post_status" class="post_status_page" value="all">
        <input type="hidden" name="post_type" class="post_type_page" value="clients">

        <input type="hidden" id="_wpnonce" name="_wpnonce" value="4c375f9c30">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/edit.php?post_type=clients">

        <h2 class="screen-reader-text">Posts list</h2>
        <table class="wp-list-table widefat fixed striped posts">


            <tbody id="the-list">

<?php $args = array(

    'posts_per_page' => 10,
    'orderby' => 'date' ,
    'order'   => 'DESC',
    'post_type' => 'clients',);

$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post(); ?>




                <tr id="post-80" class="iedit author-self level-0 post-80 type-clients status-publish hentry">

                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <strong><a class="row-title" href="/wp-admin/post.php?post=<?php echo get_the_ID();?>&amp;action=edit" aria-label="“<?php echo get_the_title();?>” (Edit)"><?php echo get_the_title();?></a></strong>

                    </td>
                </tr>


    <?php endwhile; wp_reset_postdata();?>

            </tbody>

            </tfoot>

        </table>

    </form>

    <div id="ajax-response"></div>
    <br class="clear">
</div>



<?php }


function frontheader() {?>
<script>
jQuery(document).ready(function($) {

if ( window.location !== window.parent.location ) {		  $('body').addClass('in-iframe');	$('html').addClass('in-iframe')	}
$(window).load(function(){


var iframeHeight = $('#display-options').outerHeight(),
containerHeight = $(window).outerHeight();
$('#display_options_widget .iframe-container').css('min-height', (containerHeight - iframeHeight + 400));

// jQuery(document).ready(function($) {
// if( acf.fields.color_picker ) {
// // custom colors
// var palette = [
//     "#eb3a24",
//     "#323430"];

// // when initially loaded find existing colorpickers and set the palette
// acf.add_action('load', function() {
// $('input.wp-color-picker').each(function() {
// $(this).iris('option', 'palettes', palette);
// });
// });

// // if appended element only modify the new element's palette
// acf.add_action('append', function(el) {
// $(el).find('input.wp-color-picker').iris('option', 'palettes', palette);
// });
// }
// });

 $(window).resize(function () {

var iframeHeight = $('#display-options').outerHeight(),
containerHeight = $(window).outerHeight();
$('#display_options_widget .iframe-container').css('min-height', (containerHeight - iframeHeight));

 });

});

});
</script>
<style>
.in-iframe #adminmenu,.in-iframe #adminmenuback,.in-iframe #screen-meta-links,.in-iframe #screen-meta-links .show-settings,.in-iframe #wpfooter,.in-iframe .admin-color-midnight h1,.in-iframe div#submitdiv button.handlediv.button-link,.in-iframe div#wpadminbar,.in-iframe h1,.in-iframe h2.hndle.ui-sortable-handle{display:none}.in-iframe.auto-fold #wpcontent,.in-iframe.auto-fold #wpfooter{margin:0!important}.in-iframe,.in-iframe #wpwrap{background:0 0!important}html.wp-toolbar.in-iframe{padding:0!important}.in-iframe #major-publishing-actions{background:0 0;border:none}.in-iframe .postbox .inside{border:none}.in-iframe div#submitdiv{border:none;margin:0;box-shadow:none}.in-iframe button.handlediv.button-link{display:none!important}.in-iframe #wpbody-content{padding-bottom:0}.in-iframe .wrap.acf-settings-wrap{margin:0}.in-iframe div#wpcontent{padding:0}.in-iframe div#poststuff{padding:0 5px}.in-iframe #post-body.columns-2 #postbox-container-1{float:none;width:100%;margin:0}.in-iframe #poststuff #post-body.columns-2 #side-sortables{width:100%;min-height:0}.in-iframe #poststuff #post-body.columns-2 {margin-right: 0;}
</style>


<?php }

add_action('admin_head', 'frontheader');


function fiwi_footer() {?>

<script>





</script>

<?php }

add_action('admin_footer', 'fiwi_footer');

class Replace_WP_Dashboard {
    protected $capability = 'read';
    protected $title;
    final public function __construct() {
        if( is_admin() ) {
            add_action( 'init', array( $this, 'init' ) );
        }
    }
    final public function init() {
        if( current_user_can( $this->capability ) ) {
            $this->set_title();
            add_filter( 'admin_title', array( $this, 'admin_title' ), 10, 2 );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'current_screen', array( $this, 'current_screen' ) );
        }
    }
    /**
     * Sets the page title for your custom dashboard
     */
    function set_title() {
        if( ! isset( $this->title ) ) {
            $this->title = __( 'Dashboard' );
        }
    }
    /**
     * Output the content for your custom dashboard
     */
    function page_content() {
        $content = __( 'Welcome to your new dashboard!' );
        echo <<<HTML
<div class="wrap">
    <h2>{$this->title}</h2>
    <p>{$content}</p>
</div>
HTML;
    }
    /**
     * Fixes the page title in the browser.
     *
     * @param string $admin_title
     * @param string $title
     * @return string $admin_title
     */
    final public function admin_title( $admin_title, $title ) {
        global $pagenow;
        if( 'admin.php' == $pagenow && isset( $_GET['page'] ) && 'custom-page' == $_GET['page'] ) {
            $admin_title = $this->title . $admin_title;
        }
        return $admin_title;
    }
    final public function admin_menu() {
        /**
         * Adds a custom page to WordPress
         */
        add_menu_page( $this->title, '', 'manage_options', 'custom-page', array( $this, 'page_content' ) );
        /**
         * Remove the custom page from the admin menu
         */
        remove_menu_page('custom-page');
        /**
         * Make dashboard menu item the active item
         */
        global $parent_file, $submenu_file;
        $parent_file = 'index.php';
        $submenu_file = 'index.php';
        /**
         * Rename the dashboard menu item
         */
        global $menu;
        $menu[2][0] = $this->title;
        /**
         * Rename the dashboard submenu item
         */
        global $submenu;
        $submenu['index.php'][0][0] = $this->title;
    }
    /**
     * Redirect users from the normal dashboard to your custom dashboard
     */
    final public function current_screen( $screen ) {
        if( 'dashboard' == $screen->id ) {
            wp_safe_redirect( admin_url('admin.php?page=acf-options-display-options') );
            exit;
        }
    }
}
new Replace_WP_Dashboard();
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
    'key' => 'group_58ab5b133d334',
    'title' => 'Client',
    'fields' => array (
        array (
            'key' => 'field_58ab5b17a392c',
            'label' => 'Client Logo',
            'name' => 'client_logo',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'id',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array (
            'key' => 'field_58ab5b46a392e',
            'label' => 'Slides',
            'name' => 'slides',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'min' => '',
            'max' => '',
            'button_label' => 'Add Slide',
            'layouts' => array (
                array (
                    'key' => '58ab5b56a392f',
                    'name' => 'website_screenshot',
                    'label' => 'Website Screenshot',
                    'display' => 'block',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_58ab5b67a3930',
                            'label' => 'Screenshot',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'id',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array (
                            'key' => 'field_58ac41a05dfc4',
                            'label' => 'Scroll Page?',
                            'name' => 'scroll_page',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'multiple' => 0,
                            'allow_null' => 1,
                            'choices' => array (
                                'Yes' => 'Yes',
                            ),
                            'default_value' => array (
                            ),
                            'ui' => 0,
                            'ajax' => 0,
                            'placeholder' => '',
                            'return_format' => 'value',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                array (
                    'key' => '58ab5b77a3931',
                    'name' => 'video',
                    'label' => 'Website Demo Reel',
                    'display' => 'block',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_58ab5b81a3932',
                            'label' => 'Video',
                            'name' => 'video',
                            'type' => 'file',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_size' => '',
                            'max_size' => '',
                            'mime_types' => '.mp4',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                array (
                    'key' => '58ab5b96a3933',
                    'name' => 'image',
                    'label' => 'Image',
                    'display' => 'block',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_58ab5b9aa3934',
                            'label' => 'Image/Creative Mockup',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'id',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
                array (
                    'key' => '58ab6259382e0',
                    'name' => 'client_video',
                    'label' => 'Client Video',
                    'display' => 'block',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_58ab625f382e1',
                            'label' => 'Video',
                            'name' => 'video',
                            'type' => 'file',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_size' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                        array (
                            'key' => 'field_58acb844147be',
                            'label' => 'Video Length',
                            'name' => 'video_length',
                            'type' => 'select',
                            'instructions' => 'Choose video length, default is 30 seconds',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array (
                                30000 => '30 Seconds',
                                60000 => '60 Seconds',
                                'Full Video' => 'Full Video',
                            ),
                            'default_value' => array (
                                0 => 30000,
                            ),
                            'allow_null' => 1,
                            'multiple' => 0,
                            'ui' => 0,
                            'ajax' => 0,
                            'return_format' => 'value',
                            'placeholder' => '',
                        ),
                        array (
                            'key' => 'field_592d7223450e6',
                            'label' => 'Contain Video?',
                            'name' => 'contain_video',
                            'type' => 'checkbox',
                            'instructions' => 'This will set video to native size (if video is other than 16:9)',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array (
                                'Yes' => 'Yes',
                            ),
                            'allow_custom' => 0,
                            'save_custom' => 0,
                            'default_value' => array (
                            ),
                            'layout' => 'vertical',
                            'toggle' => 0,
                            'return_format' => 'value',
                        ),
                        array (
                            'key' => 'field_592d725e450e7',
                            'label' => 'Video BG Color',
                            'name' => 'video_bg_color',
                            'type' => 'color_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array (
                                array (
                                    array (
                                        'field' => 'field_592d7223450e6',
                                        'operator' => '==',
                                        'value' => 'Yes',
                                    ),
                                ),
                            ),
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'clients',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array (
        0 => 'permalink',
        1 => 'the_content',
        2 => 'excerpt',
        3 => 'custom_fields',
        4 => 'discussion',
        5 => 'comments',
        6 => 'revisions',
        7 => 'slug',
        8 => 'author',
        9 => 'format',
        10 => 'page_attributes',
        11 => 'featured_image',
        12 => 'categories',
        13 => 'tags',
        14 => 'send-trackbacks',
    ),
    'active' => 1,
    'description' => '',
));


//acf_add_local_field_group(array (
//    'key' => 'group_58ab5c1ace6eb',
//    'title' => 'Display Options',
//    'fields' => array (
//        array (
//            'key' => 'field_58ac4eda3dbdd',
//            'label' => 'Welcome Options',
//            'name' => '',
//            'type' => 'tab',
//            'instructions' => '',
//            'required' => 0,
//            'conditional_logic' => 0,
//            'wrapper' => array (
//                'width' => '',
//                'class' => '',
//                'id' => '',
//            ),
//            'placement' => 'top',
//            'endpoint' => 0,
//        ),
//        array (
//            'key' => 'field_58b06e0a00feb',
//            'label' => 'Override Slide Mode?',
//            'name' => 'override_slide_mode',
//            'type' => 'select',
//            'instructions' => 'Select this to force "Welcome Mode"',
//            'required' => 0,
//            'conditional_logic' => 0,
//            'wrapper' => array (
//                'width' => '',
//                'class' => '',
//                'id' => '',
//            ),
//            'choices' => array (
//                'Yes' => 'Yes',
//                'No' => 'No',
//            ),
//            'default_value' => array (
//                0 => 'No',
//            ),
//            'allow_null' => 0,
//            'multiple' => 0,
//            'ui' => 0,
//            'ajax' => 0,
//            'return_format' => 'value',
//            'placeholder' => '',
//        ),
//        array (
//            'key' => 'field_58ab5c38a6df8',
//            'label' => 'Display Mode',
//            'name' => 'welcome_mode',
//            'type' => 'select',
//            'instructions' => '',
//            'required' => 0,
//            'conditional_logic' => 0,
//            'wrapper' => array (
//                'width' => '50',
//                'class' => 'hidden',
//                'id' => '',
//            ),
//            'choices' => array (
//                'Welcome' => 'Welcome',
//                'Slide' => 'Slide',
//            ),
//            'default_value' => array (
//                0 => 'Slide',
//            ),
//            'allow_null' => 1,
//            'multiple' => 0,
//            'ui' => 0,
//            'ajax' => 0,
//            'return_format' => 'value',
//            'placeholder' => '',
//        ),
//        array (
//            'key' => 'field_58ac7462beeaf',
//            'label' => 'Client Schedule',
//            'name' => 'client_schedule',
//            'type' => 'repeater',
//            'instructions' => '',
//            'required' => 0,
//            'conditional_logic' => 0,
//            'wrapper' => array (
//                'width' => '',
//                'class' => '',
//                'id' => '',
//            ),
//            'collapsed' => 'field_58ac746ebeeb0',
//            'min' => 0,
//            'max' => 0,
//            'layout' => 'block',
//            'button_label' => 'Add Client Meeting',
//            'sub_fields' => array (
//                array (
//                    'key' => 'field_58b05f686f3d4',
//                    'label' => 'Client Options',
//                    'name' => '',
//                    'type' => 'tab',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'placement' => 'top',
//                    'endpoint' => 0,
//                ),
//                array (
//                    'key' => 'field_58ac74a4beeb3',
//                    'label' => 'Client Meeting Time',
//                    'name' => 'client_time',
//                    'type' => 'time_picker',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'display_format' => 'g:i a',
//                    'return_format' => 'H:i:s',
//                ),
//                array (
//                    'key' => 'field_58ac7477beeb1',
//                    'label' => 'Welcome Message',
//                    'name' => 'welcome_message',
//                    'type' => 'text',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'default_value' => 'Welcome',
//                    'placeholder' => '',
//                    'prepend' => '',
//                    'append' => '',
//                    'maxlength' => '',
//                ),
//                array (
//                    'key' => 'field_58ac746ebeeb0',
//                    'label' => 'Client Name',
//                    'name' => 'client_name',
//                    'type' => 'text',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'default_value' => '',
//                    'placeholder' => '',
//                    'prepend' => '',
//                    'append' => '',
//                    'maxlength' => '',
//                ),
//                array (
//                    'key' => 'field_58ac746ebeeb0',
//                    'label' => 'Client Name',
//                    'name' => 'client_name',
//                    'type' => 'text',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'default_value' => '',
//                    'placeholder' => '',
//                    'prepend' => '',
//                    'append' => '',
//                    'maxlength' => '',
//                ),
//                array (
//                    'key' => 'field_58b05fab6f3d5',
//                    'label' => 'Client Video/Image Options',
//                    'name' => '',
//                    'type' => 'tab',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'placement' => 'top',
//                    'endpoint' => 0,
//                ),
//                array (
//                    'key' => 'field_58b055b9bada7',
//                    'label' => 'Video or Image',
//                    'name' => 'welcome_type',
//                    'type' => 'radio',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '50',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'choices' => array (
//                        'Video' => 'Video',
//                        'Image' => 'Image',
//                    ),
//                    'allow_null' => 1,
//                    'other_choice' => 0,
//                    'save_other_choice' => 0,
//                    'default_value' => 'Video',
//                    'layout' => 'horizontal',
//                    'return_format' => 'value',
//                ),
//                array (
//                    'key' => 'field_58ac748abeeb2',
//                    'label' => 'Client Video',
//                    'name' => 'client_video',
//                    'type' => 'file',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => array (
//                        array (
//                            array (
//                                'field' => 'field_58b055b9bada7',
//                                'operator' => '==',
//                                'value' => 'Video',
//                            ),
//                        ),
//                    ),
//                    'wrapper' => array (
//                        'width' => '50',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'return_format' => 'url',
//                    'library' => 'all',
//                    'min_size' => '',
//                    'max_size' => '',
//                    'mime_types' => '',
//                ),
//                array (
//                    'key' => 'field_58b055e9bada8',
//                    'label' => 'Client Image',
//                    'name' => 'client_image',
//                    'type' => 'image',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => array (
//                        array (
//                            array (
//                                'field' => 'field_58b055b9bada7',
//                                'operator' => '==',
//                                'value' => 'Image',
//                            ),
//                        ),
//                    ),
//                    'wrapper' => array (
//                        'width' => '50',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'return_format' => 'id',
//                    'preview_size' => 'thumbnail',
//                    'library' => 'all',
//                    'min_width' => '',
//                    'min_height' => '',
//                    'min_size' => '',
//                    'max_width' => '',
//                    'max_height' => '',
//                    'max_size' => '',
//                    'mime_types' => '',
//                ),
//                array (
//                    'key' => 'field_58b05dbfdb03d',
//                    'label' => 'Background Opacity/Color',
//                    'name' => '',
//                    'type' => 'tab',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'placement' => 'top',
//                    'endpoint' => 0,
//                ),
//                array (
//                    'key' => 'field_58b05dd1db03e',
//                    'label' => 'Background Color',
//                    'name' => 'bg_color',
//                    'type' => 'color_picker',
//                    'instructions' => '',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '50',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'default_value' => '',
//                ),
//                array (
//                    'key' => 'field_58b05de1db03f',
//                    'label' => 'Background Opacity',
//                    'name' => 'background_opacity',
//                    'type' => 'text',
//                    'instructions' => 'Input 1 to 0. eg: 1 equals 100%, .9 equals 90% and so forth',
//                    'required' => 0,
//                    'conditional_logic' => 0,
//                    'wrapper' => array (
//                        'width' => '50',
//                        'class' => '',
//                        'id' => '',
//                    ),
//                    'default_value' => '.8',
//                    'placeholder' => '',
//                    'prepend' => '',
//                    'append' => '',
//                    'maxlength' => '',
//                ),
//            ),
//        ),
//        array (
//            'key' => 'field_58ac4eea3dbde',
//            'label' => 'Slide Options',
//            'name' => '',
//            'type' => 'tab',
//            'instructions' => '',
//            'required' => 0,
//            'conditional_logic' => 0,
//            'wrapper' => array (
//                'width' => '',
//                'class' => '',
//                'id' => '',
//            ),
//            'placement' => 'top',
//            'endpoint' => 0,
//        ),
//        array (
//            'key' => 'field_58ab5c29a6df7',
//            'label' => 'Slide Order',
//            'name' => 'slide_order',
//            'type' => 'post_object',
//            'instructions' => '',
//            'required' => 0,
//            'conditional_logic' => 0,
//            'wrapper' => array (
//                'width' => '',
//                'class' => '',
//                'id' => '',
//            ),
//            'post_type' => array (
//                0 => 'clients',
//            ),
//            'taxonomy' => array (
//            ),
//            'allow_null' => 0,
//            'multiple' => 1,
//            'return_format' => 'object',
//            'ui' => 1,
//        ),
//    ),
//    'location' => array (
//        array (
//            array (
//                'param' => 'options_page',
//                'operator' => '==',
//                'value' => 'acf-options-display-options',
//            ),
//        ),
//    ),
//    'menu_order' => 0,
//    'position' => 'normal',
//    'style' => 'default',
//    'label_placement' => 'top',
//    'instruction_placement' => 'label',
//    'hide_on_screen' => '',
//    'active' => 1,
//    'description' => '',
//)
//                         );

endif;

function fiwi_login_styles() {?>

<style>
         .login h1 a {
                background: url(/wp-content/themes/smores-core/admin/img/fiwi-login.svg) no-repeat bottom center !important;
                margin-bottom: 10px;
                background-size: auto 100% !Important;
                width: 100%;
            }
            .login h1:after {
                content: '';
                display: block;
                width: 100%;
                height: 30px;
                background-image: url(/wp-content/themes/smores-core/admin/img/FIWI-classic-website.svg);
                background-size: 100% auto;
                background-position: 50% 100%;
                background-repeat: no-repeat;
            }

                .login:not(.fiwi-redirected-user) {

            background: white;
        }
    .login:not(.fiwi-redirected-user) form#loginform {
        background: none;
        box-shadow: none;
        margin-top: 0;

    }

    .login:not(.fiwi-redirected-user) input[type=text],
    .login:not(.fiwi-redirected-user) input[type=email],
    .login:not(.fiwi-redirected-user) input#user_pass {
        padding: 10px;
        background: none;
        width: 100%;
        border: 1px solid grey;
        font-family: Baskerville;
        font-style: italic;
        font-size: 22px;
        line-height: 1.5;
        color: grey;
        margin: 0;
        text-align: center;
    }

    .login:not(.fiwi-redirected-user) input[type=submit] {
    background: #d6372b;
    color: white;
        font-family: 'League Gothic', 'Arial Narrow', sans-serif;
        font-size: 2em !important;
        letter-spacing: .1em;
        padding: 0.156em 1.875em !important;
        text-align: center;
        text-transform: uppercase;
        transition: all .3s;
        border: 1px solid #d6372b;
        height: auto !important;
        display: block !important;
        width: 100%;
        margin: 10px 0;
        text-shadow: none;
        border-radius: 0 !important;
        box-shadow: none;
        line-height: 1.5 !important;
        font-weight: normal !important;
    }

        .login:not(.fiwi-redirected-user) form .forgetmenot label {
        color: grey;
        text-align: center;
        font-size: 1.5em;
        font-family: Baskerville;
        font-style: italic;
    }

    .login:not(.fiwi-redirected-user) form .forgetmenot {
        font-weight: normal;
        float: left;
        margin-bottom: 0;
        padding: 10px 80px 0;
    }

    .login:not(.fiwi-redirected-user) input#rememberme {
        /* display: block; */
        margin: 0 auto;
        -webkit-appearance: none;
        border-radius: 100px;
        float: none;
        margin: 11px auto 11px;
        top: -3px;
        position: relative;
    }

    .login:not(.fiwi-redirected-user) p.forgetmenot {
        display: block;
        width: 100%;
    }

        .login:not(.fiwi-redirected-user) #backtoblog a,
    .login:not(.fiwi-redirected-user) #nav a {
        color: grey;
        font-family: Baskerville;
        font-style: italic;
    }

.login:not(.fiwi-redirected-user) input[type=submit]:hover {
    background: white;
    color: #d6372b;
    box-shadow: none !important;
    border-color: white;
}


        .login #login_error, .login .message {

            margin: 1rem 0 0;
        }


        .login.wp-core-ui .button, .login.wp-core-ui .button.button-large {

            font-size: 2em !important;
        }

            .login label {
        font-size: 0;
    }
</style>
    <script>
        jQuery('document').ready(function(){
            jQuery('body').addClass('fiwi-login');
            jQuery('#user_login').attr( 'placeholder', 'Username' );
            jQuery('#user_pass').attr( 'placeholder', 'Password' );
        });
</script>
<?php }
add_action( 'login_head', 'fiwi_login_styles' );
