<?php
class Page extends SiteTree {

	private static
		$db = array(
		),
		$has_one = array(
		);

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
		$homepage = Page::get()->filter('URLSegment', 'home')->first();
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
	public function getCopyrightYear($startYear = 2012) {
		$curYear = date('Y');
		if ($curYear > $startYear) {
			return $startYear . ' - ' . $curYear;
		}
		return $startYear;
	}
}
