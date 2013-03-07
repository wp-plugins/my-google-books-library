=== Plugin Name ===
Contributors: hugmin42
Donate link: http://hugo.activesquirrel.com/donate
Tags: reading book, currently reading, my library, currently reading book, google books, simple currently reading book, books, ebooks, google, library, shelf, bookshelf, read, books i've read, widget, plugin, favourite books, display book
Requires at least: 3.0.1
Tested up to: 3.5
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin with a widget and [shortcode] that displays any number of your Google Books bookshelves including custom made bookshelves.

== Description ==

IMPORTANT: I have stopped development of this plugin, as my knowledge is too limited to fix the bugs that some users experience.  Anyone who would like to take over the development can contact me in this regard. For an alternative plugin that does basically the same thing see: [Google Bookshelves](http://wordpress.org/extend/plugins/google-bookshelves/ "Google Bookshelves"), I cannot however guarentee that, that plugin will be without bugs.

This is a simple wordpress plugin that allows you to showcase your Google Books bookshelves. It uses the Google Books API, and can show any of the default shelves: Reading Now, Favorites, Have Read or To Read as well as custom shelves. You can also choose the maximum number of books that you want to display.

I used some of the code from ZeroCool51's ([Bostjan Cigan](http://bostjan.gets-it.net "Bostjan Cigan")) plugin [Currently Reading Book](http://wordpress.org/extend/plugins/currently-reading-book/ "Currently Reading Book") for the admin area and [aharris88](http://adamwadeharris.com "aharris88")'s plugin [Google Bookshelves](http://wordpress.org/extend/plugins/google-bookshelves/ "Google Bookshelves") for the widget. Special thanks to [Vadym](http://v.bartko.info "Vadym") for his code to fix the max 40 books problem.

What this plugin offers:

*   Widget for showing any number of book covers from any of your shelves.
*   Use shortcode to show a list of your books from any google books shelves including custom shelves in any post or page.
*   Php function that can be put into any template file
*   Two different templates for displaying the books. List view with cover, title, author and description OR Grid view with only the covers.

This is my first plugin and any feedback would be appreciated.

== Installation ==

1. Upload the plugin directory to to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your Google Books id on the settings page (instructions on settings page).
5. Place `<?php my_google_books_library(); ?>` in your templates
6. and/or add the widget 'My Google Books Library Widget' to the theme you are currently using
7. and/or place the schortcode e.g. [my_google_books_library shelf="4" max="10" layout="2"] on any post or page.

== Frequently Asked Questions ==

= Where do I get my Google Books ID? =

See the instructions on the plugin's settings page which can be found under the settings tab in your Dash.

= More questions? =

The plugin's settings page aims to explain everything if not contact me at one of the provided links.

= Can you add this can you do that? =

I'm a full time Investment Management student and have limited time and knowledge. I will however try my best to make this plugin as usable as possible.

= More requests? =

Consider making a [donation](http://hugo.activesquirrel.com/donate "donation") to keep me motivated.

== Screenshots ==

1. An example of how the plugin functions and works on the new twentytwelve theme.
2. Example of how the shortcode is used on a page.
3. My Google Books Library Settings page
4. My Google Books Library widgets

== Changelog ==

= 1.2 =
* Fixed: Overcame issue with Google API that only allows for max of 40 books to be retrieved. Thank you Vadym for the code.

= 1.1 =
* Added: New layout template: Grid view. Add layout="2" in your shortcode to make use of the new layout. E.g. [my_google_books_library shelf="4" max="10" layout="2"]
* Added: Radomization - random books from your selected shelf will be shown with every refresh.

= 1.0.1 =
* Fixed: Bug where shortcode appeared on top of page instead of where it was placed.

= 1.0 =
* The initial version of the plugin.

== Upgrade Notice ==

= 1.2 =
Fixes API issue that only allowed 40 books to be retrieved.

= 1.1 =
Adds a new layout template and more.

= 1.0.1 =
Fixes a bug where shortcode appears on top of page instead of where it was placed.

== Author ==

The author of this plugin is Hugo Minnaar, visit the [homepage](http://hugo.activesquirrel.com "homepage").

== Homepage ==

Visit the [homepage](http://hugo.activesquirrel.com/dev/my-google-books-library "homepage") of the plugin.
