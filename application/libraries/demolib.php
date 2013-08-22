<?php 
class Demolib{
	function __construct(){	//{{{
		$this->obj = & get_instance();
	}	//}}}
	public function foo() {	//{{{
		return $this->obj->demo_model->test();
	}	//}}}
}
?>
