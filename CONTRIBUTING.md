# Contributing to Custom Translation API Integration

First off, thank you for considering contributing to Custom Translation API Integration! It's people like you that make this plugin a great tool for the WordPress community.

## Table of Contents

1. [Code of Conduct](#code-of-conduct)
2. [Getting Started](#getting-started)
   - [Issues](#issues)
   - [Pull Requests](#pull-requests)
3. [Development Environment](#development-environment)
4. [Coding Standards](#coding-standards)
5. [Testing](#testing)
6. [Documentation](#documentation)
7. [Developer Documentation](#developer-documentation)
   - [Plugin Structure](#plugin-structure)
   - [Key Classes and Methods](#key-classes-and-methods)
   - [Hooks and Filters](#hooks-and-filters)
   - [API Integration](#api-integration)
   - [Caching Mechanism](#caching-mechanism)
8. [Releasing](#releasing)

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to [your@email.com].

## Getting Started

### Issues

- Ensure the bug was not already reported by searching on GitHub under [Issues](https://github.com/yourusername/custom-translation-api-integration/issues).
- If you're unable to find an open issue addressing the problem, [open a new one](https://github.com/yourusername/custom-translation-api-integration/issues/new).
- Be sure to include a title and clear description, as much relevant information as possible, and a code sample or an executable test case demonstrating the expected behavior that is not occurring.

### Pull Requests

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Ensure the test suite passes.
5. Make sure your code lints.
6. Issue that pull request!

## Development Environment

To set up your development environment:

1. Clone the repository: `git clone https://github.com/yourusername/custom-translation-api-integration.git`
2. Set up a local WordPress installation (we recommend using [Local by Flywheel](https://localwp.com/))
3. Symlink or copy the plugin folder into your local WordPress' `wp-content/plugins/` directory
4. Activate the plugin in your WordPress admin panel

## Coding Standards

We follow the [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/). Please ensure your code adheres to these standards.

- Use [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) to check your code.
- Run `phpcs --standard=WordPress path/to/your/file.php` to check a specific file.

## Testing

We use [PHPUnit](https://phpunit.de/) for unit testing. To run the tests:

1. Install PHPUnit
2. Navigate to the plugin directory
3. Run `phpunit`

Please add tests for any new functionality and ensure all tests pass before submitting a pull request.

## Documentation

- Update the README.md with details of changes to the interface or functionality.
- Update the plugin's inline documentation (DocBlocks) for any changed or new methods.
- If applicable, update the [Developer Documentation](#developer-documentation) section in this file.

## Developer Documentation

### Plugin Structure

The plugin consists of a main class `Custom_Translation_API_Integration` which handles the core functionality:

```
custom-translation-api-integration/
├── custom-translation-api-integration.php
├── languages/
│   └── custom-translation-api-integration.pot
├── includes/
│   └── class-api-handler.php
├── tests/
│   └── test-api-handler.php
├── .gitignore
├── README.md
├── CONTRIBUTING.md
└── LICENSE
```

### Key Classes and Methods

1. `Custom_Translation_API_Integration`: Main plugin class
   - `__construct()`: Sets up hooks
   - `custom_translations_api()`: Handles the `translations_api` filter
   - `get_translations_from_api()`: Fetches translations from the custom API
   - `filter_translations_api_result()`: Processes the API result

2. `API_Handler`: Handles API communication (in `includes/class-api-handler.php`)
   - `send_request()`: Sends requests to the custom API
   - `parse_response()`: Parses the API response

### Hooks and Filters

1. `translations_api`: Main filter for overriding the WordPress.org translations API
   - Priority: 10
   - Arguments: 3 (`$result`, `$type`, `$args`)

2. `translations_api_result`: Filter for modifying the translation API result
   - Priority: 10
   - Arguments: 3 (`$result`, `$type`, `$args`)

3. `plugins_loaded`: Action hook for initializing the plugin
   - Priority: 10
   - Arguments: 0

### API Integration

The custom API should accept POST requests with the following parameters:

- `type`: The type of translations ('plugins', 'themes', or 'core')
- `wp_version`: The current WordPress version
- `locale`: The current locale
- `version`: The version of the plugin, theme, or core
- `slug`: The slug of the plugin or theme (not included for core translations)

The API should return a JSON response in the following format:

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

### Caching Mechanism

The plugin uses WordPress transients for caching API responses:

- Cache key: `'ctai_' . md5(serialize(array($type, $args)))`
- Cache duration: 3 hours (customizable)

To modify the cache duration, update the value in the `set_site_transient()` call in the `custom_translations_api()` method.

## Releasing

1. Update the version number in:
   - `custom-translation-api-integration.php` (in the plugin header)
   - `README.md` (in the "Changelog" section)
2. Update the `CHANGELOG.md` file
3. Commit these changes with a message like "Prepare for x.x.x release"
4. Create a new release on GitHub with a tag matching the new version number
5. Update the plugin on the WordPress.org repository, if applicable

Thank you for contributing to Custom Translation API Integration!
