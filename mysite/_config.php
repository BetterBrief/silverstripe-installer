<?php

global $project;
$project = 'mysite';

require_once(FRAMEWORK_PATH . '/conf/ConfigureFromEnv.php');

//details here if they don't configure from environment - typically live details
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
	//set the environment type (dev|test|live)
	Config::inst()->update('Director', 'environment_type', 'live');
}

if (Director::isLive()) {
	//add in our custom error emailer
	$emailWriter = new SS_LogEmailWriter('betterbrief+[user]@gmail.com');
	$emailWriter->setFormatter(new BB_LogErrorEmailFormatter());
	SS_Log::add_writer($emailWriter);
	Config::inst()->update('GoogleSitemap', 'google_notification_enabled', true);
	//Config::inst()->update('Email', 'admin_email', '');
}

// stop the user being able to select h1 in the editor!
HtmlEditorConfig::get('cms')->setOption('theme_advanced_blockformats', 'p,h2,h3,h4,h5,h6,address,pre');

//allow full search of the site
//FulltextSearchable::enable();

//removes m parameter
Requirements::set_suffix_requirements(false);
