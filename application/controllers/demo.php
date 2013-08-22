<?php
class Demo extends MY_Controller {
	public function __construct(){	//	{{{
		parent::__construct();
	}	//	}}}
	public function index(){	//{{{
		echo $this->demolib->foo();
	}	///}}}
}
