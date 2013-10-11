<?php

class HomePage extends Page {

	// tree customisation
	//static $icon = "";
	//static $allowed_children = array("SiteTree"); // set to string "none" or array of classname(s)
	//static $default_child = "Page"; //one classname
	//static $default_parent = null; // NOTE: has to be a URL segment NOT a class name
	//static $can_be_root = true; //
	//static $hide_ancestor = null; //dont show ancestry class

	private static
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

	public function index() {
		//Sync contacts, limiting to a small set for testing
		//Contact::sync('LastName =\'Hill\'');
		//Debug::dump(Account::sfGet());
		//Account::sync();
		//Membership::sync('Name = \'RCS-000267\'');
		//Debug::dump(implode(', ',Membership::getSalesforceFields()));
		//Debug::dump(implode(', ', Campaign::getSalesforceFields()));
		//Campaign::sync();
		//Debug::dump(implode(', ', CampaignMember::getSalesforceFields()));
		//CampaignMember::sync();
		//Debug::dump(implode(', ', Opportunity::getSalesforceFields()));

		//SalesforceDataObject::sync_all();
		if($first = Contact::get()->First()) {
			Debug::dump($first->getSalesforceData());	
		}
		
		
	}

	private static
		$allowed_actions = array(
		);

}
