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

	/* TODO:
		* Pagination
	*/
	global $wpdb;

	$table_name_teams = $wpdb->prefix . 'matches_teams';
	$table_name_matches = $wpdb->prefix . 'matches_matches';

	$wpdb->show_errors();

	if(isset($_POST['Submit'])) {
		switch($_POST['action']) {
			case 'add':
				$wpdb->insert($table_name_matches, array('time' => $_POST['time'], 'versus_team_id' => $_POST['versus_team_id'], 'location' => $_POST['location'], 'field_override' => $_POST['field_override'], 'our_score' => $_POST['our_score'], 'opponent_score' => $_POST['opponent_score'], 'link' => $_POST['link']));
				break;
			case 'delete':
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name_matches WHERE id=%d", $_POST['id']));
				break;
			case 'edit':
				if(isset($_POST['time'])) {
					$wpdb->update($table_name_matches, array('time' => $_POST['time'], 'versus_team_id' => $_POST['versus_team_id'], 'location' => $_POST['location'], 'field_override' => $_POST['field_override'], 'our_score' => $_POST['our_score'], 'opponent_score' => $_POST['opponent_score'], 'link' => $_POST['link']), array('id' => $_POST['id']));
				} else {
					$edit = true;
				}
				break;
		}
	}
?>
<div class="wrap">
	<h2><?php _e('Manage matches', 'matches'); ?></h2>
	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr class="thead">
				<th scope="col" class="manage-column"><?php _e('Time', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Location', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Field override', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Opponent', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Your score', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Opponents score', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Link', 'matches'); ?></th>
				<th scope="col" class="manage-column"></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="manage-column"><?php _e('Time', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Location', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Field override', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Opponent', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Your score', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Opponents score', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Link', 'matches'); ?></th>
				<th scope="col" class="manage-column"></th>
			</tr>
		</tfoot>
		<tbody>
<?php
	$matches = $wpdb->get_results("SELECT $table_name_matches.id AS id, $table_name_teams.name AS opponent, $table_name_matches.time AS time, $table_name_matches.location AS location, $table_name_matches.field_override AS field_override, $table_name_matches.our_score AS our_score, $table_name_matches.opponent_score AS opponent_score, $table_name_matches.link AS link FROM $table_name_matches, $table_name_teams WHERE $table_name_teams.id = $table_name_matches.versus_team_id ORDER BY $table_name_matches.time");
	foreach($matches as $match) {
?>
			<tr<?php if($i) { $i = 0; } else { echo ' class="alternate"'; $i = 1; } ?>>
				<th scope="row"><?php echo $match->time; ?></th>
				<td><?php echo $match->location; ?></td>
				<td><?php _e($match->field_override, 'matches'); ?></td>
				<td><?php echo $match->opponent; ?></td>
				<td><?php echo $match->our_score; ?></td>
				<td><?php echo $match->opponent_score; ?></td>
				<td><a href='<?php echo $match->link; ?>'><?php echo $match->link; ?></a></td>
				<td class="submit">
					<form method="post" action="" style="display: inline;">
						<input type="hidden" name="action" value="edit" />
						<input type="hidden" name="id" value="<?php echo $match->id; ?>" />
						<input type="submit" name="Submit" value="<?php _e('Edit'); ?>" />
					</form>
					<form method="post" action="" style="display: inline;">
						<input type="hidden" name="action" value="delete" />
						<input type="hidden" name="id" value="<?php echo $match->id; ?>" />
						<input type="submit" name="Submit" value="<?php _e('Delete'); ?>" />
					</form>
				</td>
			</tr>
<?php } ?>
			<form method="post" action="">
				<tr>
<?php
	if(!isset($edit) || !$edit) {
		$teams = $wpdb->get_results("SELECT id, name FROM $table_name_teams");
		$teams_options = '';
		foreach($teams as $team) {
			$teams_options .= "\t\t\t\t\t<option value='$team->id'>$team->name</option>\n";
		}
?>
					<td><input name="time" type="text" id="time" placeholder="<?php _e('0000-00-00 00:00:00', 'matches'); ?>" class="regular-text" /></td>
					<td><input name="location" type="text" id="location" placeholder="<?php _e('Location', 'matches'); ?>" class="regular-text" /></td>
					<td>
						<select name="field_override" id="field_override">
							<option value=""><?php _e('Don\'t override', 'matches'); ?></option>
							<option value="Home"><?php _e('Home', 'matches'); ?></option>
							<option value="Away"><?php _e('Away', 'matches'); ?></option>
						</select>
					</td>
					<td>
						<select name="versus_team_id" id="versus_team_id">
<?php echo $teams_options; ?>
						</select>
					</td>
					<td><input name="our_score" type="text" id="our_score" placeholder="<?php _e('Your score', 'matches'); ?>" class="regular-text" /></td>
					<td><input name="opponent_score" type="text" id="opponent_score" placeholder="<?php _e('Opponents score', 'matches'); ?>" class="regular-text" /></td>
					<td><input name="link" type="text" id="link" placeholder="<?php _e('Link', 'matches'); ?>" class="regular-text" /></td>
					<td class="submit">
						<input type="hidden" name="action" value="add" />
						<input type="submit" name="Submit" value="<?php _e('Add'); ?>" />
					</td>
<?php
	} else {
		$match = $wpdb->get_row($wpdb->prepare("SELECT id, time, location, field_override, versus_team_id, our_score, opponent_score, link FROM $table_name_matches WHERE id=%d", $_POST['id']));
		$teams = $wpdb->get_results("SELECT id, name FROM $table_name_teams");
		$teams_options = '';
		foreach($teams as $team) {
			$selected = false;
			if($edit && $team->id == $match->versus_team_id)
				$selected = true;
			$teams_options .= "\t\t\t\t\t<option value='$team->id'" . ($selected ? ' selected="selected"' : '') . ">$team->name</option>\n";
		}
?>
					<td><input name="time" type="text" id="time" value="<?php echo $match->time; ?>" class="regular-text" /></td>
					<td><input name="location" type="text" id="location" value="<?php echo $match->location; ?>" class="regular-text" /></td>
					<td>
						<select name="field_override" id="field_override">
							<option value=""><?php _e('Don\'t override', 'matches'); ?></option>
							<option value="Home"<?php if('Home' == $match->field_override) { echo ' selected'; } ?>><?php _e('Home', 'matches'); ?></option>
							<option value="Away"<?php if('Away' == $match->field_override) { echo ' selected'; } ?>><?php _e('Away', 'matches'); ?></option>
						</select>
					</td>
					<td>
						<select name="versus_team_id" id="versus_team_id">
<?php echo $teams_options; ?>
						</select>
					</td>
					<td><input name="our_score" type="text" id="our_score" value="<?php echo $match->our_score; ?>" class="regular-text" /></td>
					<td><input name="opponent_score" type="text" id="opponent_score" value="<?php echo $match->opponent_score; ?>" class="regular-text" /></td>
					<td><input name="link" type="text" id="link" value="<?php echo $match->link; ?>" class="regular-text" /></td>
					<td class="submit">
						<input type="hidden" name="action" value="edit" />
						<input type="hidden" name="id" value="<?php echo $match->id; ?>" />
						<input type="submit" name="Submit" value="<?php _e('Save'); ?>" />
					</td>
<?php } ?>
				</tr>
			</form>
		</tbody>
	</table>
</div>
