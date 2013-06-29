<?php
function set_structure_values($slug, $name, $plural, $values) {
	global $structures;
	$structures[$slug] = array( 
		'slug'    => $slug,
		'name'    => $name, 
		'plural'  => $plural,
		'max'     => $values[0],
		'cost'    => $values[1],
		'target'  => $values[2],
		'upgrade' => $values[3],
		'desired' => $values[4],
		'happy'   => $values[5],
		'culture' => $values[6],
		'edu'     => $values[7]
	);
	return $structures[$slug];
}


function get_structures() {

	$structures = array();

	$structures['park'] = set_structure_values('park', 'park', 'parks', array(0, 50, 50, 0, 200, 1, 2, 0));
	$structures['neighborhood'] = set_structure_values('neighborhood', 'neighborhood', 'neighborhoods', array(0, 100, 100, 2, 0, 0, 0, 0));

	// Education
	$structures['library'] = set_structure_values('library', 'library', 'libraries', array(1, 300, 100, 0, 1000, 3, 5, 15));
	$structures['college'] = set_structure_values('college', 'college', 'colleges', array(1, 1000, 500, 0, 5000, 5, 10, 20));
	$structures['university'] = set_structure_values(  'university',  'university', 'universities', array(1, 2000, 1000, 0, 8000, 8, 20, 30));

	// Culture
	$structures['cinema'] = set_structure_values(      'cinema',       'cinema', 'cinemas', array(1, 450, 200, 0, 2000, 5, 10, 5));
	$structures['stadium'] = set_structure_values(     'stadium',      'stadium', 'stadiums', array(1, 2500, 1000, 0, 12000, 10, 25, 0));

	// Resource
	$structures['farm'] = set_structure_values(        'farm',         'farm', 'farms', array(0, 250, 100));
	$structures['pasture'] = set_structure_values(     'pasture',      'pasture', 'pastures', array(0, 250, 100));
	$structures['lumberyard'] = set_structure_values(  'lumberyard',   'lumberyard', 'lumberyard', array(0, 250, 100));
	$structures['fishery'] = set_structure_values(     'fishery',      'fishery', 'fisheries', array(0, 300, 100));
	$structures['quarry'] = set_structure_values(      'quarry',       'quarry', 'quarries', array(0, 500, 100));
	$structures['coal_mine'] = set_structure_values(   'coal_mine',    'coal mine', 'coal mines', array(0, 500, 100));
	$structures['iron_mine'] = set_structure_values(   'iron_mine',    'iron mine', 'iron mines', array(0, 500, 100));
	$structures['gold_mine'] = set_structure_values(   'gold_mine',    'gold mine', 'gold mines', array(0, 500, 100));
	$structures['uranium_mine'] = set_structure_values('uranium_mine', 'uranium mine', 'uranium mines', array(0, 500, 100));
	$structures['oil_rig'] = set_structure_values(     'oil_rig',      'oil rig', 'oil rigs', array(0, 700, 100));

	// Misc/trade/civic
	$structures['port'] = set_structure_values(        'port',         'port', 'ports', array(6, 800, 150, 0, 4000, 0, 5, 0));
	$structures['city_hall'] = set_structure_values(   'city_hall',    'city hall', 'city halls', array(1, 1000, 0, 0, 10000, 5, 5, 5));
	
	return $structures;
}
?>