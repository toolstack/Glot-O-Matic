# Glot-O-Matic

A full copy of GlotPress that's integrated with your WordPress install, including single sign on, all in a single plugin.

Contributors: GregRoss
Plugin URI: http://toolstack.com/glot-o-matic
Author URI: http://toolstack.com
Tags: glotpress admin
Requires at least: 3.9
Tested up to: 4.1
Stable tag: 0.5
License: GPLv2

A full copy of GlotPress that's integrated with your WordPress install, including single sign on.

## Description ##

A full copy of GlotPress that's full integrated with your WordPress install, including single sign on.

One click installation of GlotPress, no more fussing around with SVN and limited instructions!

Glot-O-Matic also adds the missing features you need to run GlotPress effectively:

* Manage admin users
* Add and delete and reset passwords for users in the local GlotPress user table
* Delete projects and translation sets
* View your GlotPress site right in the WordPress Admin interface
* Shortcode to embed your GlotPress installation right in to your WordPress front end
* Support for single sign on with WordPress, never see the "Login" link again in GlotPress

# License #
	
This code is released under the GPL v2, see license.txt for details.

## Installation ##

1. Extract the archive file into your plugins directory in the glot-o-matic folder.
2. Activate the plugin in the Plugin options.
3. Go to the GlotPress->Settings menu and select your options.

## Settings ##

Download File Name

	Use translation set slug for download file name
	
		By default when downloading a translation set GlotPress will use the project name plus the locale for the filename.  Enabling this option will instead use the translation set slug (which you can set by editing the translation set properties) unless it has not been set or is the default value.
		
Bulk Translation Download
	Enable bulk download of translation sets
	
		If you have a project with many translation sets it can be time consuming to download each one individually, this option will add an action to the project actions menu that will allow you to download all the translation sets at once as a zip file.

		By Default PO files will be included in the zip, you can control which types are included by selecting the options below.
		
	Include PO files in bulk downloads
	
		Include PO files in the bulk download zip.
	
	Include MO files in bulk downloads
	
		Include MO files in the bulk download zip.
	
	Include Android XML files in bulk downloads
	
		Include Android XML files in the bulk download zip.
	
	Include .NET files in bulk downloads
	
		Include .NET files in the bulk download zip.
	
	Include Mac/iOS String files in bulk downloads
	
		Include Mac/iOS String files in the bulk download zip.
	
Project Breadcrumbs
	Remove "Projects" from the breadcrumbs at the top of GlotPress
	
		GlotPres dispalys along the top of the page a series of breadcrumbs (just to the right of the GlotPress logo), if you have only a single project you may want to hide the "Projects" list from the breadcrumbs and this options allows you to do this.
	
	Append the following to the URL of the GlotPress logo link
	
		Like above, if you have a single project you may wish to link to it directly instead of the projects page, which you can do by adding the required path here (for example if you have a project with slug "test-project", adding "projects/test-project" to this field will take you there whenever you click the GlotPress logo).

External Links
	Open external links in new windows/tabs

		This will force any external links that appear on a GlotPress page (like the "Powered by" link in the footer) to open in a new window instead of the current one.  This should always be enabled as clicking on external links will break the resizing of the iFrame used to display GlotPress within WordPress.
	
Google Translate
	Global Google API Key(leave blank to disable)
	
		If you have a Google API key and wish to use it to automatically translate strings in GlotPress you can enter it here.  This key will be used for all logged in users.  Leaving this blank will disable the global Google Translate option.
	
	Enable per user API Keys
	
		This setting is only required if you have not enabled the global Google API Key but still want to enable the API for specific uses with the options below.
	
	Google API key for [user]
	
		Each admin user will be listed here and can be assinged a unique Google API key to use.
	
Footer
	Remove "...Powered By..." from the footer
	
		If you do not want to see the "Powered By" line in the footer you can enable this option to remove it.
	
Advanced
	Use this url when opening GlotPress
	
		If you have only a single project in GlotPress you may wish to link to it directly instead of the projects page, which you can do by adding the fully quailified URL here (for example if you have a project with slug "test-project", adding "http://yourserver/wp-content/plugsin/glot-o-matic/glotpress/projects/test-project" to this field will take you directly to that project).
	
	gp-config.php lines
	
		If you have any additional lines you need to add to the gp-config.php file, add them here.  You CANNOT manually edit the gp-config.php file as it is rewritten each time a setting is changed or an upgrade is done.
	
## Frequently Asked Questions ##

# I don't have GlotPress installed, can I still use Glot-O-Matic? #

Yes! Glot-O-Matic is a full one-click install of GlotPress in your WordPress system.

# I already have GlotPress installed, can I still use Glot-O-Matic? #

No, but you might be interested in (GP Integration)[http://wordpress.org/plugins/gp-integration] which has many of the same features.

You also might want to check out the plugins and other features available in the fork of GlotPress I maintain at (GitHub)[http://github.com/toolstack/GlotPress].

# What is the shortcode name? #

There are two short codes you can use:

[glot-o-matic] provides a fully embedded copy of GlotPress you can use anywhere short codes are supported.
[glot-o-matic-link] provides a link that will open in a new window directly to GlotPress.

# How does the shortcode work? #

The shortcode creates an iFrame along with a bit of JavaScript.  The JavaScript will resize the iFrame to match the height of the GlotPress page being displayed.  The JavaScript fires once a second so you may see a slight delay in the iFrame being resized.

Also note that external links the "Proudly powered by GlotPress" in the footer, if clicked, will break the resizing script.

## Screenshots ##

1. GlotPress inside of WordPress Admin.
2. GlotPress on the front end.
3. Configuration screen.
4. Translation set managmenet	.
5. Project management.
6. User management.
7. Admin management.

## Changelog ##
# 0.5 #
* Initial release.

## Upgrade Notice ##
# 0.5 #
* None.
