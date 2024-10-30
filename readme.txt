=== Matches ===
Contributors: nemrod
Donate link: http://nemrod.se
Tags: matches, sports, games
Requires at least: 3.0.1
Tested up to: 3.2.1
Stable tag: 0.5

This plugin makes it easy to administer and display matches (sports or otherwise) with a neat widget.

== Description ==

This plugin makes it easy to administer and display matches (sports or otherwise) with a neat widget. It provides you with an options page where you can set your team's name, home field and logo as well as how many matches to show and settings to change the date format (and optionally localizing all timestamps to the visitor's local time). It then provides a separate admin page to add new teams along with their logos. Finally you can add matches between yourself and the teams you've added along with scores (only shown for previous matches in the widget) and optionally a link for more information. It's simple, effective and easy to use, not to mention easy to style with some basic CSS knowledge.

I try to keep up with the topics in the forums here as well as the comments on <a href="http://nemrod.se/wordpress/matches-wordpress-plugin/" target="_blank">my website</a>, where I'm prone to seeing them sooner due to e-mail notifications.

If you an awfully generous person and like this plugin any donations are tremendously appreciated (there is a <a href="http://nemrod.se/wordpress/matches-wordpress-plugin/#donate" target="_blank">donate button in the sidebar of my website</a>)

== Installation ==

1. Upload the `matches` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Drag the 'Matches' widget to where you want it using the widget menu in WordPress. For templates without widget support you can place `<?php if(function_exists('matches_display_widget')) { matches_display_widget(); } ?>` in your template files.
1. Modify `widget.css` in the plugin directory to style the widget to your liking 

== Screenshots ==

1. What the widget looks like with the default stylesheet. Everything is easily customisable via CSS.
2. The options page where you set the number of upcoming matches to display in the widget as well as information about your own team.
3. The administration of opponent teams.
4. The administration of matches, past and upcoming.

== Changelog ==

= 0.5 =
* You can now choose to display timestamps in the visitor's own timezone.
* It now registers as a "real" widget meaning you won't need to add any PHP code to the template. The old code still works for backwards-compatibility and for templates without widget support.
* Match links open in a new tab/window (target="_blank").
* Added a per-match override setting for home/away match.
* Modified the area the match link covers slightly to make it less ambiguous.

= 0.4 =
* Date and time format can now be changed in the settings.

= 0.3 =
* First public release.
* Code clean-up.
* Database-related match link fix.
* Stylesheet overhaul.

= 0.2 =
* Code clean-up.
* Added match link.

= 0.1 =
* Initial in-house release.
