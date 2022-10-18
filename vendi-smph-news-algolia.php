<?php
/**
 * Plugin Name: Vendi SMPH News - Algolia
 * Description: Algolia search integration
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * Version: 0.0.1
 * Author: Vendi Advertising (Chris Haas)
 * Author URI: https://vendiadvertising.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Setup some plugin-level constants to make life easier elsewhere
define('VENDI_SMPH_NEWS_ALGOLIA_FILE', __FILE__);
define('VENDI_SMPH_NEWS_ALGOLIA_PATH', __DIR__);
define('VENDI_SMPH_NEWS_ALGOLIA_URL', get_bloginfo('template_directory'));

require_once VENDI_SMPH_NEWS_ALGOLIA_PATH.'/includes/autoload.php';
require_once VENDI_SMPH_NEWS_ALGOLIA_PATH.'/includes/hooks/hooks-wordpress.php';

set_error_handler(
    static function ($errorNumber, $errorMessage, $errorFile, $errorLine) {
        $knownErrors = [
            'rtrim(): Passing null to parameter',
            'strlen(): Passing null to parameter #1',
            'Return type of Requests_Cookie_Jar',
            'Return type of Requests_Utility_CaseInsensitiveDictionary',
        ];

        foreach ($knownErrors as $knownError) {
            if (str_contains($errorMessage, $knownError)) {
                return;
            }
        }

//        debug_print_backtrace();

        throw new ErrorException($errorMessage, 0, $errorNumber, $errorFile, $errorLine);
    }
);