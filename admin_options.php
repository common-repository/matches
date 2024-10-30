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
?>
<div class="wrap">
	<h2><?php _e('Matches options', 'matches'); ?></h2>
	<form method="post" action="options.php">
<?php settings_fields('matches-settings-group'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Upcoming matches in list', 'matches'); ?></th>
				<td>
					<select name="matches_upcoming_list_number">
						<option value="0"><?php _e('None'); ?></option>
<?php
	for($i = 1; $i <= 10; $i++) {
		$selected = '';
		if(get_option('matches_upcoming_list_number') == $i)
			$selected = ' selected="selected"';
		echo "\t\t\t\t\t\t<option value='$i'$selected>$i</option>", PHP_EOL;
	}
?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Your team name', 'matches'); ?></th>
				<td><input type="text" name="matches_team_name" value="<?php echo get_option('matches_team_name'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Your team location', 'matches'); ?></th>
				<td><input type="text" name="matches_team_location" value="<?php echo get_option('matches_team_location'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Your team logo', 'matches'); ?></th>
				<td><?php echo bloginfo('wpurl'), '/wp-content/uploads/'; ?><input type="text" name="matches_team_logo" value="<?php echo get_option('matches_team_logo'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Date format', 'matches'); ?></th>
				<td>
					<label><input type="radio" name="matches_date_format" value="wordpress"<?php if('custom' != get_option('matches_date_format')) { echo ' checked'; } ?> /> WordPress setting (<?php echo get_option('date_format') . ' ' . get_option('time_format'); ?>)</label><br />
					<label><input type="radio" name="matches_date_format" value="custom"<?php if('custom' == get_option('matches_date_format')) { echo ' checked'; } ?> /> <?php _e('Custom format: ', 'matches'); ?> <input type="text" name="matches_custom_date_format" value="<?php echo get_option('matches_custom_date_format'); ?>" /> <a href="http://codex.wordpress.org/Formatting_Date_and_Time"><?php _e('Documentation on formatting', 'matches'); ?></a></label><br />
					<label><input type="checkbox" name="matches_date_localize" value="1"<?php if(get_option('matches_date_localize')) { echo ' checked'; } ?> /> <?php _e('Localize time to match visitor timezone', 'matches'); ?></label><br />
					<label>UTC <input type="text" name="matches_date_timezone" size="3" value="<?php if(get_option('matches_date_timezone')) { echo get_option('matches_date_timezone'); } else { echo '0'; } ?>" /> <?php _e('Timezone to convert from for localization (example values: "+1", "-3")', 'matches'); ?></label>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
	</form>
</div>
