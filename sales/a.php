<?php
$items = array(
    1 => array('id' => 1, 'pid' => 0, 'name' => '安徽省'),
    2 => array('id' => 2, 'pid' => 0, 'name' => '浙江省'),
    3 => array('id' => 3, 'pid' => 1, 'name' => '合肥市'),
    4 => array('id' => 4, 'pid' => 3, 'name' => '长丰县'),
    5 => array('id' => 5, 'pid' => 1, 'name' => '安庆市'),
);
/*
function generateTree($items){
    foreach($items as $item)
        $items[$item['pid']]['son'][$item['id']] = &$items[$item['id']];
    return isset($items[0]['son']) ? $items[0]['son'] : array();
}
*/
function generateTree($items){
    $tree = array();
    foreach($items as $item){
        if(isset($items[$item['pid']])){
            $items[$item['pid']]['son'][] = &$items[$item['id']];
        }else{
            $tree[] = &$items[$item['id']];
        }
    }
    return $tree;
}
//print_r(generateTree($items));


function generateTree2($items){
    foreach($items as $item)
        $items[$item['pid']]['son'][$item['id']] = &$items[$item['id']];
    return isset($items[0]['son']) ? $items[0]['son'] : array();
}
$tree = generateTree($items);
var_dump($tree);
/*
function getTreeData($tree){
    foreach($tree as $t){
        echo $t['name']."\n";
        if(isset($t['son'])){
            getTreeData($t['son']);
        }
    }
}*/
function getTreeData($tree){
	static $html;
    $html .= "<ul>";
    foreach($tree as $k => $v){
        $html .= "<li><a href=\"#\" ref=\"$k\">" . $v['name'] . "</a></li>";
        if(isset($v['son'])){
            getTreeData($v['son']);
        }
    }
    $html .= "</ul>";
  	return $html;
}
$a = getTreeData($tree);
var_dump($a);
