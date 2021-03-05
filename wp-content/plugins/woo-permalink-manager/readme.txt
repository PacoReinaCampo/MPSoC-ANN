=== Premmerce Permalink Manager for WooCommerce ===
Contributors: premmerce, freemius
Tags: woocommerce url, remove product, remove product_category, woocommerce permalink, woocommerce, woocommerce seo
Requires at least: 4.8
Tested up to: 5.6
Stable tag: 2.3.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Premmerce Permalink Manager for WooCommerce allows you to change WooCommerce permalink and remove product and product_category slugs from the URL.

== Description ==

Premmerce Permalink Manager for WooCommerce is developed to shortener the current WooCommerce URL to increase your site SEO ranking. 
According to several industry studies, we acknowledge at least two purposes to utilize Permalink Manager for eCommerce:

* **URL Length.** According to [Ahrefs research](https://ahrefs.com/blog/on-page-seo/), there is a clear correlation between the number of characters in a URL and rankings.
* **URL Path.** Another well-known fact in the industry is that the closer the page located to the homepage, the more authority boost it may get vs. pages hidden deep down in a site's architecture. You can read more on top ranking factors on the [Backlinko article](https://backlinko.com/google-ranking-factors).

[youtube https://www.youtube.com/watch?v=-TMpzsSPiMU]

= The main plugin features =

* Removing **/product-category** base from URL to leave the only category slug. E.g., having site.com/electronics instead of site.com/product-category/electronics 
* Removing  **/product** base from URL to leave the only product slug. E.g., having site.com/iphone-xr instead of site.com/product/iphone-xr
* **Automatic adding of the 'rel = canonical'** attribute eliminates duplicate pages in the search results. This is highly recommended to avoid pages duplication in a Google index 
* Tested with a large number of products (over 100k)
* Simple plugin's interface lets you set up everything on one page with just a few clicks.
* Compatibility with the foremost WooCommerce plugins, as well as with WPML
* Tested with a large number of products (over 100k)

= The main competitive advantages of the plugin =

* Set up a product URL that consists of a product and category slugs. E.g., site.com/iphones/iphone-xr. Such a descriptive URL helps your SEO visibility as well, according to [Ahrefs study](https://ahrefs.com/blog/on-page-seo/)
* You can also set up a full path to the product through its categories hierarchy. E.g., site.com/phones/iphones/iphone-xr
* You can choose the main category to generate a product URL
* Removing all the prefixes from the URL, leaving just the category or product name
* You can choose a main category to generate a product URL.

= Additional features available in Premmerce Permalink Manager for WooCommerce Premium only: =

[Premmerce Permalink Manager for WooCommerce Premium](https://premmerce.com/woocommerce-permalink-manager/)

* **Remove the base from the product tags URL**. E.g., site.com/best-deals instead of site.com/product-tag/best-deals
* **Automatic creation of 301 redirects**  for duplicated pages. It's extremely important in case you don't wanna lose old structure URLs weight in Google ranking 
* **Breadcrumbs Support**
* **Replace the product slug with an SKU**
* **Adding a suffix to the product and category URL**
* Premium support 

= DEMO =

You can check how it works on the Storefront theme here:  <https://storefront.premmerce.com>.

Or on the SalesZone theme:  <https://saleszone.premmerce.com>.

Plus, you can create your demo-store and test this plugin together with  [Premmerce Premium](https://premmerce.com/features/)
and all other Premmerce plugins and themes developed by our team available here: [Premmerce WooCommerce Demo](https://premmerce.com/premmerce-woocommerce-demo/).

= This plugin is 100% compatible with =

* base WooCommerce functionality
* Yoast SEO
* WPML WooCommerce Multilingual
* Polylang
* all the Premmerce plugins

== Screenshots ==

1. Ahrefs research - Dependence between the number of characters in URL & Google ranking
2. The plugin settings
3. Url example
4. Number of characters in URL (median)

== Frequently Asked Questions ==

= Does the plugin affect the overall response rate of an online store?  =
We have tested how the plugin works with the basic WooCommerce kit and the standard storefront theme and haven't noticed any influence on the response rate of the store.

= Does the plugin create link duplicates? Does it affect SEO?  =
The plugin works perfectly with the Yoast SEO plugin installed. No link duplicates are generated. All similar URLs are automatically tagged as canonical in accordance with the search engines' recommendations.

= Documentation =
Full documentation is available here: [Premmerce Permalink Manager for WooCommerce](https://premmerce.com/woocommerce-permalink-manager-remove-shop-product-product-category-url/)

= Installation Instructions =
Go to Plugins -> Add New section from your admin account and search for Premmerce Permalink Manager for WooCommerce.

You can also install this plugin manually:
* Download the plugin's ZIP archive and unzip it.
* Copy the unzipped permalink-manager folder to the /wp-content/plugins/ directory.
* Activate the plugin through the â€˜Plugins' menu in WordPress

== Changelog ==

= 1.0 =

Release Date: July 24, 2017

* Initial release

= 1.0.1 =

Release Date: Sep 26, 2017

* Fixed a bug with wrong translation domain
* Updated FileManager

= 1.1 =

Release Date: Oct 31, 2017

* Added premmerce SDK
* Added freemius SDK
* Added php 5.6 support
* Added WooCommerce version check annotations
* Fixed behavior with unresolved plugin dependencies
* Fixed bug when domain has same name as url prefix
* Fixed bug with plain settings
* Fixed bug with rewrite rules after plugin install

= 1.1.1 =

Release Date: Nov 20, 2017

* Added WordPress 4.9 support
* Added WooCommerce 3.2.4 support

= 1.1.2 =

Release Date: Dec 06, 2017

* Fixed product comments pagination
* Added Premmerce icon to menu
* Added Premmerce version check
* Added WordPress 4.9.1 support
* Added WooCommerce 3.2.5 support

= 1.1.3 =

Release Date: Jan 24, 2018

* Updated freemius sdk
* Fixed the dependency check on multisite

= 1.1.4 =

Release Date: Feb 02, 2018

* Added WooCommerce 3.3.0 support
* Updated translations

= 2.0 =

Release Date: Apr 19, 2018

* Fixed product comments pagination
* Fixed WPML term link
* Fixed private product link
* Fixed product hierarchical permalink
* Updated settings interface
* Added "YOAST" primary category
* Added canonicals
* Added remove tag base to premium version
* Added redirects to premium version

= 2.0.1 =

Release Date: May 18, 2018

* Fixed payment redirect
* Fixed undefined index when product has no category

= 2.1 =

Release Date: Jun 22, 2018

* Fixed user trailing slash
* Fixed plugin updater scripts
* Added WooCommerce 3.4 support
* Added AMP support
* Removed freemius menu subitems
* Updated premmerce SDK

= 2.1.1 =

Release Date: Jul 31, 2018

* Fixed cyrillic slugs
* Fixed WPML language url trailing slash
* Fixed AMP redirects
* Fixed tag permalinks updates

= 2.1.2 =

Release Date: Aug 2, 2018

* Fixed upgrade page

= 2.1.3 =

Release Date: Nov 14, 2018

* Updated freemius version to 2.1.3
* Added WooCommerce 3.5 support

= 2.1.4 =

Release Date: Nov 27, 2018

* Fixed updater

= 2.1.5 =

Release Date: Nov 29, 2018

* Fixed updater

= 2.1.6 =

Release Date: Dec 20, 2018

* Fixed bug with cyrillic symbols in product slug
* Updated freemius version

= 2.1.7 =

Release Date: Mar 1, 2019

* Security fix

= 2.1.8 =

Release Date: Mar 12, 2019

* Fixed category slug translated with WPML

= 2.1.9 =

Release Date: Apr 22, 2019

* Updated WooCommerce compatibility version to 3.6

= 2.1.10 =

Release Date: May 2, 2019

* Fix canonical redirect from product with non published status

= 2.1.11 =

Release Date: Jun 24, 2019

* Update Freemius to 2.3.0
* Update Premmerce SDK to 2.1

= 2.1.12 =

Release Date: Sep 12, 2019

* Updated WooCommerce compatibility version to 3.7

= 2.1.13 =

Release Date: Feb 20, 2020

* Updated WooCommerce compatibility version to 3.9
* Updated WordPress compatibility version to 5.3
* Updated Freemius SDK to 2.3.2

= 2.1.14 =

Release Date: March 26, 2020

* Updated WooCommerce compatibility version to 4.0.1

= 2.2.0 =

Release Date: May 18, 2020

* Polylang compatibility
* new feature "Url suffix"
* support new symbols
* minor improvements

= 2.3.0 =

Release Date: December 15, 2020

* Added new feature "Breadcrumbs Support"
* Added new feature "Replace the product slug with an SKU"
* Updated WooCommerce compatibility version to 4.8
* Updated WordPress compatibility version to 5.6
* Updated Freemius SDK to 2.4.1
* Minor improvements and fixes
