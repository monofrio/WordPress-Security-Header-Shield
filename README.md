# WordPress Security Header Shield

## Overview
**WordPress Security Header Shield** is a custom WordPress plugin that enhances your website's security by managing and configuring important HTTP security headers. This plugin gives you control over critical headers to protect your site against common web vulnerabilities and attacks.

## Features
- **Referrer-Policy Header**: Configure the Referrer-Policy to control what information is included with requests to other websites.
- **X-Content-Type-Options Header**: Prevent browsers from interpreting files as a different MIME type to enhance protection.
- **X-XSS-Protection Header**: Mitigate cross-site scripting (XSS) attacks with this essential header.
- **Content-Security-Policy (CSP) Frame-Src**: Define approved domains for embedding frames. Includes a default list for convenience and allows users to add custom domains.

## Compatibility
- Fully tested and compatible with Gutenberg-based sites.
- Supports WordPress versions from the Gutenberg release (WordPress 5.0) and higher.

## Installation
1. Download the plugin from this repository or your WordPress admin dashboard.
2. Navigate to `Plugins` > `Add New` in your WordPress admin area.
3. Click `Upload Plugin` and select the downloaded ZIP file.
4. Click `Install Now` and activate the plugin.

## Configuration
1. Once activated, go to `Settings` > `Security Header Shield` in your WordPress admin dashboard.
2. Configure the following headers as per your requirements:
    - **Referrer-Policy Header**: Choose the desired policy (e.g., `no-referrer`, `origin`, etc.).
    - **X-Content-Type-Options Header**: Enable this header to enforce MIME type checking.
    - **X-XSS-Protection Header**: Toggle on to enable XSS protection.
    - **Content-Security-Policy (CSP) Frame-Src**:
        - Enable default domains from the provided list.
        - Add custom domains as needed for embedding frames securely.
3. Save changes to apply your configurations.

## Usage
- The plugin automatically applies the configured headers to your WordPress site.
- Use the settings page to modify or add custom configurations at any time.

## Support
If you encounter issues or have feature requests, please open an issue in this repository. Contributions are welcome!

## License
This plugin is released under the MIT License. See the [LICENSE](./LICENSE) file for details.

---

Thank you for using **WordPress Security Header Shield**! Stay secure and happy WordPressing!

