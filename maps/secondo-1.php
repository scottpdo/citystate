<?php

$map = array(
	/* 
	row number => array(value for each tile: 0 water, 1 land)
	 */
	1  => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	2  => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	3  => array(0, 0, 0, 0, 0, 0, 0, 1, 1, 0),
	4  => array(0, 0, 0, 0, 0, 0, 1, 1, 1, 1),
	5  => array(0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
	6  => array(0, 0, 0, 0, 1, 1, 0, 0, 0, 1),
	7  => array(0, 0, 1, 1, 1, 0, 0, 0, 0, 0),
	8  => array(0, 0, 0, 1, 1, 1, 0, 0, 0, 0),
	9  => array(0, 0, 0, 1, 1, 1, 0, 0, 1, 0),
	10 => array(0, 1, 1, 1, 1, 1, 1, 1, 1, 0),
);
$neighbors = array(
	'nw' => 0,
	'n'  => 0,
	'ne' => 0,
	'w'  => 'originalia',
	'e'  => 'secondo-2',
	'sw' => 0,
	's'  => 'secondo-3',
	'se' => 'secondo-4',
);

?>