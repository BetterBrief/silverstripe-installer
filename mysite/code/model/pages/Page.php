<?php
class Page extends SiteTree {

	// tree customisation
	//static $icon = "";
	//static $allowed_children = array("SiteTree"); // set to string "none" or array of classname(s)
	//static $default_child = "Page"; //one classname
	//static $default_parent = null; // NOTE: has to be a URL segment NOT a class name
	//static $can_be_root = true; //
	//static $hide_ancestor = null; //dont show ancestry class

	public static
		$db = array(
			'MetaTitle' => 'Varchar(255)'
		),
		$has_one = array(
		);

	/**
	 * @inheritdoc
	 *
	 * Adding in the MetaTitle field
	 */
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main.Metadata', new TextField('MetaTitle'), 'MetaDescription');

		return $fields;
	}

	/**
	 * A custom getter for MetaTitle, if there isn't a value, use the page Title
	 *
	 * @return string The MetaTitle to use
	 */
	public function getMetaTitle() {
		if (!$metaTitle = $this->getField('MetaTitle')) {
			$metaTitle = $this->Title;
		}
		return $metaTitle;
	}

	/**
	 * A custom setter for MetaTitle, if the meta title is the same as the title
	 * don't store it in the DB
	 *
	 * @param string $val The value of the MetaTitle field
	 */
	public function setMetaTitle($val) {
		if ($val == $this->Title) {
			$val = '';
		}
		$this->setField('MetaTitle', $val);
	}

	/**
	 * Ovverride the core MetaTags function to include the Title if we want
	 *
	 * @inheritdoc
	 *
	 * @return string the MetaTags to use on the page
	 */
	public function MetaTags($includeTitle = true) {
		$tags = "";
		//if they want the title include it
		if($includeTitle === true || strtolower($includeTitle) == 'true') {
			$tags .= "<title>" . Convert::raw2xml($this->MetaTitle) . "</title>\n";
		}
		//never include the title as we're doing this ourselves
		return $tags . parent::MetaTags(false);
	}

	/**
	 * Add default records to database.
	 *
	 * This function is called whenever the database is built, after the
	 * database tables have all been created. Overload this to add default
	 * records when the database is built, but make sure you call
	 * parent::requireDefaultRecords().
	 */
	public function requireDefaultRecords() {
		// default pages
		//Make a home page
		$homepage = Page::get()->filter('URLSegment', RootURLController::get_homepage_link())->first();
		if (!$homepage || !$homepage->exists()) {
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
	public static
		$allowed_actions = array (
		);

	public function init() {
		parent::init();
		//allow me to quickly access pages by ID :)
		if (is_numeric($this->request->param('URLSegment')) && $this->request->param('URLSegment') == (int)$this->request->param('URLSegment')) {
			if ($page = DataObject::get_by_id('Page',(int)$this->request->param('URLSegment'))) {
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
	public function getPageLink($pageType,$action = null) {
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
	public function PageLink($pageType) {
		return $this->getPageLink($pageType);
	}

	// relies on requireDefaultRecords
	public function getFooterMenu() {
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
	public function getCopyrightYear($startYear = 2012) {
		$curYear = date('Y');
		if ($curYear > $startYear) {
			return $startYear . ' - ' . $curYear;
		}
		return $startYear;
	}
}
