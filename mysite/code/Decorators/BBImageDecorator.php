<?php
/**
 * BB Image Decorator
 * 
 * Custom Image class to allow greater manipulation of images
 * 
 * @author Dan Hensby <dan@betterbrief.co.uk>
 * @copyright copyright (c) 2010, Better Brief LLP
 * 
 */
class BBImageDecorator extends DataObjectDecorator {
	
	public function Square($dim) {
		return $this->owner->CroppedImage($dim, $dim);
	}
	
	public function Side() {
		return $this->owner->CroppedImage(310,220);
	}
	
	public function UpTo($width, $height) {
		return $this->owner->getFormattedImage('UpTo', $width, $height);
	}
	
	public function generateUpTo(GD $gd, $width, $height) {
		return $gd->resizeRatio($width, $height, false);
	}

}
