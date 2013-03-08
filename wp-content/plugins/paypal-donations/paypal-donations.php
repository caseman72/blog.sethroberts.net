<?php
/*
Plugin Name: PayPal Donations
Plugin URI: http://wpstorm.net/wordpress-plugins/paypal-donations/
Description: Easy and simple setup and insertion of PayPal donate buttons with a shortcode or through a sidebar Widget. Donation purpose can be set for each button. A few other customization options are available as well.
Author: Johan Steen
Author URI: http://johansteen.se/
Version: 1.7
License: GPLv2 or later
Text Domain: paypal-donations 

Copyright 2009-2013  Johan Steen  (email : artstorm [at] gmail [dot] com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/** Load all of the necessary class files for the plugin */
spl_autoload_register('PayPalDonations::autoload');

/**
 * Init Singleton Class for PayPal Donations.
 *
 * @package PayPal Donations
 * @author  Johan Steen <artstorm at gmail dot com>
 */
class PayPalDonations
{
    private static $instance = false;

    const MIN_PHP_VERSION  = '5.2.4';
    const MIN_WP_VERSION   = '2.8';
    const OPTION_DB_KEY    = 'paypal_donations_options';


    // -------------------------------------------------------------------------
    // Define constant variables and data arrays
    // -------------------------------------------------------------------------
    private $donate_buttons = array(
        'small' => 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif',
        'large' => 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif',
        'cards' => 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif'
    );
    private $currency_codes = array(
        'AUD' => 'Australian Dollars (A $)',
        'CAD' => 'Canadian Dollars (C $)',
        'EUR' => 'Euros (&euro;)',
        'GBP' => 'Pounds Sterling (&pound;)',
        'JPY' => 'Yen (&yen;)',
        'USD' => 'U.S. Dollars ($)',
        'NZD' => 'New Zealand Dollar ($)',
        'CHF' => 'Swiss Franc',
        'HKD' => 'Hong Kong Dollar ($)',
        'SGD' => 'Singapore Dollar ($)',
        'SEK' => 'Swedish Krona',
        'DKK' => 'Danish Krone',
        'PLN' => 'Polish Zloty',
        'NOK' => 'Norwegian Krone',
        'HUF' => 'Hungarian Forint',
        'CZK' => 'Czech Koruna',
        'ILS' => 'Israeli Shekel',
        'MXN' => 'Mexican Peso',
        'BRL' => 'Brazilian Real',
        'TWD' => 'Taiwan New Dollar',
        'PHP' => 'Philippine Peso',
        'TRY' => 'Turkish Lira',
        'THB' => 'Thai Baht'
    );
    private $localized_buttons = array(
        'en_AU' => 'Australia - Australian English',
        'de_DE/AT' => 'Austria - German',
        'nl_NL/BE' => 'Belgium - Dutch',
        'fr_XC' => 'Canada - French',
        'zh_XC' => 'China - Simplified Chinese',
        'fr_FR/FR' => 'France - French',
        'de_DE/DE' => 'Germany - German',
        'it_IT/IT' => 'Italy - Italian',
        'ja_JP/JP' => 'Japan - Japanese',
        'es_XC' => 'Mexico - Spanish',
        'nl_NL/NL' => 'Netherlands - Dutch',
        'pl_PL/PL' => 'Poland - Polish',
        'es_ES/ES' => 'Spain - Spanish',
        'de_DE/CH' => 'Switzerland - German',
        'fr_FR/CH' => 'Switzerland - French',
        'en_US' => 'United States - U.S. English'
    );
    private $checkout_languages = array(
        'AU' => 'Australia',
        'AT' => 'Austria',
        'BR' => 'Brazil',
        'CA' => 'Canada',
        'CN' => 'China',
        'FR' => 'France',
        'DE' => 'Germany',
        'IT' => 'Italy',
        'NL' => 'Netherlands',
        'ES' => 'Spain',
        'SE' => 'Sweden',
        'GB' => 'United Kingdom',
        'US' => 'United States',
    );

    /**
     * Singleton class
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     * Initializes the plugin by setting localization, filters, and
     * administration functions.
     */
    private function __construct()
    {
        if (!$this->testHost()) {
            return;
        }

        // Load plugin text domain
        add_action('init', array($this, 'pluginTextdomain'));

        register_uninstall_hook(__FILE__, array(__CLASS__, 'uninstall'));

        add_action('admin_menu', array(&$this,'wpAdmin'));
        add_shortcode('paypal-donation', array(&$this,'paypalShortcode'));
        add_action('wp_head', array($this, 'addCss'), 999);

        add_action('widgets_init', 
            create_function('', 'register_widget("PayPalDonations_Widget");'));
    }

    /**
     * PSR-0 compliant autoloader to load classes as needed.
     *
     * @since 1.7
     * @param  string  $classname  The name of the class
     * @return null    Return early if the class name does not start with the
     *                 correct prefix
     */
    public static function autoload($className)
    {
        if ('PayPalDonations' !== mb_substr($className, 0, 15)) {
            return;
        }
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
            $fileName .= DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, 'lib_'.$className);
        $fileName .='.php';

