<?php

class BBSiteConfigDecorator extends DataObjectDecorator {

	public function extraStatics() {
		return array(
			'db' => array(
			)
		);
	}

	public function updateCMSFields(FieldSet &$fields) {
		//remove the theme option
		$fields->removeByName('Theme');
	}

}
