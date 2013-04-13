<?php

class SiteMap extends Page {
}

class SiteMap_Controller extends Page_Controller {

	/**
	 * This function will return a unordered list of all pages on the site.
	 * Watch for the switch between $page and $child in the second line of the foreach().
	 *
	 * Note that this will only skip ErrorPage's at the top/root level of the site.
	 * If you have an ErrorPage class somewhere else in the hierarchy, it will be displayed.
	 */
	public function SiteMap() {
		$rootLevel = DataObject::get("Page", "ParentID = 0"); // Pages at the root level only
		$output = "";
		$output = $this->makeList($rootLevel);
		return $output;
	}

	private function makeList($pages, $isTopLevel = true) {
		$output = "";
		$listAtts = $isTopLevel ? ' id="SiteMapList"' : '';
		if(count($pages)) {
			$output = '
			<ul'.$listAtts.'>';
				foreach($pages as $page) {
				if(!($page instanceof ErrorPage) && $page->ShowInMenus && $page->Title != $this->Title){
					$output .= '
					<li><a href="'.$page->AbsoluteLink().'" title="Go to the '.Convert::raw2xml($page->Title).' page">'.Convert::raw2xml($page->MenuTitle).'</a>';
					$whereStatement = "ParentID = ".$page->ID;
					//$childPages = new DataObjectSet();
					$childPages = DataObject::get("Page", $whereStatement);
					$output .= $this->makeList($childPages, false);
					$output .= '
					</li>';
				}
			}
			$output .= '
			</ul>';
		}
		return $output;
	}

}
