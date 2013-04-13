<?php

class BBSiteConfigDecorator extends DataExtension {

	private static
		$db = array(
		);

	public function updateCMSFields(FieldList $fields) {
		//remove the theme option
		$fields->removeByName('Theme');
	}

}
