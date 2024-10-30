<?php
/*
	Copyright 2010-2012 Anders Mårtensson <anders@nemrod.se>

	This file is part of Matches.

	Matches is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	Matches is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Matches.  If not, see <http://www.gnu.org/licenses/>.
*/

	global $wpdb;

	$date_format = get_option('date_format') . ' ' . get_option('time_format');
	if('custom' == get_option('matches_date_format')) {
		$date_format = get_option('matches_custom_date_format');
	}
	$date_localize = get_option('matches_date_localize');

	$matches_team_name = get_option('matches_team_name');
	$matches_team_location = get_option('matches_team_location');
	$matches_team_logo = get_option('matches_team_logo');
	$matches_upcoming_list_number = get_option('matches_upcoming_list_number');

	$table_name_teams = $wpdb->prefix . 'matches_teams';
	$table_name_matches = $wpdb->prefix . 'matches_matches';

	$next_match = $wpdb->get_row("SELECT $table_name_teams.name AS opponent, $table_name_teams.logo AS opponent_logo, $table_name_matches.location AS location, $table_name_matches.field_override AS field_override, $table_name_matches.time AS time, $table_name_matches.link AS link FROM $table_name_teams, $table_name_matches WHERE $table_name_teams.id = $table_name_matches.versus_team_id AND time > DATE_SUB(NOW(), INTERVAL 1 HOUR) ORDER BY $table_name_matches.time ASC LIMIT 0,1");

	$previous_match = $wpdb->get_row("SELECT $table_name_teams.name AS opponent, $table_name_teams.logo AS opponent_logo, $table_name_matches.location AS location, $table_name_matches.field_override AS field_override, $table_name_matches.time AS time, $table_name_matches.our_score AS our_score, $table_name_matches.opponent_score AS opponent_score, $table_name_matches.link AS link FROM $table_name_teams, $table_name_matches WHERE $table_name_teams.id = $table_name_matches.versus_team_id AND time < DATE_SUB(NOW(), INTERVAL 1 HOUR) ORDER BY $table_name_matches.time DESC LIMIT 0,1");

	if($matches_upcoming_list_number > 0) {
		$matches_list = $wpdb->get_results("SELECT $table_name_teams.name AS opponent, $table_name_matches.location AS location, $table_name_matches.field_override AS field_override, $table_name_matches.time AS time, $table_name_matches.link AS link FROM $table_name_teams, $table_name_matches WHERE $table_name_teams.id = $table_name_matches.versus_team_id AND time > NOW() ORDER BY $table_name_matches.time LIMIT 1, $matches_upcoming_list_number");
	}
?>
<div class="matches">
<?php if($date_localize) { echo "\t", '<script type="text/javascript" src="https://raw.github.com/kvz/phpjs/master/functions/datetime/date.js"></script>', "\n"; } ?>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
		$j(function() {
			$j('div.matches div.previous-match-tab').click(function() {
				$j('div.matches div.next-match').hide();
				$j('div.matches div.previous-match').show();
				$j('div.matches div.next-match-tab').removeClass('current');
				$j('div.matches div.previous-match-tab').addClass('current');
			});
			$j('div.matches div.next-match-tab').click(function() {
				$j('div.matches div.previous-match').hide();
				$j('div.matches div.next-match').show();
				$j('div.matches div.previous-match-tab').removeClass('current');
				$j('div.matches div.next-match-tab').addClass('current');
			});
<?php if($date_localize) { ?>
			//system_timezone_offset = '<?php echo timezone_offset_get(timezone_open(date_default_timezone_get()), new DateTime($next_match->time)); ?>';
			system_timezone_offset = <?php echo intval(get_option('matches_date_timezone')) * 60 * 60; ?>;
			visitor_timezone_offset = new Date().getTimezoneOffset() * -60 - system_timezone_offset;
			$j('.time').each(function(index) {
				system_time = new Date($j(this).html()).getTime() / 1000;
				visitor_time = date('<?php echo $date_format; ?>', system_time + visitor_timezone_offset);
				$j(this).html(visitor_time);
			});
<?php } ?>
		});
	</script>
	<div class="tabs">
		<div class="next-match-tab current"><?php _e('Next match', 'matches'); ?></div>
		<div class="previous-match-tab"><?php _e('Previous match', 'matches'); ?></div>
	</div>
	<div class="matches-widget">
		<div class="next-match">
