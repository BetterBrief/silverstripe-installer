<?php

global $project;
$project = 'mysite';

require_once(FRAMEWORK_PATH . '/conf/ConfigureFromEnv.php');

//Live details here if they don't configure from environment
if (!defined('SS_ENVIRONMENT_FILE')) {
	global $databaseConfig;
	$databaseConfig = array(
		'type' => 'MySQLDatabase',
		'username'      => '',
		'password'      => '',
		'database'      => '',
		'server'        => '',
		'path'          => ''
	);
	Email::setAdminEmail('');
	//add in our custom error emailer
	$emailWriter = new SS_LogEmailWriter('betterbrief+[user]@gmail.com');
	$emailWriter->setFormatter(new BB_LogErrorEmailFormatter());
	SS_Log::add_writer($emailWriter);
}

MySQLDatabase::set_connection_charset('utf8');

DataObject::add_extension('Image', 'BBImageDecorator'); //enhancing the image class with a decorator
DataObject::add_extension('SiteConfig','BBSiteConfigDecorator'); //enhancing the siteconfig with a decorator

GD::set_default_quality(85);

// Set the correct default language, this is used for Users
// and for the content-language meta tag
//i18n::set_default_lang('en_GB');
//i18n::set_default_locale('en_GB');
i18n::set_locale('en_GB');

// stop the user being able to select h1 in the editor!
HtmlEditorConfig::get('cms')->setOption('theme_advanced_blockformats', 'p,h2,h3,h4,h5,h6,address,pre');

//set admin user var for analytics for admin users
LeftAndMain::require_javascript('mysite/javascript/admin-analytics.js');

//allow full search of the site
//FulltextSearchable::enable();


//stop default pages
if(class_exists('SiteTree')) {
	SiteTree::enable_nested_urls();
	SiteTree::set_create_default_pages(false);
	// Breadcrumb delimiter
	//SiteTree::$breadcrumbs_delimiter = " - ";
}

//removes m parameter
Requirements::set_suffix_requirements(false);

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('default');
