<?php

class BBSiteConfigDecorator extends DataObjectDecorator {

	public static
		$db = array(
		);

	public function updateCMSFields(FieldList $fields) {
		//remove the theme option
		$fields->removeByName('Theme');
	}

}
