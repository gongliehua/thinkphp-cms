<?php

namespace app\admin\controller;

use app\common\controller\Backend;

class Index extends Backend
{
	public function index()
	{
		return 'admin';
	}

	public function test()
	{
		return 'test';
	}
}
