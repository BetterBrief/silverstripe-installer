<?php

class HomePage extends Page {

	public function canCreate($member = null) {
		return HomePage::get()->first() ? false : parent::canCreate($member);
	}

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		// don't let people accidentally change the home page URL
		$fields->removeFieldFromTab('Root','URLSegment');
		return $fields;
	}

}

class HomePage_Controller extends Page_Controller {

}
