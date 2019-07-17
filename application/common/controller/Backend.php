<?php

namespace app\common\controller;

use think\Controller;

class Backend extends Controller
{
	protected $middleware = ['Auth'];
}
