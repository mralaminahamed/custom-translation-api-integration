<?php
/**
 * Plugin Name: Custom Translation API Integration
 * Plugin URI: https://github.com/mralaminahamed/custom-translation-api-integration
 * Description: Integrates with a custom translation API to provide translations for WordPress content, including themes, plugins, and core.
 * Version: 1.2.0
 * Author: Al Amin Ahamed
 * Author URI: https://alaminahamed.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: custom-translation-api-integration
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 *
 * @package CustomTranslationAPIIntegration
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Custom_Translation_API_Integration {
    private $api_url = 'https://api.example.com/translate'; // Replace with your actual API URL

    public function __construct() {
        add_filter('translations_api', array($this, 'custom_translations_api'), 10, 3);
        add_filter('translations_api_result', array($this, 'filter_translations_api_result'), 10, 3);
    }

    /**
     * Custom translations API handler
     *
     * @param false|array $result The result array. Default false.
     * @param string $type The type of translations being requested.
     * @param object $args Translation API arguments.
     * @return array|WP_Error
     */
    public function custom_translations_api($result, $type, $args) {
        if (false !== $result) {
            return $result;
        }

        if (!in_array($type, array('plugins', 'themes', 'core'), true)) {
            return new WP_Error('invalid_type', __('Invalid translation type.', 'custom-translation-api-integration'));
        }

        // Check if we already have a cached translation
        $cache_key = 'ctai_' . md5(serialize(array($type, $args)));
        $cached_translations = get_site_transient($cache_key);
        if (false !== $cached_translations) {
            return $cached_translations;
        }

        // If not cached, request translations from API
        $translations = $this->get_translations_from_api($type, $args);
        
        if (is_wp_error($translations)) {
            return $translations;
        }

        // Cache the translations for future use
        set_site_transient($cache_key, $translations, 3 * HOUR_IN_SECONDS);

        return $translations;
    }

    /**
     * Get translations from the custom API
     *
     * @param string $type The type of translations being requested.
     * @param object $args Translation API arguments.
     * @return array|WP_Error
     */
    private function get_translations_from_api($type, $args) {
        $body = array(
            'type' => $type,
            'wp_version' => $GLOBALS['wp_version'],
            'locale' => get_locale(),
            'version' => $args->version,
        );

        if ('core' !== $type) {
            $body['slug'] = $args->slug;
        }

        $response = wp_remote_post($this->api_url, array(
            'timeout' => 30,
            'body' => $body,
        ));

        if (is_wp_error($response)) {
            return new WP_Error(
                'translations_api_failed',
                sprintf(
                    /* translators: %s: Support forums URL. */
                    __('An unexpected error occurred. Something may be wrong with the translation API or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.', 'custom-translation-api-integration'),
                    __('https://wordpress.org/support/forums/', 'custom-translation-api-integration')
                ),
                $response->get_error_message()
            );
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);

        if (!is_array($result)) {
            return new WP_Error(
                'translations_api_failed',
                sprintf(
                    /* translators: %s: Support forums URL. */
                    __('An unexpected error occurred. Something may be wrong with the translation API or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.', 'custom-translation-api-integration'),
                    __('https://wordpress.org/support/forums/', 'custom-translation-api-integration')
                ),
                wp_remote_retrieve_body($response)
            );
        }

        return $result;
    }

    /**
     * Filter the Translation Installation API response results.
     *
     * @param array|WP_Error $result Response as an associative array or WP_Error.
     * @param string $type The type of translations being requested.
     * @param object $args Translation API arguments.
     * @return array|WP_Error
     */
    public function filter_translations_api_result($result, $type, $args) {
        // You can modify the result here if needed
        return $result;
    }
}

// Initialize the plugin
function custom_translation_api_integration_init() {
    new Custom_Translation_API_Integration();
}
add_action('plugins_loaded', 'custom_translation_api_integration_init');
