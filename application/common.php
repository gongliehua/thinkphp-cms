<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// 无限极分类数组排序成二维数组
function sort_two_array($data = [], $parent_id = 0, $level = 0, $clear = false)
{
    static $result = [];
    if ($clear) {
        $result = [];
        return $result;
    }
    foreach ($data as $key=>$value) {
        if ($value['parent_id'] == $parent_id) {
            $value['level'] = $level;
            $result[] = $value;
            sort_two_array($data, $value['id'], $level + 1);
        }
    }
    return $result;
}

// 无限极分类数组排序成树形数组
function sort_multi_array($data = [], $parent_id = 0)
{
    $result = [];
    foreach ($data as $key=>$value) {
        if ($value['parent_id'] == $parent_id) {
            $value['child'] = sort_multi_array($data, $value['id']);
            $result[] = $value;
        }
    }
    return $result;
}

// 树形数组转换成HTML
function multi_array_html($data = [])
{
    $result = '';
    foreach ($data as $key=>$value) {
        if (empty($value['child'])) {
            $className = '';
            $aTag  = '<a href="/'. $value['path'] .'" target="mainFrame"><i class="fa '. $value['icon'] .'"></i> <span>'. $value['title'] .'</span></a>';
        } else {
            $className = 'treeview';
            $aTag  = '<a href="'. $value['path'] .'"><i class="fa '. $value['icon'] .'"></i> <span>'. $value['title'] .'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
        }
        $result .= '<li class="'. $className .'">'.$aTag;
        if (!empty($value['child'])) {
            $result .= '<ul class="treeview-menu" style="display: none;">'. multi_array_html($value['child']) .'</ul>';
        }
        $result .= '</li>';
    }
    return $result;
}

// 加密函数
function setPassword($data, $key)
{
    $key    = md5(md5(md5($key)));
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = "";
    $str    = "";
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l)
        {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

// 解密函数
function getPassword($data, $key)
{
    $data   = base64_decode($data);
    $key    = md5(md5(md5($key)));
    $x      = 0;
    $str    = "";
    for ($i = 0; $i < strlen($data); $i++) {
        if ($x == strlen($key)) $x = 0;
        $str .= chr(ord($data{$i}) - (ord($key{$x})) % 256);
        $x++;
    }
    return $str;
}
