<?php
/**
* Plugin Name: WEI Warning Banner
* Description: This plugin displays a warning banner when the website detects user browser is using Web Environment Integrity (WEI).
* Version: 1.0
* Author: groundcat
*/

// Exit if accessed directly
if(!defined('ABSPATH')) exit;

class WEI_Warning_Banner {
    // Constructor
    public function __construct() {
        add_action('admin_menu', array($this, 'wei_warning_banner_menu'));
        add_action('admin_init', array($this, 'wei_warning_banner_settings'));
        add_action('wp_footer', array($this, 'display_wei_warning_banner'));
    }

    // Adds menu to settings in admin dashboard
    public function wei_warning_banner_menu() {
        add_options_page(
            'WEI Warning Banner Settings',
            'WEI Warning Banner',
            'manage_options',
            'wei-warning-banner',
            array($this, 'wei_warning_banner_settings_page')
        );
    }

    // Registers settings for the warning banner
    public function wei_warning_banner_settings() {
        register_setting('wei_warning_banner_settings', 'wei_warning_banner_enabled');
    }

    // Creates settings page in admin dashboard
    public function wei_warning_banner_settings_page() { ?>
        <div class="wrap">
            <h1>WEI Warning Banner Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('wei_warning_banner_settings'); ?>
                <?php do_settings_sections('wei_warning_banner_settings'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable WEI Warning Banner</th>
                        <td><input type="checkbox" name="wei_warning_banner_enabled" value="1" <?php checked(1, get_option('wei_warning_banner_enabled'), true); ?> /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
   <?php }

   // Displays warning banner on front-end of site if enabled in settings
   public function display_wei_warning_banner() {
       if(get_option('wei_warning_banner_enabled')) { 
           ?>
                <style>
                    #wei-warning {
                        display: none;
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        width: 100%;
                        background-color: #ffcc00;
                        color: black;
                        text-align: left;
                        padding: 20px;
                        font-size: 16px;
                        z-index: 9999;
                        word-wrap: break-word;
                        box-sizing: border-box;
                    }

                    #wei-warning a {
                        color: blue;
                    }

                    #wei-warning .more-info {
                        display: none;
                    }

                    #wei-warning button {
                        margin-top: 10px;
                    }

                    #wei-warning .button {
                        background-color: white;
                        border: none;
                        color: black;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        margin-top: 10px;
                        cursor: pointer;
                    }
                </style>

                <div id="wei-warning">
                    <h2>Your browser is not privacy-respecting</h2>
                    <p>You're currently using a Google Chrome or Edge browser, which could be vulnerable to mass surveillance and
                    potential privacy breaches due to its recent Web Environment Integrity (WEI) proposal. This technology could
                    potentially allow third parties to access certain information about your software and hardware stack which could
                    be used in ways that infringe upon your privacy. We understand the importance of confidentiality and privacy. 
                    Therefore, we recommend switching to a privacy-respecting browser such as <a
                        href="https://www.brave.com">Brave</a> or <a href="https://www.mozilla.org/en-US/firefox/new/">Firefox</a>.
                    These browsers are known for their strong commitment to user privacy and do not support the controversial WEI
                    technology.</p>

                <button class="button" onclick="window.open('https://www.fsf.org/blogs/community/web-environment-integrity-is-an-all-out-attack-on-the-free-internet', '_blank')">Learn More</button>

                <button class="button" id="hide-button">Hide</button>
                </div>

                <script>
                window.onload = function() {
                    if(localStorage.getItem('hideWeiWarning') !== 'true' && navigator.getEnvironmentIntegrity !== undefined) {
                        document.getElementById('wei-warning').style.display = 'block';
                    }
                }

                document.getElementById('hide-button').addEventListener('click', function() {
                    document.getElementById('wei-warning').style.display = 'none';
                    localStorage.setItem('hideWeiWarning', 'true');
                });
                </script>

            <?php
       }
   }
}

// Initialize plugin
if(class_exists('WEI_Warning_Banner')) {
    $WEI_Warning_Banner = new WEI_Warning_Banner();
}
?>
