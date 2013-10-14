<?php
class Page extends SiteTree {

	private static
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

		$fields->addFieldToTab(
			'Root.Main.Metadata',
			TextField::create('MetaTitle')
				->setRightTitle('This will be displayed as the title in SERPs as well as the top bar of the browser window or tab'),
			'MetaDescription'
		);

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
			$homepage->URLSegment = RootURLController::get_homepage_link();
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
	private static
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
	 * Copyright Year
	 *
	 * Returns the year or the range of years copyright is held for.
	 *
	 * @return string A string of year(s)
	 */
	public function getCopyrightYear($startYear = 2013) {
		$curYear = date('Y');
		if ($curYear > $startYear) {
			return $startYear . ' - ' . $curYear;
		}
		return $startYear;
	}
}
