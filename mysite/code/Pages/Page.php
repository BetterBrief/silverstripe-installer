<?php
class Page extends SiteTree {

	// tree customisation
	//static $icon = "";
	//static $allowed_children = array("SiteTree"); // set to string "none" or array of classname(s)
	//static $default_child = "Page"; //one classname
	//static $default_parent = null; // NOTE: has to be a URL segment NOT a class name
	//static $can_be_root = true; //
	//static $hide_ancestor = null; //dont show ancestry class

	public static $db = array(
	);

	public static $has_one = array(
	);

	/**
	 * Add default records to database.
	 *
	 * This function is called whenever the database is built, after the
	 * database tables have all been created. Overload this to add default
	 * records when the database is built, but make sure you call
	 * parent::requireDefaultRecords().
	 */
	function requireDefaultRecords() {
		// default pages
		//Make a home page
		if(!$homepage = DataObject::get_one("Page", "URLSegment = 'home'")) {
			$homepage = new HomePage();

			$homepage->Title = _t('SiteTree.DEFAULTHOMETITLE', 'Home');
			$homepage->Content = _t('SiteTree.DEFAULTHOMECONTENT', '<p>Welcome to SilverStripe! This is the default homepage. You can edit this page by opening <a href="admin/">the CMS</a>. You can now access the <a href="http://doc.silverstripe.com">developer documentation</a>, or begin <a href="http://doc.silverstripe.com/doku.php?id=tutorials">the tutorials.</a></p>');
			$homepage->URLSegment = "home";
			$homepage->Status = "Published";
			$homepage->write();
			$homepage->publish("Stage", "Live");
			$homepage->flushCache();
			DB::alteration_message("Home page created","created");
		}
		elseif ($homepage->ClassName != 'HomePage'){
			$homepage->ClassName = 'HomePage';
			$homepage->write();
			$homepage->publish("Stage", "Live");
			$homepage->flushCache();
			DB::alteration_message("Home page type changed","repaired");
		}

		//Make a footer holder and redirector, if there is no footer, then
		//it has no children, so create a sitemap and redirect to that.
		if (!DataObject::get_one('RedirectorPage',"URLSegment = 'footer'")) {
			$footerParent = new RedirectorPage();
			$footerParent->Title = 'Footer';
			$footerParent->FooterHolder = true;
			$footerParent->Status = 'Published';
			$footerParent->ShowInMenus = false;
			$footerParent->ShowInSearch = false;
			$footerParent->write();

			$siteMap = new SiteMap();
			$siteMap->Title = 'Sitemap';
			$siteMap->NavigationLabel = 'Sitemap';
			$siteMap->URLSegment = 'sitemap';
			$siteMap->ParentID = $footerParent->ID;
			$siteMap->Status = 'Published';
			$siteMap->write();

			$footerParent->LinkToID = $siteMap->ID;
			$footerParent->write();

			$footerParent->publish("Stage", "Live");
			$siteMap->publish("Stage", "Live");

			$siteMap->flushCache();

			DB::alteration_message("Footer Holder created","created");
			DB::alteration_message("Sitemap created","created");
		}

		parent::requireDefaultRecords();
	}

}
class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	public static $allowed_actions = array (
	);

	function init() {
		parent::init();
		//allow me to quickly access pages by ID :)
		if (is_numeric(Director::urlParam('URLSegment')) && Director::urlParam('URLSegment') == (int)Director::urlParam('URLSegment')) {
			if ($page = DataObject::get_by_id('Page',(int)Director::urlParam('URLSegment'))) {
				$this->redirect($page->Link(),301);
			}
		}
	}

	/**
	 * get Page Link
	 *
	 * Gets a page link for a specific type of page. Best used for pages that
	 * will only have one instance, or it doesnt matter which instance you want
	 *
	 * Caches the result
	 *
	 * @param string $pageType The page type to get the link of
	 * @param string $action The action to add to the end of the URL
	 *
	 * @return string The link.
	 *
	 */
	function getPageLink($pageType,$action = null) {
		$varName = $pageType . 'Link';
		if ($action && isset($this->$varName)) {
			return Controller::join_links($this->$varName,$action);
		}
		if (!isset($this->$varName)) {
			if ($this->ClassName == $pageType) {
				$page = $this;
			}
			else {
				$page = DataObject::get_one($pageType);
			}
			if ($page) {
				$this->$varName = $page->Link();
				if ($action) {
					return $page->Link($action);
				}
			}
			else {
				$this->$varName = '#';
			}
		}
		return $this->$varName;
	}

	// For templates
	function PageLink($pageType) {
		return $this->getPageLink($pageType);
	}

	// relies on requireDefaultRecords
	function getFooterMenu() {
		$footer = DataObject::get_one('RedirectorPage', 'URLSegment = \'footer\'');
		if($footer) {
			return $footer->Children();
		}
	}

	/**
	 * Copyright Year
	 *
	 * Returns the year or the range of years copyright is held for.
	 *
	 * @return string A string of year(s)
	 */
	function getCopyrightYear($startYear = 2012) {
		$curYear = date('Y');
		if ($curYear > $startYear) {
			return $startYear . ' - ' . $curYear;
		}
		return $startYear;
	}
}
