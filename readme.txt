=== People Page ===
Contributors: jethin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MJZSQZA3VVZ8W
Tags: users, authors, photos, bios, staff
Requires at least: 3.0
Tested up to: 3.9.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a "People Page" that displays a list of selected site users with photos, bios, titles, links and more.

== Description ==

This plugin allows editors to create a "People Page" that displays a list of selected site users with photos, bios, titles, profile and website links and more.

When activated via a page template, the plugin creates a drag and drop meta box which editors can use to select users and create headers. These users are listed on the people page template, along with links to their full profile when applicable.

The plugin also creates custom "Title" and "Photo" fields on the "User Edit" admin screen, which are used in theme pages. The plugin also supports "User Photo" plugin images and gravatars.

Three template files for both the “Twenty Twelve” and “Twenty Thirteen” themes are included within the /theme-templates/ directory:

* **"people-page.php"** displays the people page index list
* **"author.php"** displays a single user’s info page
* **“people-page.css”** contains basic people page styles for each theme; this file can be customized


See "Other Notes" for more details and usage instructions.

== Installation ==

1. Download and unzip the plugin file.
1. Upload the unzipped 'people-page' directory to your '/wp-content/plugins/' directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. See "Other Notes" for usage instructions.

== Other Notes ==

**Required Theme Files**

After installation a "people-page.php" template must be created inside your theme's folder in order to use the plugin. Default templates (based on the Twenty Twelve and Twenty Thirteen themes) are included inside the plugin's 'theme-templates' directory. Copy this file to your theme’s directory if you wish to use it. You can also create a custom template from your site's theme:

1. Duplicate the "page.php" file in your theme's folder and rename it "people-page.php"
1. Assign your new page as a template by entering the following comment directly after the first php tag: /* Template Name: People Page */
1. Insert the people page *index* function after the WordPress loop (or wherever you'd like it to appear):

`<?php echo people_page(); ?>`

*Excerpt Length Argument (integer, optional)*: Set the character limit where "Biographical Info" will be excerpted. Default = 420.

A CSS stylesheet "people-page.css" that contains basic styles can be found in each of the 'theme-templates' subdirectories. Copy it into your theme's directory to apply the default styles. The styles in this file can be altered to better match your theme.

You may also wish to customize your theme's "author.php" template, which is used to display users' profiles. A sample "author.php" file (based on the Twenty Twelve theme) is included in the 'theme-templates'. To use your theme's existing "author.php" file, insert the people page *author* function after the loop (or wherever you'd like it to appear):

`<?php echo people_page_author(); ?>`

The people page data is stored as an array of user IDs and heading strings. You can access this array directly in your theme using get_post_meta():

`<?php $people = get_post_meta( $post->ID, 'peeps', true ); ?>`

**Using the Plugin**

To activate the plugin, set a page's template to use the "People Page" template and update the page. You should now see the people page meta box in the center column. Use this area to select and arrange users and create section headings if desired.

**Additional User Edit / Profile Fields**

"Title" and "Photo" fields (optional) are added to the bottom of the "Edit User" admin page when the plugin is activated. When set these fields are included in people page displays. If a photo is not set the plugin will attempt to display 1) a photo set using the "User Photo" plugin or 2) a gravatar. Gravatar size is 150 pixels by default.

**Default Displays**

*People Page*

* Photo (if set; if not set and available: "User Photo" image; gravatar)
* Name (linked if "Biographical Info" field is excerpted)
* Title (if set)
* [ posts | website ] (links if: User has posts (to profile) | website if field is set)
* Biographical Info (includes link to profile if text is excerpted)

*Author Page*

* Photo (if set; if not set and available: "User Photo" image; gravatar)
* Name
* Title (if set)
* [ website ] (if set)
* Biographical Info
* Posts (last three; post details: date, and excerpt - if set: comment number, category, tags)

== Screenshots ==

1. The "Edit Page" admin screen with "People Page" template selected (right) and displaying the people page meta box (center). "Active" users will be included on the people page.
2. A people page template based on the Twenty Twelve theme. It lists active users, meta information, biographical excerpts and links to users' profile page (author.php).
3. A user profile page (author.php) based on the Twenty Twelve them showing full biographical information and latest posts (three maximum).

== Changelog ==

= 1.1 =
* Updated for WordPress 3.9.1 compatibility; Added templates for Twenty Thirteen theme; Visual editor on users’ “Biographical Info” field.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.1 =
Updated for WordPress 3.9.1 compatibility; Added templates for Twenty Thirteen theme; Visual editor on users’ “Biographical Info” field.