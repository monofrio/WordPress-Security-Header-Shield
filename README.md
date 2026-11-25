# WordPress Security Header Shield

## Overview
**WordPress Security Header Shield** is a custom WordPress plugin that enhances your website's security by managing and configuring important HTTP security headers. This plugin gives you control over critical headers to protect your site against common web vulnerabilities and attacks.

## Features
- **Referrer-Policy Header**: Sends `strict-origin-when-cross-origin`, so other sites see only your domain, never the full page URL.
- **X-Content-Type-Options Header**: Prevent browsers from interpreting files as a different MIME type to enhance protection.
- **X-XSS-Protection Header**: Controls the legacy browser XSS filter. Defaults to `0`, the current recommendation, since the filter has been removed from modern browsers and could be abused in older ones.
- **Strict-Transport-Security (HSTS)**: Opt-in. Tells browsers to only use HTTPS for one year, optionally including subdomains. Sent only over HTTPS.
- **Content-Security-Policy (CSP)**: Rules that are safe for most sites: `frame-ancestors 'self'`, `object-src 'none'`, `base-uri 'self'`, `form-action 'self'` (off by default) and `upgrade-insecure-requests`. Includes a **Report-Only** mode for testing.
- **Content-Security-Policy (CSP) Frame-Src**: Define which sites your pages may load in iframes. Includes a default list for convenience and allows users to add custom domains.
- **Cross-Origin-Opener-Policy**: `same-origin`, so other sites can't reach into your pages through popups or tabs they open.
- **Permissions-Policy**: Turn off browser features you don't use (camera, microphone, location, screen capture, payment, USB, motion sensors). Combined with any Permissions-Policy your host already sends.
- **X-Frame-Options** (`SAMEORIGIN`) and **X-Permitted-Cross-Domain-Policies** (`none`) are always sent.
- **Hardening**: Hide the WordPress version, disable XML-RPC (off by default), and send the headers on REST API responses.

Headers are sent on front-end pages, the login page and, optionally, REST API responses.

## Compatibility
- Fully tested and compatible with Gutenberg-based sites.
- Supports WordPress versions from the Gutenberg release (WordPress 5.0) and higher.
- Requires PHP 7.4 or higher.

## Installation
1. Download the plugin from this repository or your WordPress admin dashboard.
2. Navigate to `Plugins` > `Add New` in your WordPress admin area.
3. Click `Upload Plugin` and select the downloaded ZIP file.
4. Click `Install Now` and activate the plugin.

## Configuration
1. Once activated, go to `Settings` > `Security Header Shield` in your WordPress admin dashboard.
2. Configure the following headers as per your requirements:
    - **Referrer-Policy Header**: Enable or disable.
    - **X-Content-Type-Options Header**: Enable this header to enforce MIME type checking.
    - **X-XSS-Protection Header**: Choose `0` (recommended), `1`, or `1; mode=block`.
    - **Strict-Transport-Security Header**: Leave disabled until the whole site (and, for `includeSubDomains`, every subdomain) works over HTTPS.
    - **Content-Security-Policy**: Pick Enforce or Report-Only and which rules to send. If something breaks, switch to Report-Only and check the browser console.
    - **Cross-Origin-Opener-Policy**, **Permissions-Policy** and **Hardening**: Uncheck anything your site needs.
    - **Content-Security-Policy (CSP) Frame-Src**:
        - Enable default domains from the provided list.
        - Add custom domains, one per line (for example `https://example.com` or `https://*.example.com`). Invalid entries are removed on save.
3. Save changes to apply your configurations.

## Usage
- The plugin automatically applies the configured headers to your WordPress site.
- Use the settings page to modify or add custom configurations at any time.
- Deleting the plugin from the Plugins screen removes its saved settings.

## Support
If you encounter issues or have feature requests, please open an issue in this repository. Contributions are welcome!

## License
This plugin is released under the GNU General Public License v2 or later. See the [license.txt](./license.txt) file for details.

---

Thank you for using **WordPress Security Header Shield**! Stay secure and happy WordPressing!
