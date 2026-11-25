=== Security Header Shield ===
Contributors: markonofrio
Tags: security, headers, csp, hsts, referrer-policy
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds HTTP security headers to your site, with a settings page to control them.

== Description ==

Security Header Shield sends these headers on front-end pages, the login screen and (optionally) the REST API:

* Referrer-Policy: strict-origin-when-cross-origin
* X-Content-Type-Options: nosniff
* X-XSS-Protection (0, 1 or 1; mode=block)
* X-Frame-Options: SAMEORIGIN
* X-Permitted-Cross-Domain-Policies: none
* Strict-Transport-Security (opt-in, HTTPS only)
* Content-Security-Policy: frame-ancestors, object-src, base-uri, form-action, upgrade-insecure-requests and frame-src (built from a list of common services plus your own domains), with a Report-Only mode for testing
* Cross-Origin-Opener-Policy: same-origin
* Permissions-Policy: turn off camera, microphone, location and other browser features you do not use (combined with any Permissions-Policy your host already sends)

It can also hide your WordPress version and disable XML-RPC.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install the ZIP from Plugins > Add New > Upload Plugin.
2. Activate the plugin.
3. Go to Settings > Security Header Shield to choose which headers to send.

== Frequently Asked Questions ==

= Does frame-src stop other sites from embedding my site? =

No. frame-src controls which sites your pages may load in iframes. X-Frame-Options (always sent as SAMEORIGIN) stops other sites from framing yours.

= Why is Strict-Transport-Security off by default? =

Browsers remember it for a year. Turn it on only after the whole site works over HTTPS, and only use includeSubDomains if every subdomain does too.

= Something broke after turning on a Content-Security-Policy rule. =

Switch Content-Security-Policy to Report-Only, reload the page and open the browser's developer console. It lists everything the policy would block, so you can see which rule to turn off or which site to add.

= Does "Hide WordPress version" hide everything? =

It removes the generator tag and the version from core script and style URLs. The readme.html file in your WordPress folder is served directly by the web server, so delete it or block it there.

== Changelog ==

= 1.2.0 =
* New: Content-Security-Policy rules frame-ancestors, object-src, base-uri, form-action and upgrade-insecure-requests.
* New: Report-Only mode for Content-Security-Policy.
* New: Cross-Origin-Opener-Policy and Permissions-Policy headers.
* New: hide the WordPress version, disable XML-RPC, and send headers on REST API responses.

= 1.1.0 =
* Fix: keep the headers WordPress sets itself (Content-Type, no-cache headers for logged-in users, feed headers).
* Fix: the Content-Security-Policy header now actually contains frame-src and the selected sites.
* Fix: the Disable options for Referrer-Policy, X-Content-Type-Options and CSP now work.
* Fix: X-XSS-Protection 0 and 1 stay selected after saving.
* Fix: "Other Sites" accepts one domain per line and drops invalid entries.
* New: Strict-Transport-Security is now a setting (off by default) and only sent over HTTPS.
* New: headers are also sent on the login page.
* Change: Referrer-Policy value is now strict-origin-when-cross-origin; X-XSS-Protection defaults to 0.
* Settings are removed when the plugin is deleted.

= 1.0.0 =
* Initial release.
