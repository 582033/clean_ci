<?php
class redis_demo extends MY_Controller {
	public function __construct(){	//	{{{
		parent::__construct();
	}	//	}}}
	public function index(){	//{{{
		/**
		 *  $this->redis->command(); //执行redis原生命令
		 *  更多方法可参考作者说明
		 *  https://github.com/joelcox/codeigniter-redis/
		 */
		$this->redis->set('foo', 'bar');
		var_dump($this->redis->get('foo'));
		$this->redis->del('foo');
	}	///}}}
}
