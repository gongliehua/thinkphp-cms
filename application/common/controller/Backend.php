<?php

namespace app\common\controller;

use think\Controller;

/*
 * 后台模块基类
 */
class Backend extends Controller
{
	protected $middleware = ['Admin'];
}
