# Developer Guidelines for Custom Translation API Integration Plugin

## Overview

The Custom Translation API Integration plugin allows WordPress to use a custom translation API instead of the default WordPress.org translation API. This document provides guidelines and references for developers who want to understand, use, or extend the plugin.

## Hook References

### 1. translations_api

**WordPress Reference:** [translations_api](https://developer.wordpress.org/reference/hooks/translations_api/)

This filter is used to override the WordPress.org Translation Installation API.

**Usage in plugin:**
```php
add_filter('translations_api', array($this, 'custom_translations_api'), 10, 3);
```

**Parameters:**
- `$result` (false|array): The default result (false).
- `$type` (string): The type of translations being requested ('plugins', 'themes', or 'core').
- `$args` (object): Translation API arguments.

**Return:** (array|WP_Error) An associative array of translations or WP_Error on failure.

### 2. translations_api_result

**WordPress Reference:** [translations_api_result](https://developer.wordpress.org/reference/hooks/translations_api_result/)

This filter allows modification of the Translation Installation API response results.

**Usage in plugin:**
```php
add_filter('translations_api_result', array($this, 'filter_translations_api_result'), 10, 3);
```

**Parameters:**
- `$result` (array|WP_Error): Response as an associative array or WP_Error.
- `$type` (string): The type of translations being requested.
- `$args` (object): Translation API arguments.

**Return:** (array|WP_Error) Modified response.

### 3. plugins_loaded

**WordPress Reference:** [plugins_loaded](https://developer.wordpress.org/reference/hooks/plugins_loaded/)

This action hook is used to initialize the plugin.

**Usage in plugin:**
```php
add_action('plugins_loaded', 'custom_translation_api_integration_init');
```

## Function References

### 1. get_site_transient()

**WordPress Reference:** [get_site_transient()](https://developer.wordpress.org/reference/functions/get_site_transient/)

Used to retrieve cached translation data.

**Usage in plugin:**
```php
$cached_translations = get_site_transient($cache_key);
```

### 2. set_site_transient()

**WordPress Reference:** [set_site_transient()](https://developer.wordpress.org/reference/functions/set_site_transient/)

Used to cache translation data.

**Usage in plugin:**
```php
set_site_transient($cache_key, $translations, 3 * HOUR_IN_SECONDS);
```

### 3. wp_remote_post()

**WordPress Reference:** [wp_remote_post()](https://developer.wordpress.org/reference/functions/wp_remote_post/)

Used to send POST requests to the custom translation API.

**Usage in plugin:**
```php
$response = wp_remote_post($this->api_url, array(
    'timeout' => 30,
    'body' => $body,
));
```

### 4. is_wp_error()

**WordPress Reference:** [is_wp_error()](https://developer.wordpress.org/reference/functions/is_wp_error/)

Used to check if a value is a WordPress Error.

**Usage in plugin:**
```php
if (is_wp_error($response)) {
    // Handle error
}
```

### 5. wp_remote_retrieve_body()

**WordPress Reference:** [wp_remote_retrieve_body()](https://developer.wordpress.org/reference/functions/wp_remote_retrieve_body/)

Used to get the body from a WordPress HTTP response.

**Usage in plugin:**
```php
$body = wp_remote_retrieve_body($response);
```

## Extending the Plugin

To extend or modify the plugin's functionality, you can:

1. Use the `translations_api_result` filter to modify the API response.
2. Extend the `Custom_Translation_API_Integration` class to add or override methods.
3. Use the `plugins_loaded` action with a later priority to ensure your code runs after this plugin is loaded.

Example of extending the class:

```php
class Extended_Custom_Translation_API_Integration extends Custom_Translation_API_Integration {
    public function __construct() {
        parent::__construct();
        // Add your custom initialization here
    }

    // Override or add methods as needed
}

function extended_custom_translation_api_integration_init() {
    new Extended_Custom_Translation_API_Integration();
}
add_action('plugins_loaded', 'extended_custom_translation_api_integration_init', 20);
```

## API Integration

When integrating with your custom translation API, ensure that your API:

1. Accepts POST requests with the following parameters:
   - `type`: The type of translations ('plugins', 'themes', or 'core')
   - `wp_version`: The current WordPress version
   - `locale`: The current locale
   - `version`: The version of the plugin, theme, or core
   - `slug`: The slug of the plugin or theme (not included for core translations)

2. Returns a JSON response with the following structure:
   ```json
   {
     "translations": [
       {
         "language": "fr_FR",
         "version": "4.0",
         "updated": "2019-06-01 00:00:00",
         "package": "https://downloads.wordpress.org/translation/core/4.0/fr_FR.zip",
         "autoupdate": "1"
       }
     ]
   }
   ```

## Error Handling

The plugin uses `WP_Error` for error handling. When extending the plugin, make sure to:

1. Use `WP_Error` for consistency when returning errors.
2. Provide meaningful error messages that can help in troubleshooting.
3. Use the plugin's text domain ('custom-translation-api-integration') for error message translations.

## Caching

The plugin caches API responses using WordPress transients. The default cache time is 3 hours. You can modify this by changing the value in the `set_site_transient()` call:

```php
set_site_transient($cache_key, $translations, YOUR_CUSTOM_TIME_IN_SECONDS);
```

## Security Considerations

1. Always validate and sanitize data received from your custom API.
2. Use nonces and capability checks if adding any admin-side functionality.
3. Follow WordPress coding standards and security best practices.

## Localization

The plugin is translation-ready. To add translations:

1. Use the `__()` function for translatable strings.
2. Use the text domain 'custom-translation-api-integration' for all translations.
3. Create .po and .mo files in the /languages directory.

## Support and Contributions

For support or to contribute to this plugin, please visit the [GitHub repository](https://github.com/mralaminahamed/custom-translation-api-integration).
