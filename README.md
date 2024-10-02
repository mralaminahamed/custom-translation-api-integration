# Custom Translation API Integration

## Description

Custom Translation API Integration is a WordPress plugin that allows you to use a custom translation API instead of the default WordPress.org translation API. This plugin is ideal for sites that need to use a specific translation service or have custom translation requirements.

## Features

- Seamlessly integrates with WordPress's built-in translation update system
- Supports translations for plugins, themes, and WordPress core
- Caches API responses for improved performance
- Easily extendable for custom requirements

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher

## Installation

1. Upload the `custom-translation-api-integration` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your custom API URL in the plugin settings (if applicable)

## Usage

Once activated, the plugin will automatically use your custom translation API for all translation-related requests. No additional configuration is needed unless you need to specify a custom API URL.

## Frequently Asked Questions

### How do I specify my custom API URL?

You can modify the `$api_url` variable in the main plugin file. In future versions, we plan to add a settings page for easier configuration.

### Is this plugin compatible with WordPress multisite?

Yes, this plugin is designed to work with both single site and multisite WordPress installations.

## Changelog

### 1.2.0
- Initial public release
- Added support for WordPress core translations
- Improved error handling and reporting

## Upgrade Notice

### 1.2.0
This version adds support for WordPress core translations and includes several bug fixes and performance improvements.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL-2.0+ License - see the [LICENSE](LICENSE) file for details.
