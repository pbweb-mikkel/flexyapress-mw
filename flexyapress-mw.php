<?php
define( 'FLEXYAPRESS_VERSION', '1.12' );
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://pbweb.dk
 * @since             1.1
 * @package           Flexyapress
 *
 * @wordpress-plugin
 * Plugin Name:       PB Web - Mindworking
 * Plugin URI:        https://pbweb.dk/flexyapress
 * Description:       Dette plugin sørger for dataintegration med Mindworking.
 * Version:           1.12
 * Author:            PB Web
 * Author URI:        https://pbweb.dk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flexyapress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */


if(!defined('FLEXYA_DEV')){
    define( 'FLEXYA_DEV', true);
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flexyapress-activator.php
 */
function activate_flexyapress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flexyapress-activator.php';
	Flexyapress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flexyapress-deactivator.php
 */
function deactivate_flexyapress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flexyapress-deactivator.php';
	Flexyapress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flexyapress' );
register_deactivation_hook( __FILE__, 'deactivate_flexyapress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flexyapress.php';

add_filter('pre_set_site_transient_update_plugins', 'pb_automatic_updates', 100, 1);
function pb_automatic_updates($data) {
    // Theme information
    $id   = 'flexyapress-mw/flexyapress-mw.php'; // Folder name of the current theme
    $slug   = 'flexyapress-mw'; // Folder name of the current theme
    $current = FLEXYAPRESS_VERSION; // Get the version of the current theme
    // GitHub information
    $user = 'pbweb-mikkel'; // The GitHub username hosting the repository
    $repo = 'flexyapress-mw'; // Repository name as it appears in the URL
    // Get the latest release tag from the repository. The User-Agent header must be sent, as per
    // GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
    /*$file = json_decode(file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
        stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])
    ));*/
    $response = wp_remote_get('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest',['headers' => "User-Agent: ".$user]);

    $file = false;
    if(!is_wp_error($response)){
        $file = json_decode($response['body']);
    }

    if($file) {
        $update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // Only return a response if the new version number is higher than the current version


        if($update > $current) {
            $item = (object) array(
                'new_version' => $update,
                'url'         => 'https://github.com/'.$user.'/'.$repo,
                'package'     => $file->assets[0]->browser_download_url,
                'id'            => $id,
                'slug'          => $slug,
                'plugin'        => $id,
            );
            $data->response[$id] = $item;

        }else{

            $item = (object) array(
                'new_version' => FLEXYAPRESS_VERSION,
                'url'         => '',
                'package'     => '',
                'id'            => $id,
                'slug'          => $slug,
                'plugin'        => $id,
                'icons'         => array(),
                'banners'       => array(),
                'banners_rtl'   => array(),
                'tested'        => '',
                'requires_php'  => '',
                'compatibility' => new stdClass(),
            );

            $data->no_update[$id] = $item;
        }
    }
    return $data;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flexyapress() {

	$plugin = new Flexyapress();
	$plugin->run();

}
run_flexyapress();
