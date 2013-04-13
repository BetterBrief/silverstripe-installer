<?php

class HomePage extends Page {

	// tree customisation
	//static $icon = "";
	//static $allowed_children = array("SiteTree"); // set to string "none" or array of classname(s)
	//static $default_child = "Page"; //one classname
	//static $default_parent = null; // NOTE: has to be a URL segment NOT a class name
	//static $can_be_root = true; //
	//static $hide_ancestor = null; //dont show ancestry class

	public static
		$db = array(
		);

	public function canCreate($member = null) {
		return HomePage::get()->first() ? false : parent::canCreate($member);
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		//don't let them change the home page URL... it's a pain when they do
		$fields->removeFieldFromTab('Root','URLSegment');

		return $fields;
	}

}

class HomePage_Controller extends Page_Controller {

	public static
		$allowed_actions = array(
		);

}
