<?php

// Get ID
$ID = get_the_ID();

// Get structures
include( MAIN .'structures.php');

// Get structure info
$structure = $_POST['build-structure'];
if ($structure == 'stadium') {
	$x = min($_POST['build-x'], $_POST['build-x-1'], $_POST['build-x-2'], $_POST['build-x-3']);
	$y = min($_POST['build-y'], $_POST['build-y-1'], $_POST['build-y-2'], $_POST['build-y-3']);
} else { 
	$x = $_POST['build-x'];
	$y = $_POST['build-y'];
}

$cost = $structures[$structure][3];
$target_increase = $structures[$structure][4];
$happy_increase = $structures[$structure][7];
$cult_increase = $structures[$structure][8];
$edu_increase = $structures[$structure][9];

// Get city population
$pop = get_post_meta($ID, 'population', true);

// Get user info
global $current_user;
get_currentuserinfo();
$cash_current = get_user_meta($current_user->ID, 'cash', true);

// Make sure we're not bankrupting, then proceed
if (($cash_current - $cost) < 0) {
	$alert = '<p>You can&#39;t do that &mdash; you&#39;d go bankrupt!</p>';
} else {

	// Take cash from user
	update_user_meta($current_user->ID, 'cash', $cash_current - $cost);
	
	// Set location for non-repeating
	if ($structures[$structure][2] == 1) {
		update_post_meta($ID, $structure.'-x', $x);
		update_post_meta($ID, $structure.'-y', $y);

		// Update target population
		$target_current = get_post_meta($ID, 'target-pop', true);
		update_post_meta($ID, 'target-pop', $target_current + $target_increase);

		// Update happiness, culture, education
		$happy = get_post_meta($ID, 'happiness', true);
		update_post_meta($ID, 'happiness', $happy + round($happy_increase - $happy_increase * $happy/100, 3));
		
		$culture = get_post_meta($ID, 'culture', true);
		update_post_meta($ID, 'culture', $culture + round($cult_increase - $cult_increase * $culture/100, 3));

		$edu = get_post_meta($ID, 'education', true);
		update_post_meta($ID, 'education', $edu + round($edu_increase - $edu_increase * $edu/100, 3));

		// Update funding
		$funding = round(0.02 * $cost * (1 + 0.1 * (($pop - $structures[$structure][6]) / 1000) ));
		if ($funding < 1) {
			$funding = 'bad';
		} elseif ($funding < 1.5) {
			$funding = 'fair';
		} elseif ($funding < 5) {
			$funding = 'good';
		} elseif ($funding >= 5) {
			$funding = 'excellent';
		}
		update_post_meta($ID, $structure.'-funding', $funding);

	// Set location for repeating
	} else {
		$num = get_post_meta($ID, $structure.'s', true);
		$new = $num + 1;
		
		// Add location of new structure
		add_post_meta($ID, $structure.'-'.$new.'-x', $x);
		add_post_meta($ID, $structure.'-'.$new.'-y', $y);

		// Update total number
		update_post_meta($ID, $structure.'s', $new);

		// Update target population
		$target_current = get_post_meta($ID, 'target-pop', true);
		update_post_meta($ID, 'target-pop', $target_current + $target_increase);

		// Update happiness, culture, education
		$happy = get_post_meta($ID, 'happiness', true);
		update_post_meta($ID, 'happiness', $happy + round($happy_increase - $happy_increase * $happy/100, 3));
		
		$culture = get_post_meta($ID, 'culture', true);
		update_post_meta($ID, 'culture', $culture + round($cult_increase - $cult_increase * $culture/100, 3));

		$edu = get_post_meta($ID, 'education', true);
		update_post_meta($ID, 'education', $edu + round($edu_increase - $edu_increase * $edu/100, 3));

		// Update population for residential types
		if ($structure == 'neighborhood') {
			update_post_meta($ID, 'population', $pop + 20);
		}

		// Update funding
		$funding = round(0.02 * $new * $cost * (1 + 0.1 * (($pop - $structures[$structure][6]) / 1000) ));
		if ($funding < 1) {
			$funding = 'bad';
		} elseif ($funding < 1.5) {
			$funding = 'fair';
		} elseif ($funding < 5) {
			$funding = 'good';
		} elseif ($funding >= 5) {
			$funding = 'excellent';
		}
		update_post_meta($ID, $structure.'-funding', $funding);
	}

	// Update the activity log. The output:
	$site_url = home_url();
	$link = get_permalink();
	$city = get_the_title();
	$output = 'A '.$structures[$structure][0].' was built in <a href="'.$link.'">'.$city.'</a> by <a href="'.$site_url.'/user/'.$current_user->user_login.'">'.$current_user->display_name.'</a>.';

	// Query the latest activity date
	$args = array(
				'post_type' => 'activity',
				'posts_per_page' => 1
			);
	$a_query = new WP_Query($args); 
	while ($a_query->have_posts()) : 
	$a_query->the_post(); 
	
	// Central time!
	date_default_timezone_set('America/Chicago');

	// Check to see if it's the same day as most recent activity
	if (date('Ymd') == get_the_date('Ymd')) {
		$already = get_post_meta(get_the_ID(), $current_user->user_login.'-'.$city.'-build-'.$structure, true);
		if ($already > 0) {
			$new = $already + 1;
			$output = $new.' '.$structures[$structure][1].' were built in <a href="'.$link.'">'.$city.'</a> by <a href="'.$site_url.'/user/'.$current_user->user_login.'">'.$current_user->display_name.'</a>.';
			delete_post_meta(get_the_ID(), 'activity', 'A '.$structures[$structure][0].' was built in <a href="'.$link.'">'.$city.'</a> by <a href="'.$site_url.'/user/'.$current_user->user_login.'">'.$current_user->display_name.'</a>.');
			delete_post_meta(get_the_ID(), 'activity', $already.' '.$structures[$structure][1].' were built in <a href="'.$link.'">'.$city.'</a> by <a href="'.$site_url.'/user/'.$current_user->user_login.'">'.$current_user->display_name.'</a>.');
			update_post_meta(get_the_ID(), $current_user->user_login.'-'.$city.'-build-'.$structure, $new);
		} else {
			add_post_meta(get_the_ID(), $current_user->user_login.'-'.$city.'-build-'.$structure, 1);
		}
		add_post_meta(get_the_ID(), 'activity', $output);
	// If not, add a new activity entry
	} else {
		$activity_ID = wp_insert_post(array(
			'post_type' => 'activity',
			'post_title' => date('M j, Y'),
			'post_content' => $output,
			'post_status' => 'publish',
			)
		);
		add_post_meta($activity_ID, 'activity', $output);
		add_post_meta(get_the_ID(), $current_user->user_login.'-'.$city.'-build-'.$structure, 1);
	}
	endwhile;
	wp_reset_postdata();

}

?>