        require $fileName;
    }

    /**
     * Loads the plugin text domain for translation
     */
    public function pluginTextdomain()
    {
        $domain = 'paypal-donations';
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
        load_textdomain(
            $domain,
            WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo'
        );
        load_plugin_textdomain(
            $domain,
            false,
            dirname(plugin_basename(__FILE__)).'/lang/'
        );
    }

    /**
     * Fired when the plugin is uninstalled.
     */
    public function uninstall()
    {
        delete_option('paypal_donations_options');
        delete_option('widget_paypal_donations');
    }

    /**
     * Adds inline CSS code to the head section of the html pages to center the
     * PayPal button.
     */
    public function addCss()
    {
        $opts = get_option(self::OPTION_DB_KEY);
        if (isset($opts['center_button']) and $opts['center_button'] == true) {
            echo '<style type="text/css">'."\n";
            echo '.paypal-donations { text-align: center !important }'."\n";
            echo '</style>'."\n";
        }
    }

    /**
     * Create and register the PayPal shortcode
     */
    public function paypalShortcode($atts)
    {
        extract(shortcode_atts(array(
            'purpose' => '',
            'reference' => '',
            'amount' => '',
            'return_page' => '',
            'button_url' => '',
        ), $atts));

        return $this->generateHtml(
            $purpose,
            $reference,
            $amount,
            $return_page,
            $button_url
        );
    }
    
    /**
     * Generate the PayPal button HTML code
     */
    public function generateHtml(
        $purpose = null,
        $reference = null,
        $amount = null,
        $return_page = null,
        $button_url = null
    ) {
        $pd_options = get_option(self::OPTION_DB_KEY);

        // Set overrides for purpose and reference if defined
        $purpose = (!$purpose) ? $pd_options['purpose'] : $purpose;
        $reference = (!$reference) ? $pd_options['reference'] : $reference;
        $amount = (!$amount) ? $pd_options['amount'] : $amount;
        $return_page = (!$return_page) ? $pd_options['return_page'] : $return_page;
        $button_url = (!$button_url) ? $pd_options['button_url'] : $button_url;
        
        $data = array(
            'pd_options' => $pd_options,
            'return_page' => $return_page,
            'purpose' => $purpose,
            'reference' => $reference,
            'amount' => $amount,
            'button_url' => $button_url,
            'donate_buttons' => $this->donate_buttons,
        );

        return PayPalDonations_View::render(
            plugin_dir_path(__FILE__).'views/paypal-button.php',
            $data
        );
    }

    /**
     * The Admin Page and all it's functions
     */
    public function wpAdmin()
    {
        if (function_exists('add_options_page'))
            add_options_page(
                'PayPal Donations Options',
                'PayPal Donations',
                'administrator',
                basename(__FILE__),
                array(&$this, 'optionsPage')
            );
    }

    public function adminMessage($message)
    {
        if ($message) {
            ?>
            <div class="updated"><p><strong>
                <?php echo $message; ?>
            </strong></p></div>
            <?php   
        }
    }

    public function optionsPage()
    {
        // Update Options
        if (isset($_POST['Submit'])) {
            $pd_options['paypal_account'] = trim( $_POST['paypal_account'] );
            $pd_options['page_style'] = trim( $_POST['page_style'] );
            $pd_options['return_page'] = trim( $_POST['return_page'] );
            $pd_options['purpose'] = trim( $_POST['purpose'] );
            $pd_options['reference'] = trim( $_POST['reference'] );
            $pd_options['button'] = trim( $_POST['button'] );
            $pd_options['button_url'] = trim( $_POST['button_url'] );
            $pd_options['currency_code'] = trim( $_POST['currency_code'] );
            $pd_options['amount'] = trim( $_POST['amount'] );
            $pd_options['button_localized'] = trim( $_POST['button_localized'] );
            $pd_options['disable_stats'] = isset($_POST['disable_stats']) ? true : false;
            $pd_options['center_button'] = isset($_POST['center_button']) ? true : false;
            $pd_options['set_checkout_language'] = isset($_POST['set_checkout_language']) ? true : false;
            $pd_options['checkout_language'] = trim( $_POST['checkout_language'] );
            update_option(self::OPTION_DB_KEY, $pd_options);
            $this->adminMessage( __( 'The PayPal Donations settings have been updated.', 'paypal-donations' ) );
        }

        // Render the settings screen
        $settings = new PayPalDonations_Admin();
        $settings->setOptions(
            get_option(self::OPTION_DB_KEY),
            $this->currency_codes,
            $this->donate_buttons,
            $this->localized_buttons,
            $this->checkout_languages
        );
        $settings->render();
    }

    // -------------------------------------------------------------------------
    // Environment Checks
    // -------------------------------------------------------------------------

    /**
     * Constructor.
     *
     * Checks PHP and WordPress versions. If any check failes, a system notice
     * is added and $passed is set to fail, which can be checked before trying
     * to create the main class.
     */
    private function testHost()
    {
        // Check if PHP is too old
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            // Display notice
            add_action( 'admin_notices', array(&$this, 'phpVersionError') );
            return false;
        }

        // Check if WordPress is too old
        global $wp_version;
        if (version_compare($wp_version, self::MIN_WP_VERSION, '<')) {
            add_action( 'admin_notices', array(&$this, 'wpVersionError') );
            return false;
        }
        return true;
    }

    /**
     * Displays a warning when installed on an old PHP version.
     */
    public function phpVersionError()
    {
        echo '<div class="error"><p><strong>';
        printf(
            'Error: PayPal Donations requires PHP version %1$s or greater.<br/>'.
            'Your installed PHP version: %2$s',
            self::MIN_PHP_VERSION, PHP_VERSION);
        echo '</strong></p></div>';
    }

    /**
     * Displays a warning when installed in an old Wordpress version.
     */
    public function wpVersionError()
    {
        echo '<div class="error"><p><strong>';
        printf(
            'Error: PayPal Donations requires WordPress version %s or greater.',
            self::MIN_WP_VERSION );
        echo '</strong></p></div>';
    }
}

add_action('plugins_loaded', array('PayPalDonations', 'getInstance'));
