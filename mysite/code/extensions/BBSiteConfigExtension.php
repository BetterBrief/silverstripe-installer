<?php

class BBSiteConfigExtension extends DataExtension {

	public static
		$db = array(
		);

	public function updateCMSFields(FieldList $fields) {
		//remove the theme option
		$fields->removeByName('Theme');
	}

}
