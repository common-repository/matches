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

	$table_name_teams = $wpdb->prefix . 'matches_teams';
	$table_name_matches = $wpdb->prefix . 'matches_matches';

	if(isset($_POST['Submit'])) {
		switch($_POST['action']) {
			case 'add':
				$wpdb->insert($table_name_teams, array('name' => $_POST['name'], 'logo' => $_POST['logo']));
				break;
			case 'delete':
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name_matches WHERE versus_team_id=%d", $_POST['id']));
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name_teams WHERE id=%d", $_POST['id']));
				break;
			case 'edit':
				if(isset($_POST['name'])) {
					$wpdb->update($table_name_teams, array('name' => $_POST['name'], 'logo' => $_POST['logo']), array('id' => $_POST['id']));
				} else {
					$edit = true;
				}
				break;
		}
	}
?>
<div class="wrap">
	<h2><?php _e('Manage teams', 'matches'); ?></h2>
	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr class="thead">
				<th scope="col" class="manage-column"><?php _e('Name', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Logo', 'matches'); ?></th>
				<th scope="col" class="manage-column"></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="thead">
				<th scope="col" class="manage-column"><?php _e('Name', 'matches'); ?></th>
				<th scope="col" class="manage-column"><?php _e('Logo', 'matches'); ?></th>
				<th scope="col" class="manage-column"></th>
			</tr>
		</tfoot>
		<tbody>
<?php
	$teams = $wpdb->get_results("SELECT id, name, logo FROM $table_name_teams");
	foreach($teams as $team) {
		$logo = get_bloginfo('wpurl') . '/wp-content/uploads/' . $team->logo;
?>
			<tr<?php if($i) { $i = 0; } else { echo ' class="alternate"'; $i = 1; } ?>>
				<th scope="row">
					<img src='<?php echo $logo; ?>' width='20px' />
					<?php echo $team->name; ?>
				</th>
				<td><?php echo $logo; ?></td>
				<td class="submit">
					<form method="post" action="" style="display: inline;">
						<input type="hidden" name="action" value="edit" />
						<input type="hidden" name="id" value="<?php echo $team->id; ?>" />
						<input type="submit" name="Submit" value="<?php _e('Edit'); ?>" />
					</form>
					<form method="post" action="" style="display: inline;">
						<input type="hidden" name="action" value="delete" />
						<input type="hidden" name="id" value="<?php echo $team->id; ?>" />
						<input type="submit" name="Submit" value="<?php _e('Delete'); ?>" />
					</form>
				</td>
			</tr>
<?php } ?>
			<form method="post" action="">
				<tr>
<?php if(!$edit) { ?>
					<td><input name="name" type="text" id="name" placeholder="<?php _e('Team name', 'matches'); ?>" class="regular-text" /></td>
					<td><?php echo bloginfo('wpurl'), '/wp-content/uploads/'; ?><input name="logo" type="text" id="logo" placeholder="<?php _e('Logo URL', 'matches'); ?>" class="regular-text" /></td>
					<td class="submit">
						<input type="hidden" name="action" value="add" />
						<input type="submit" name="Submit" value="<?php _e('Add'); ?>" />
					</td>
<?php
	} else {
		$team = $wpdb->get_row($wpdb->prepare("SELECT id, name, logo FROM $table_name_teams WHERE id=%d", $_POST['id']));
?>
					<td><input name="name" type="text" id="name" value="<?php echo $team->name; ?>" class="regular-text" /></td>
					<td><?php echo bloginfo('wpurl'), '/wp-content/uploads/'; ?><input name="logo" type="text" id="logo" value="<?php echo $team->logo; ?>" class="regular-text" /></td>
					<td class="submit">
						<input type="hidden" name="action" value="edit" />
						<input type="hidden" name="id" value="<?php echo $team->id; ?>" />
						<input type="submit" name="Submit" value="<?php _e('Save'); ?>" />
					</td>
<?php } ?>
				</tr>
			</form>
		</tbody>
	</table>
</div>