<?php if($next_match) { ?>
		<div class="team-logos">
<?php
			if(!empty($next_match->link)) { echo '<a href="', $next_match->link, '" target="_blank">'; }
			$team_logo_output = '<span class="team-logo"><img src="' . get_bloginfo('wpurl') . '/wp-content/uploads/' . $matches_team_logo . '" /></span>';
			$opponent_logo_output = '<span class="opponent-logo"><img src="' . get_bloginfo('wpurl') . '/wp-content/uploads/' . $next_match->opponent_logo . '" /></span>';
			if('Home' == $next_match->field_override) {
				echo $team_logo_output;
				echo '<span class="versus"> vs. </span>';
				echo $opponent_logo_output;
			} else if('Away' == $next_match->field_override) {
				echo $opponent_logo_output;
				echo '<span class="versus"> vs. </span>';
				echo $team_logo_output;
			} else {
				echo $matches_team_location == $next_match->location ? $team_logo_output : $opponent_logo_output;
				echo '<span class="versus"> vs. </span>';
				echo $matches_team_location != $next_match->location ? $team_logo_output : $opponent_logo_output;
			}
			if(!empty($next_match->link)) { echo '</a>'; }
?>
		</div>
		<div class="match-info">
			<span class="location"><?php echo $next_match->location; ?></span>
			<span class="info-separator">, </span>
			<span class="match-start time"><?php echo date_i18n($date_format, strtotime($next_match->time)); ?></span>
		</div>
<?php } else { echo '<span class="no-matches">', __('No upcoming matches.', 'matches'), '</span>'; } ?>
		</div>
		<div class="previous-match">
<?php 
	if($previous_match) {
?>
		<div class="team-logos">
<?php
			if(!empty($previous_match->link)) { echo '<a href="', $previous_match->link, '" target="_blank">'; }
			$team_logo_output = '<span class="team-logo"><img src="' . get_bloginfo('wpurl') . '/wp-content/uploads/' . $matches_team_logo . '" /></span>';
			$opponent_logo_output = '<span class="opponent-logo"><img src="' . get_bloginfo('wpurl') . '/wp-content/uploads/' . $previous_match->opponent_logo . '" /></span>';
			if('Home' == $previous_match->field_override) {
				echo $team_logo_output;
				echo '<span class="versus"> vs. </span>';
				echo $opponent_logo_output;
			} else if('Away' == $previous_match->field_override) {
				echo $opponent_logo_output;
				echo '<span class="versus"> vs. </span>';
				echo $team_logo_output;
			} else {
				echo $matches_team_location == $previous_match->location ? $team_logo_output : $opponent_logo_output;
				echo '<span class="versus"> vs. </span>';
				echo $matches_team_location != $previous_match->location ? $team_logo_output : $opponent_logo_output;
			}
			if(!empty($previous_match->link)) { echo '</a>'; }
?>
		</div>
		<div class="team-scores">
<?php
			$team_score_output = '<span class="team-score">' . $previous_match->our_score . '</span>';
			$opponent_score_output = '<span class="opponent-score">' . $previous_match->opponent_score . '</span>';
			if('Home' == $previous_match->field_override) {
				echo $team_score_output;
				echo ' - ';
				echo $opponent_score_output;
			} else if('Away' == $previous_match->field_override) {
				echo $opponent_score_output;
				echo ' - ';
				echo $team_score_output;
			} else {
				echo $matches_team_location == $previous_match->location ? $team_score_output : $opponent_score_output;
				echo ' - ';
				echo $matches_team_location != $previous_match->location ? $team_score_output : $opponent_score_output;
			}
?>
		</div>
		<div class="match-info">
			<span class="location"><?php echo $previous_match->location; ?></span>
			<span class="info-separator">, </span>
			<span class="match-start time"><?php echo date_i18n($date_format, strtotime($previous_match->time)); ?></span>
		</div>
<?php } else { echo '<span class="no-matches">', __('No previous matches.', 'matches'), '</span>'; } ?>
		</div>
	</div>
<?php if($matches_upcoming_list_number > 0) { ?>
	<div class="matches-list">
<?php

	if($matches_list) {
		echo '<table><tr><th colspan="2">', __('Upcoming matches', 'matches'), '</th></tr>';
		foreach($matches_list as $match) {
			if($matches_team_location == $match->location || 'Home' == $match->field_override) { $location = __('h', 'matches'); }
			else { $location = __('a', 'matches'); }
			if(!empty($match->link)) {
				echo '<tr><td class="opponent"><a href="', $match->link, '" target="_blank">', $match->opponent, ' (', $location, ')</a></td><td class="match-start time">', date_i18n($date_format, strtotime($match->time)), '</td></tr>';
			} else {
				echo '<tr><td class="opponent">', $match->opponent, ' (', $location, ')</td><td class="match-start time">', date_i18n($date_format, strtotime($match->time)), '</td></tr>';
			}
		}
		echo '</table>';
	} else
	echo '<span class="no-matches">', __('No upcoming matches.', 'matches'), '</span>';
?>
	</div>
<?php } ?>
</div>
