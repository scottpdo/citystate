<?php get_header(); the_post(); 

if ($_GET['visit'] == 'first') {
	echo '<div id="alert">Welcome to your new city!</div>';
}

if ($_GET['meta'] == 'true') {
	$meta = get_post_custom();
	echo '<div id="alert"><pre>';
	the_meta(); 
	echo '</pre></div>';
}

// Get main map
include 'maps/originalia.php';

// Get all structures
include 'structures.php';

// Get user info
global $current_user;
get_currentuserinfo();
?>

<div id="map" class="clearfix">

	<?php foreach ( range(1,100) as $tile ) { ?>

	<div 
		data-x="<?php $x = fmod($tile, 10); if ($x != 0) { echo $x; } else { echo 10; } ?>" 
		data-y="<?php $y = ceil($tile/10); echo $y; ?>" 
		class="tile <?php 
		foreach($structures as $structure=>$values) {
			include( MAIN .'structures/values.php');

			// Non-repeating 
			if ($max != 0) {
				$loc_x_[$structure] = get_post_meta($post->ID, $structure.'-x', true);
				$loc_y_[$structure] = get_post_meta($post->ID, $structure.'-y', true);
				if ($loc_x_[$structure] == $x && $loc_y_[$structure] == $y) {
					echo $structure.' structure no-build';
				}
			// Repeating
			} else {
				$total = get_post_meta($post->ID, $structure.'s', true);
				for ($i = 1; $i <= $total; $i++) {
					$x_[$structure] = get_post_meta($post->ID, $structure.'-'.$i.'-x', true);
					$y_[$structure] = get_post_meta($post->ID, $structure.'-'.$i.'-y', true);
					if ($x_[$structure] == $x && $y_[$structure] == $y) {
						echo $structure.' structure no-build';
					}
				}
			}
		} ?>"
		<?php foreach($structures as $structure=>$values) {
			include( MAIN .'structures/values.php');

			if ($max != 0) {
				$loc_x_[$structure] = get_post_meta($post->ID, $structure.'-x', true);
				$loc_y_[$structure] = get_post_meta($post->ID, $structure.'-y', true);
				if ($loc_x_[$structure] == $x && $loc_y_[$structure] == $y) {
					echo 'data-structure="'.$structure.'"';
					// Upgradeable if the level is less than max for upgrades
					if ($upgrade > 0) {
						// Cost to upgrade
						echo 'data-cost="'.$cost.'" data-upgrade="true"';
						echo 'data-level="'.get_post_meta($post->ID, $structure.'-level', true).'"';
					} elseif (get_post_meta($post->ID, $structure.'-level', true) == $upgrade) {
							echo 'data-level="'.get_post_meta($post->ID, $structure.'-'.$i.'-level', true).'"';
						}
				}
			} else {
				$total = get_post_meta($post->ID, $structure.'s', true);
				for ($i = 1; $i <= $total; $i++) {
					$x_[$structure] = get_post_meta($post->ID, $structure.'-'.$i.'-x', true);
					$y_[$structure] = get_post_meta($post->ID, $structure.'-'.$i.'-y', true);
					if ($x_[$structure] == $x && $y_[$structure] == $y) {
						echo 'data-structure="'.$structure.'"';
						echo 'data-id="'.$i.'"';
						
						// Upgradeable if the level is less than max for upgrades
						if ($upgrade > 0 && get_post_meta($post->ID, $structure.'-'.$i.'-level', true) < $upgrade) {
							echo 'data-cost="'.$cost.'" data-upgrade="true"';
							echo 'data-level="'.get_post_meta($post->ID, $structure.'-'.$i.'-level', true).'"';
						} elseif (get_post_meta($post->ID, $structure.'-'.$i.'-level', true) == $values[4]) {
							echo 'data-level="'.get_post_meta($post->ID, $structure.'-'.$i.'-level', true).'"';
						}
					}
				}
			}
		} ?>>
	</div>

	<?php } 

	$geo = array('nw', 'n', 'ne', 'w', 'e', 'sw', 's', 'se'); 
	foreach ($geo as $cardinal) { 
		$terrain = get_post_meta(get_the_ID(), 'map-'.$cardinal, true);
		?>

		<div id="<?php echo $cardinal; ?>" class="terrain <?php echo $terrain; ?>"></div>

	<?php }

	// Make sure the user is logged in
	// and built this city to be able to modify
	if (is_user_logged_in() && ($current_user->ID == get_the_author_meta('ID'))) { ?>

	<div><!-- wrapping build and demolish in one div -->
		<div id="build" class="infobox">
			<p>Build a structure at (<span class="x"></span>,&nbsp;<span class="y"></span>):</p>
			<form method="post" action="<?php echo get_permalink().'?structure=build'; ?>">
				<?php 
				foreach ($structures as $structure=>$values) { 
					include( MAIN .'structures/values.php');

					// Non-repeating structures
					if ($max != 0) {	
						// Get location
						$x = get_post_meta($post->ID, $structure.'-x', true);
						$y = get_post_meta($post->ID, $structure.'-y', true);		

						// Only show build option if structure is not yet built
						if ($x == '0' && $y == '0') { ?>
							<input id="<?php echo $structure; ?>" name="build-structure" type="radio" value="<?php echo $structure; ?>" />
							<label><?php echo ucfirst($name).' ('.$cost.')'; ?></label>
						<?php 
						}
					// Repeating structures	
					} else { ?>
						<input id="<?php echo $structure; ?>" name="build-structure" type="radio" value="<?php echo $structure; ?>" />
						<label><?php echo ucfirst($name).' ('.$cost.')'; ?></label>
					<?php 
					}
				} ?>
				<input id="build-x" name="build-x" type="hidden" />
				<input id="build-y" name="build-y" type="hidden" />	
				<input type="submit" value="build" name="update" />
			</form>
		</div>
		<div id="extra" class="infobox">
			<p><span class="name-structure"></span> at (<span class="x"></span>,&nbsp;<span class="y"></span>):</p>
			<form class="upgrade" method="post" action="<?php echo get_permalink().'?structure=upgrade'; ?>">
				<input type="hidden" id="upgrade-structure" name="upgrade-structure" />
				<input id="upgrade-x" name="upgrade-x" type="hidden" />
				<input id="upgrade-y" name="upgrade-y" type="hidden" />
				<input id="upgrade-id" name="upgrade-id" type="hidden" />
				<input type="submit" value="Upgrade" name="update" />
			</form>
			<form method="post" action="<?php echo get_permalink().'?structure=demolish'; ?>">
				<input type="hidden" id="demo-structure" name="demo-structure" />
				<input id="demo-x" name="demo-x" type="hidden" />
				<input id="demo-y" name="demo-y" type="hidden" />
				<input id="demo-id" name="demo-id" type="hidden" />
				<input type="submit" value="Demolish (50)" name="update" />
			</form>
		</div>
	</div>

	<?php } ?>

</div><!-- #map -->

<?php get_footer(); ?>