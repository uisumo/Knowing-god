<?php
/*
Plugin Name: Post Groups
Plugin URI: http://blog.netsf.org
Description: Adds support for placing posts into separate sub-blogs according to a subject of your choosing (for example, you can create a combination of "Favorite quotations", "Programming stuff", and "Day-to-day ramblings" sub-blogs).
Version: 1.2.0
Author: Catalin Sandu
Author URI: http://blog.netsf.org
*/

/*
	Copyright 2008 (c) Catalin Sandu  (email : scatalin@mailcity.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Add our filters, actions and registration stuff
add_action                ('admin_menu',                 array('PostGroups', 'admin_menu'));
add_action                ('plugins_loaded',             array('PostGroups', 'init_widget'));
add_action                ('dbx_post_sidebar',           array('PostGroups', 'build_groups_sidebar'));
add_action                ('save_post',                  array('PostGroups', 'save_post'));
add_action                ('delete_post',                array('PostGroups', 'delete_post'));
add_action                ('pre_get_posts',              array('PostGroups', 'pre_get_posts'));
add_filter                ('query_vars',                 array('PostGroups', 'query_vars'));
add_filter                ('wp_list_pages',              array('PostGroups', 'wp_list_pages'));
add_filter                ('manage_posts_columns',       array('PostGroups', 'manage_posts_columns'));
add_action                ('manage_posts_custom_column', array('PostGroups', 'manage_posts_custom_column'), 10, 2);
add_filter                ('posts_where',                array('PostGroups', 'where'));
add_filter                ('posts_fields',               array('PostGroups', 'fields'));
add_filter                ('posts_join',                 array('PostGroups', 'join'));
add_filter                ('posts_request',              array('PostGroups', 'request'));
add_filter                ('the_posts',                  array('PostGroups', 'the_posts'));
register_activation_hook  (__FILE__,                     array('PostGroups', 'install'));
register_deactivation_hook(__FILE__,                     array('PostGroups', 'uninstall'));

// Shortcodes
if(function_exists('add_shortcode')) {
	add_shortcode('groupname', array('PostGroups', 'handle_groupname_shortcode'));
}

$requestForBulkAssignment = '';
$postGroupsPluginVersion  = '1.2.0';

// Check if the plugin version is correctly saved in the db.
$dbPostGroupsPluginVersion = get_option('pg_version');

if($postGroupsPluginVersion > $dbPostGroupsPluginVersion)
{
	// When the plugin version stored in this file is newer than what
	// exists in the database, it usually means the plugin was recently 
	// updated. We may need to perform additional stuff in this case,
	// like adding things to the database and so on.
	update_option('pg_version', $postGroupsPluginVersion);
}

class PostGroups {
	/**
	 * handle_groupname_shortcode() - Handler for the [groupname] shortcode.
	 */
	function handle_groupname_shortcode($atts, $content=null) {
		global $wpdb, $post;

		extract(shortcode_atts(array(
			'format' => 'default'
			), $atts));

		$p2pg      = PostGroups::post2postgroup_table();
		$pg        = PostGroups::postgroups_table();
		$groupName = $wpdb->get_var("SELECT groupname FROM $p2pg p2pg INNER JOIN $pg pg ON p2pg.postgroup_id = pg.id WHERE p2pg.post_id = $post->ID");

		if(empty($groupName)) {
			$groupName = get_option('blogname');
		}

		switch($format) {
			case 'lowercase'   : return strtolower($groupName);
			case 'uppercase'   : return strtoupper($groupName);
			case 'titlecase'   : return ucwords($groupName);
			case 'sentencecase': return ucfirst($groupName);
		}

		return $groupName;
	}

	/**
	 * widget_name() - Returns the "Post Groups" widget name
	 */
	function widget_name() {
		return __('Post Groups');
	}

	/**
	 * postgroups_table() - Returns the name of the post groups table
	 */
	function postgroups_table() {
		global $wpdb;
		return $wpdb->prefix . 'postgroups';
	}

	/**
	 * post2postgroup_table() - Returns the name of the table that links posts to post groups
	 */
	function post2postgroup_table() {
		global $wpdb;
		return $wpdb->prefix . 'post2postgroup';
	}

	/**
	 * delete_post() - Deletes the record that stores the group to which the post was linked.
	 */
	function delete_post($pid) {
		global $wpdb;
		$p2pg = PostGroups::post2postgroup_table();
		$wpdb->query("DELETE FROM $p2pg WHERE post_id = $pid");
	}

	/**
	 * edit_sidebar_name() - Gets the name of the Post Groups sidebar
	 *
	 * @return    string    The post groups sidebar name
	 */
	function edit_sidebar_name() {
		return 'PostGroups_group_id';
	}

	/**
	 * save_post() - Saves information concerning the group to which the recently created post belongs.
	 * 
	 * @param    int    $pid     The post/page id which is saved in the database
	 */
	function save_post($pid) {
		global $wpdb;

		$p2pg = PostGroups::post2postgroup_table();
		$wpdb->query("DELETE FROM $p2pg WHERE post_id = $pid");

		$sidebarName  = PostGroups::edit_sidebar_name();
		$postgroup_id = (int)$_POST[$sidebarName];

		if($postgroup_id != '' && $postgroup_id != -1) {
			$wpdb->query("INSERT INTO $p2pg (post_id, postgroup_id) VALUES ($pid, $postgroup_id)");
		}
	}

	/**
	 * query_vars() - Adds the 'group' query variable.
	 *
	 * @param     array    $vars     Array with existing query vars.
	 * @return    array              The same array, plus the 'group' query variable.
	 */
	function query_vars($vars) {
		array_push($vars, 'group');
		return $vars;
	}

	/**
	 * build_groups_sidebar() - Constructs the HTML fragment that represents the "Post Groups" sidebar.
	 */
	function build_groups_sidebar() {
		global $wpdb, $wp_query;

		$pg              = PostGroups::postgroups_table();
		$p2pg            = PostGroups::post2postgroup_table();
		$postID          = (int)($_REQUEST['post']);
		$primaryGroupId  = PostGroups::get_primary_group_id();
		$selectedGroupId = $wpdb->get_var("
				SELECT
					(CASE WHEN (tbl.postgroup_id IS NULL) 
					THEN $primaryGroupId ELSE tbl.postgroup_id END)
				FROM 
					(SELECT SUM(postgroup_id) AS postgroup_id 
					FROM wp_post2postgroup WHERE post_id = $postID) tbl");

		$groups     = $wpdb->get_results("SELECT pg.id, pg.groupname FROM $pg pg ORDER BY pg.grouporder ASC");
		$all_groups = '';
		$groupFound = false;

		if (!empty($groups)) {
			foreach($groups as $group) {
				$all_groups .= "<option value=\"$group->id\"";

				if($selectedGroupId > 0 && $selectedGroupId == $group->id) {
					$all_groups .= ' selected';
					$groupFound = true;
				}

				$groupName = attribute_escape(stripslashes($group->groupname));
				if($group->id == $primaryGroupId) {
					$groupName .= ' ' . __('(primary group)');
				}

				$all_groups .= ">$groupName</option>";
			}
		}

		$sidebarName      = PostGroups::edit_sidebar_name();
		$existing_groups  = "<select id=\"$sidebarName\" name=\"$sidebarName\">";
		$existing_groups .= "<option value=\"-1\"";

		if(false == $groupFound) {
			$existing_groups .= " selected";
		}

		$existing_groups   .= '>-- ' . __('no group') . " --</option>" . $all_groups;
		$existing_groups   .= "</select>";
		$edit_series_title  = __('Post Groups');

		$output = "
			<fieldset id='postGroup_fieldset' class='dbx-box'>
			  <h3 class='dbx-handle'>$edit_series_title</h3>
			  <div class='dbx-content'>
			    $existing_groups
			  </div>
			</fieldset>";

		echo $output;
	}

	/**
	 * init_widget() - Registers the plugin widget in WordPress.
	 */
	function init_widget() {
		if(!function_exists('register_sidebar_widget')) {
			return;
		}

		$widgetName = PostGroups::widget_name();
		register_sidebar_widget($widgetName, array('PostGroups', 'add_widget'));
	}

	/**
	 * group_link() - Returns the HTTP link to be used when building the widget and the links returned by wp_list_pages.
	 *
	 * @param     int    $group     The group ID.
	 */
	function group_link($group) {
		// TODO: return proper structure for permalinks.
		return get_option('home') . "/?group=$group"; 
	}

	/**
	 * add_widget() - Outputs the Post Groups widget.
	 */
	function add_widget($args) {
		global $wpdb;

		if(PostGroups::groups_count() > 0) {
			$pg      = PostGroups::postgroups_table();
			$groups  = $wpdb->get_results("SELECT id, groupname FROM $pg ORDER BY grouporder ASC");

			extract($args);

			$title  = attribute_escape(stripslashes(get_option('pg_groups_header_text')));
			$output = $before_widget . $before_title . $title . $after_title;

			if (!empty($groups)) {
				$output .= "<ul>";
				$showUngroupedPosts = (0 == (int)get_option('pg_hide_ungrouped_posts'));
				if(PostGroups::ungrouped_posts_count() > 0 && $showUngroupedPosts) {
					$ungroupedPostsName = attribute_escape(get_option('pg_ungrouped_posts_name'));
					$groupLink = PostGroups::group_link("ungrouped");
					$output .= "<li><a href=\"$groupLink\" title=\"$ungroupedPostsName\">$ungroupedPostsName</a></li>";
				}
				foreach ($groups as $group) {
					$groupName = attribute_escape(stripslashes($group->groupname));
					$groupLink = PostGroups::group_link($group->id);
					$output   .= "<li><a href=\"$groupLink\" title=\"$groupName\">$groupName</a></li>";
				}
				$output .= "</ul>";
				$output .= $after_widget;
			}
			else {
				$output .= $after_widget;
			}

			echo $output;
		}
	}

	/**
	 * group_name_exists() - Checks if a group name exists.
	 *
	 * @param    string    $groupName           The group name to be checked.
	 * @param    int       $excludedGroupID     Group ID to be excluded when checking for group name existence.
	 *
	 * @return   int                            Returns 0 if the group name does not exist, 1 if it exists.
	 */
	function group_name_exists($groupName, $excludedGroupID) {
		global $wpdb;

		$pg = PostGroups::postgroups_table();
		return $wpdb->get_var("SELECT COUNT(*) AS nameExists FROM $pg WHERE groupname = '$groupName' AND id <> $excludedGroupID");
	}

	/**
	 * groups_count() - Returns the number of defined groups.
	 *
	 * @return    int              Number of defined groups
	 */
	function groups_count() {
		global $wpdb;

		$pg = PostGroups::postgroups_table();
		return $wpdb->get_var("SELECT COUNT(*) FROM $pg");
	}

	/**
	 * group_id() - Returns the group ID.
	 *
	 * Returns the group ID based on the URL query string, POST variable, or,
	 * if these two are not set, based on the post ID (if WordPress is showing 
	 * a single post).  If the query string or POST variable have the group set 
	 * to zero (or "ungrouped"), and if there are posts that don't belong to a 
	 * group, then zero is returned.  Zero is also returned when showing a single 
	 * post which has not assigned to a group.  If none of these condition is 
	 * fulfilled, then -1 is returned.
	 * 
	 * @return    int          The group ID.
	 */
	function group_id() {
		global $wpdb, $wp_query, $post;

		$groupID = -1;

		// Retrieve the group id from _POST.
		if(isset($_POST['group'])) {
			$groupID = (int)$_POST['group'];
		}
		else {
			// Retrieve the group id from the query string or _GET.
			if(isset($wp_query->query_vars['group'])) {
				$groupQueryVar = $wp_query->query_vars['group'];
			}
			else if(isset($_GET['group'])) {
				$groupQueryVar = $_GET['group'];
			}

			if($groupQueryVar == "ungrouped") {
				$groupID = 0;
			}
			else if(((int)$groupQueryVar) > 0) {
				$groupID = (int)$groupQueryVar;
			}
		}

		$ungroupedPostsCount = PostGroups::ungrouped_posts_count();

		if($groupID != -1) {
			if($ungroupedPostsCount > 0 && $groupID == 0) {
				// This is OK: group ID is zero, and we have ungrouped posts.
				return $groupID;
			}

			// Check also if the group really exists.
			$pg     = PostGroups::postgroups_table();
			$result = $wpdb->get_row("SELECT id FROM $pg WHERE id = $groupID LIMIT 1");

			if (!$result) {
				// The group ID does not exist in the database.
				// Set it again to -1 and try to infer the correct
				// group ID after that from $post, but only if the
				// WordPress is displaying a single post.
				$groupID = -1;
			}
		}

		if($groupID == -1 && is_single() && !empty($post)) {
			$p2g    = PostGroups::post2postgroup_table();
			$result = $wpdb->get_row("SELECT postgroup_id FROM $p2g WHERE post_id = $post->ID LIMIT 1");

			if ($result) {
				// We're OK here; the post has been assigned to a group.
				$groupID = $result->postgroup_id;
			}
			else if($ungroupedPostsCount > 0) {
				// This post has not been assigned to a group. Return zero.
				$groupID = 0;
			}
		}

		return $groupID;
	}

	/**
	 * the_posts() - Called by WordPress immediately after the posts are retrieved in get_posts.
	 *
	 * @param     array    $the_posts  All posts retrieved by WordPress.
	 * @return    array                The same array of posts.
	 */
	function the_posts($the_posts) {
		global $wp_query;

		if (!is_admin() && $wp_query->is_home) {
			if(PostGroups::group_id() != -1) {
				$wp_query->is_home = false;
			}
		}

		return $the_posts;
	}

	/**
	 * request() - Called by WordPress before executing the request to retrieve the posts.
	 *
	 * @param     string    $request   The SELECT request to be executed by WordPress.
	 * @return    string               The changed request.
	 */
	function request($request) {
		global $wpdb, $wp_query, $requestForBulkAssignment;

		$groupID = PostGroups::group_id();

		if(is_admin() || (is_home() && $groupID == -1) || is_page()) {
			if(is_admin()) {
				if($_GET['page'] == basename(__FILE__) && $_GET['action'] == 'edit' && $requestForBulkAssignment == '') {
					// This is a request coming from the edit page for a group. This is a dummy request, so that 
					// we can capture the SQL statement used to retrieve all posts from the database. The statement 
					// is saved in the $requestForBulkAssignment variable. The request itself is then changed so that
					// it will return no records.
					$request                   = PostGroups::remove_limits($request);
					$requestForBulkAssignment  = $request;
					$requestForBulkAssignment  = str_ireplace('SQL_CALC_FOUND_ROWS', '', $requestForBulkAssignment);
					$request                  .= ' LIMIT 0, 0';
				}
			}
			else {
				// Change the request.
				$request  = str_ireplace('SQL_CALC_FOUND_ROWS', '', $request);
				$request  = PostGroups::remove_limits($request);
				$request  = "SELECT SQL_CALC_FOUND_ROWS * FROM ($request) $wpdb->posts ";
				$request .= "GROUP BY $wpdb->posts.postgroup_id ";
				$request .= "ORDER BY $wpdb->posts.pgRowNo ASC";
			}
		}

		// Set the @pgRowNo variable which is used in the whole SQL query.
		$wpdb->query('SET @pgRowNo = 0;');

		return $request;
	}

	/**
	 * remove_limits() - Removes the LIMIT clause from a SQL statement, but only if the LIMIT appears last in the request.
	 *
	 * @param     string    $request   The request to be changed.
	 * @return    string               The changed request.
	 */
	function remove_limits($sqlRequest) {
		$newRequest = $sqlRequest;
		$limitPos   = strripos($newRequest, 'LIMIT ');

		if($limitPos === false) {
			// Do nothing when $limitPos is false.
		}
		else { 
			// Strip the LIMITs from the request. When showing the home page for a blog, the plugin 
			// always displays the latest entry from each group as well as the latest entry from
			// the ungrouped posts (if this option is set), all order by date.
			$limitPart = substr($newRequest, $limitPos + strlen('LIMIT '));
			if(preg_match('/((\s*[0-9]+\s*)(,\s*[0-9]+\s*)?){1}/', $limitPart, $matches) == 1) {
				if($matches[0] == $limitPart) {
					$newRequest = substr($newRequest, 0, $limitPos);
				}
			}
		}

		return $newRequest;
	}

	/**
	 * join() - Called by WordPress when building the JOINs SQL clause.
	 *
	 * @param     string    $join   The JOINs so far, as built by WordPress at the time of call.
	 * @return    string            The changed JOINs.
	 */
	function join($join) {
		global $wpdb;

		$showUngroupedPosts = (0 == (int)get_option('pg_hide_ungrouped_posts'));
		$p2pg               = PostGroups::post2postgroup_table();
		$pg                 = PostGroups::postgroups_table();
		$joinType           = ' INNER';

		if($showUngroupedPosts || is_page() || is_admin()) {
			$joinType = ' LEFT';
		}

		$join .= "$joinType JOIN $p2pg ON ($wpdb->posts.ID = $p2pg.post_id)";
		$join .= "$joinType JOIN (SELECT id AS postgroup_id, groupname FROM $pg) $pg ON ($p2pg.postgroup_id = $pg.postgroup_id)";

		return $join;
	}

	/**
	 * fields() - Called by WordPress to allow for customization of the fields list.
	 *
	 * @param     string    $fields   The fields list, as built by WordPress at the time of call.
	 * @return    string              The changed fields list.
	 */
	function fields($fields) {

		$p2pg    = PostGroups::post2postgroup_table();
		$pg      = PostGroups::postgroups_table();
		$fields .= ", $p2pg.postgroup_id, $pg.groupname, (@pgRowNo := @pgRowNo + 1) AS pgRowNo";

		return $fields;
	}

	/**
	 * where() - Constructs the WHERE clause used to retrieve only those posts that belong to a certain group.
	 *
	 * @param     string    $where    The WHERE clause, as built by WordPress at the time of call.
	 * @return    string              The changed WHERE SQL clause.
	 */
	function where($where) {
		global $wpdb;

		if(is_admin()) {
			return $where;
		}

		$p2pg    = PostGroups::post2postgroup_table();
		$groupID = PostGroups::group_id();

		if($groupID != -1) {
			if($groupID == 0) {
				// Get all ungrouped posts.
				$where .= " AND ($p2pg.postgroup_id IS NULL)";
			}
			else {
				// Clause used to retrieve only those posts that belong to a group.
				$where .= " AND ($p2pg.postgroup_id = $groupID)";
			}
		}
		else {
			if(is_page()) {
				$hideUngroupedPosts = (1 == (int)get_option('pg_hide_ungrouped_posts'));
				if($hideUngroupedPosts) {
					$where .= ' AND (';
					$where .= "($wpdb->posts.post_type = 'post' AND $p2pg.post_id IS NOT NULL) OR ";
					$where .= "($wpdb->posts.post_type = 'page' AND $p2pg.post_id IS NULL))";
				}
			}
		}

		return $where;
	}

	/**
	 * pre_get_posts() - Called by WordPress just before the posts are retrieved.
	 */
	function pre_get_posts($query) {
		global $wp_query;

		if(is_admin()) {
			return;
		}

		$makeHome = 
			!(is_single()  || is_search()     || 
			  is_feed()    || is_page()       || 
			  is_archive() || is_attachment() || 
			  is_paged()   || (function_exists('is_tag') && is_tag()));
			  
		if($makeHome) {
			$wp_query->is_home = true;
		}
	}

	/**
	 * install() - Installs the plugin in WordPress
	 */
	function install() {
		global $wpdb, $postGroupsPluginVersion;

		$pg             = PostGroups::postgroups_table();
		$tempDeactivate = false;

		if($wpdb->get_var("SHOW TABLES LIKE '$pg'") != $pg) {
			$sql = "CREATE TABLE $pg (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				groupname tinytext NOT NULL,
				groupdescription varchar(255) DEFAULT '',
				grouporder bigint(20) unsigned NOT NULL DEFAULT 0,
				UNIQUE KEY id (id));";

			require_once(ABSPATH . 'wp-admin/upgrade.php');

			dbDelta($sql);
		}
		else {
			$tempDeactivate = true;
		}

		$p2pg = PostGroups::post2postgroup_table();

		if($wpdb->get_var("SHOW TABLES LIKE '$p2pg'") != $p2pg) {
			$sql = "CREATE TABLE $p2pg ( 
				post_id bigint(20) unsigned NOT NULL, 
				postgroup_id bigint(20) unsigned NOT NULL,
				CONSTRAINT p2pg_post_fk FOREIGN KEY (post_id) REFERENCES $wpdb->posts(ID),
				CONSTRAINT p2pg_postgroups_fk FOREIGN KEY (postgroup_id) REFERENCES $pg(ID));";

			dbDelta($sql);
		}
		else {
			// Add again the foreign key referencing the posts table, but take 
			// care to delete all records that refer to deleted posts.
			$wpdb->query("DELETE FROM $p2pg 
				WHERE post_id NOT IN (SELECT id FROM $wpdb->posts)");
			$wpdb->query("ALTER TABLE $p2pg 
				ADD CONSTRAINT p2pg_post_fk 
				FOREIGN KEY (post_id) REFERENCES $wpdb->posts(ID)");
		}

		if($tempDeactivate == false) {
			// Create plugin options
			add_option('pg_groups_in_wp_list_pages', '1');
			add_option('pg_groups_header_text',      'Groups');
			add_option('pg_ungrouped_posts_name',    'Ungrouped posts');
			add_option('pg_temp_deactivate',         "$tempDeactivate");
			add_option('pg_primary_group_id',        '0');
			add_option('pg_hide_ungrouped_posts',    '0');
		}

		update_option('pg_version', $postGroupsPluginVersion);
	}

	/**
	 * uninstall() - Uninstalls the plugin from WordPress
	 */
	function uninstall() {
		global $wpdb;

		// Check if this is a temp. deactivation.
		$tempDeactivate = (int)get_option('pg_temp_deactivate');
		$p2pg           = PostGroups::post2postgroup_table();

		if($tempDeactivate == 0) {
			if($wpdb->get_var("SHOW TABLES LIKE '$p2pg'") == $p2pg) {
				$wpdb->query("DROP TABLE " . $p2pg . ";");
				$pg = PostGroups::postgroups_table();
				if($wpdb->get_var("SHOW TABLES LIKE '$pg'") == $pg) {
					$wpdb->query("DROP TABLE " . $pg . ";");
				}
			}
		}
		else {
			// Drop the foreign key; at the next activation
			// of this plugin, the foreign key will be recreated.
			$wpdb->query("ALTER TABLE $p2pg 
				DROP FOREIGN KEY p2pg_post_fk");
		}

		if($tempDeactivate == 0) {
			// Also delete our options
			delete_option('pg_version');
			delete_option('pg_groups_in_wp_list_pages');
			delete_option('pg_groups_header_text');
			delete_option('pg_ungrouped_posts_name');
			delete_option('pg_temp_deactivate');
			delete_option('pg_primary_group_id');
			delete_option('pg_hide_ungrouped_posts');
		}
	}

	/**
	 * get_groups_in_wp_list_pages() - Returns the current value for the option_groups_in_wp_list_pages plugin option.
	 */
	function get_groups_in_wp_list_pages() {
		$optionValue = (int)get_option('pg_groups_in_wp_list_pages');

		if($optionValue < 0 || $optionValue > 2) {
			$optionValue = 0;
		}

		return $optionValue;
	}

	/**
	 * get_primary_group_id() - Returns the primary group id.
	 */
	function get_primary_group_id() {
		global $wpdb;

		$optionValue = (int)get_option('pg_primary_group_id');
		$pg          = PostGroups::postgroups_table();
		$groupExists = $wpdb->get_var("SELECT COUNT(*) FROM $pg pg WHERE pg.id = $optionValue");

		if($groupExists == 1) {
			return $optionValue;
		}

		return 0;
	}

	/**
	 * ungrouped_posts_count() - Returns the number of posts that are not yet placed in a group.
	 */
	function ungrouped_posts_count() {
		global $wpdb;

		$p2pg = PostGroups::post2postgroup_table();

		$orExtra = '';
		if (is_user_logged_in()) {
			if(current_user_can("read_private_posts")) {
				$orExtra = "OR post_status = 'private'";
			}
		}

		return $wpdb->get_var("
			SELECT COUNT(*) FROM $wpdb->posts 
			WHERE post_type = 'post' AND (post_status = 'publish' $orExtra)
			AND ID NOT IN (SELECT post_id from $p2pg)");
	}

	/**
	 * manage_posts_columns() - Manages the list of columns shown in the 'manage posts' admin screen.
	 *
	 * @param    array    $defaults    Array with existing columns at the time of call.
	 * @return   array                 Same array, plus an additional column for this plugin.
	 */
	function manage_posts_columns($defaults) {
		$defaults['postgroup'] = __('Group');
		return $defaults;
	}

	/**
	 * manage_posts_custom_column() - Shows the group name in the 'Group' column.
	 *
	 * @param    string    $column_name    The column name being processed.
	 * @param    int       $column_name    The post being shown on the posts table.
	 */
	function manage_posts_custom_column($column_name, $post_id) {
		global $wpdb;
		if($column_name == 'postgroup') {
			$pg    = PostGroups::postgroups_table();
			$p2pg  = PostGroups::post2postgroup_table();
			$group = $wpdb->get_row("SELECT pg.groupname, pg.id FROM $pg pg
				INNER JOIN $p2pg p2pg ON pg.id = p2pg.postgroup_id
				INNER JOIN $wpdb->posts p ON p2pg.post_id = p.id
				WHERE p.id = $post_id LIMIT 1");

			if($group) {
				$groupNameValue = attribute_escape(stripslashes($group->groupname));
				echo "$groupNameValue";
			}
			else {
				$notInAGroup = '&ndash;&nbsp;<i>' . __('not in a group') . '</i>&nbsp;&ndash;';
				_e($notInAGroup);
			}
		}
	}

	/**
	 * wp_list_pages() - Appends a list with all groups to the list of normal WordPress pages.
	 *
	 * @params     string     $output    The HTML containing all WordPress pages.
	 * @return     string                The same HTML, plus a list of all defined groups.
	 */
	function wp_list_pages($output) {
		global $wpdb;

		$optionValue = PostGroups::get_groups_in_wp_list_pages();
		$groupID     = PostGroups::group_id();

		if($optionValue != 0) {
			$pg         = PostGroups::postgroups_table();
			$groups     = $wpdb->get_results("SELECT pg.id, pg.groupname, pg.groupdescription FROM $pg pg ORDER BY pg.grouporder");
			$groupsList = '';

			if($groups)
			{
				$showUngroupedPosts = (0 == (int)get_option('pg_hide_ungrouped_posts'));

				if(PostGroups::ungrouped_posts_count() > 0 && $showUngroupedPosts) {
					// There are also posts that are not yet placed in a group.
					// These posts will go in a default 'Ungrouped' group.
					// This default group has a hard-coded ID set to zero.
					$defaultGroupName = attribute_escape(stripslashes(get_option('pg_ungrouped_posts_name')));
					$groupLink        = PostGroups::group_link("ungrouped");
					$groupsList      .= "\r\n<li class=\"page_item";

					if(0 == $groupID) {
						$groupsList .= " current_page_item";
					}

					$groupsList .= "\">";
					$groupsList .= "<a href=\"$groupLink\" ";
					$groupsList .= "title=\"$defaultGroupName\">$defaultGroupName</a></li>";
				}

				foreach($groups as $group) {
					$groupLink   = PostGroups::group_link($group->id);
					$groupsList .= "\r\n<li class=\"page_item";

					if($group->id == $groupID) {
						// Don't forget to set the 'current_page_item'
						// style on the currently selected group.
						$groupsList .= " current_page_item";
					}

					$groupName   = attribute_escape(stripslashes($group->groupname));
					$groupDesc   = attribute_escape(stripslashes($group->groupdescription));
					if(trim($groupDesc) == '') {
						$groupDesc = $groupName;
					}
					$groupsList .= "\">";
					$groupsList .= "<a href=\"$groupLink\" ";
					$groupsList .= "title=\"$groupDesc\">$groupName</a></li>";
				}
			}

			if($optionValue == 1) {
				return $groupsList . $output;
			}

			return $output . $groupsList;
		}

		return $output;
	}

	/**
	 * admin_menu() - Sets the functions that need to be called when the option and management pages for this plugin are built.
	 */
	function admin_menu() {
		$widgetName = PostGroups::widget_name();

		add_options_page(
			$widgetName,
			$widgetName,
			8,
			basename(__FILE__),
			array('PostGroups', 'options_page')
		);

		add_management_page(
			__('Post Groups'),
			__('Post Groups'),
			8,
			basename(__FILE__),
			array('PostGroups', 'management_page')
		);

		if (function_exists('wp_enqueue_script')) {
			// Add our own javascripts.
			$path_parts   = pathinfo(__FILE__);
			$path         = $path_parts['dirname'];
			$wpContentPos = strpos($path, "wp-content");
			$relPath      = substr($path, $wpContentPos);
			if(DIRECTORY_SEPARATOR == '\\') {
				$relPath = str_replace('\\', '/', $relPath);
			}
			wp_enqueue_script('post_groups_scripts', get_option('home') . "/$relPath/js/functions.js");
		}
	}

	/**
	 * options_page() - Adds content to the plugin options page in WordPress admin.
	 */
	function options_page() {
		$action = '';

		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}

		switch($action) {
			case 'update':
				check_admin_referer('postgroups-update-options');
				PostGroups::update_options();
				break;
			default:
				PostGroups::default_options_page();
				break;
		}
	}

	/**
	 * update_options() - Updates the plugin options.
	 */
	function update_options() {
		global $wpdb;

		$redirectUrl             = "options-general.php?page=" . basename(__FILE__);
		$headerTextValue         = trim($_POST['groups_header_text']);
		$ungroupedPostsNameValue = trim($_POST['ungrouped_posts_name']);

		if($headerTextValue == "") {
			$redirectUrl .= "&message=1";
		}
		else if($ungroupedPostsNameValue == "") {
			$redirectUrl .= "&message=2";
		}
		else
		{
			$wpListPagesOptionValue = (int)$_POST['groups_in_wp_list_pages'];
			if(0 <= $wpListPagesOptionValue && $wpListPagesOptionValue <= 2) {
				update_option('pg_groups_in_wp_list_pages', $wpListPagesOptionValue);
			}

			$tempDeactivationValue   = (isset($_POST['temp_deactivation']) ? '1' : '0');
			$hideUngroupedPostsValue = (isset($_POST['hide_ungrouped_posts']) ? '1' : '0');

			// update_option will take care to escape special SQL characters.
			update_option('pg_groups_header_text',   $headerTextValue);
			update_option('pg_ungrouped_posts_name', $ungroupedPostsNameValue);
			update_option('pg_temp_deactivate',      $tempDeactivationValue);
			update_option('pg_hide_ungrouped_posts', $hideUngroupedPostsValue);

			$redirectUrl .= "&message=3";
		}

		wp_redirect($redirectUrl);
		exit();
	}

	/**
	 * default_options_page() - Shows the options page in the Admin screen.
	 */
	function default_options_page() {
		$selfPage    = 'options-general.php?page=' . basename(__FILE__);
		$messages[1] = __('The sidebar header text cannot be empty.');
		$messages[2] = __('The name for ungrouped posts cannot be empty.');
		$messages[3] = __('The options were updated successfully.');

		if (isset($_GET['message'])) {
			$messageText = $messages[$_GET['message']];
			echo "
				<div id=\"message\" class=\"updated fade\">
					<p>$messageText</p>
				</div>";
		}

		$title                   = __('Post Groups Settings');
		$actionPage              = "$selfPage&action=update";
		$wpListPagesOption       = __('Append groups to the list of pages returned in WordPress by <code>wp_list_pages</code> (e.g. About, Contact)');
		$wpListPagesOptionValue  = get_option('pg_groups_in_wp_list_pages');
		$noValue                 = __("Don't append.");
		$noCheckStatus           = ($wpListPagesOptionValue == 0) ? 'checked' : '';
		$inFrontValue            = __('Append groups in front of the pages list.');
		$inFrontCheckStatus      = ($wpListPagesOptionValue == 1) ? 'checked' : '';
		$atTheBackValue          = __('Append groups at the back of the pages list.');
		$atTheBackCheckStatus    = ($wpListPagesOptionValue == 2) ? 'checked' : '';
		$saveChanges             = __('Save Changes');
		$groupHeaderText         = __('Post Groups widget header text');
		$headerTextValue         = attribute_escape(stripslashes(get_option('pg_groups_header_text')));
		$ungroupedPostsName      = __('Name for ungrouped posts');
		$ungroupedPostsNameValue = attribute_escape(stripslashes(get_option('pg_ungrouped_posts_name')));
		$tempDeactivation        = __('Temporary deactivation');
		$tempDeactivationValue   = ((1 == (int)get_option('pg_temp_deactivate')) ? " checked=\"checked\"" : '');
		$hideUngroupedPostsValue = ((1 == (int)get_option('pg_hide_ungrouped_posts')) ? " checked=\"checked\"" : '');

		$explainPagesOption1        = __("This setting won't affect the list of groups shown in the <code>PostGroups</code> widget.");
		$explainPagesOption2        = __("If you choose not to append the groups to the list of WordPress pages, then your blog theme should support widgets, and the <code>PostGroups</code> widget needs to be added to one of your sidebars.");
		$explainPagesOption3        = __("Note that if the current blog theme is using its own method for retrieving pages (for example, by using <code>get_pages</code> directly instead of <code>wp_list_pages</code>), you will again need to use the <code>PostGroups</code> widget to see the groups.");
		$explainUngroupedPostName1  = __('All posts that are not assigned to a group will go automatically to this group.');
		$explainUngroupedPostName2  = __('Also, when a group is deleted all its posts will be shown under this heading.');
		$explainUngroupedPostName3  = __('Note that this default group appears on your blog only when there are unassigned posts.');
		$explainTempDeactivation1   = __('Check this box if you need to <b>temporary</b> deactivate this plugin in the <a href="plugins.php">Plugin Management</a> page.');
		$explainTempDeactivation2   = __("You may need to do this to detect a plugin that is not working... I hope it's not mine... :-)");
		$explainTempDeactivation3   = __("A temporary deactivation won't delete the groups and posts-to-group associations, will keep the options you set on this page, and a further activation will use them again.");
		$explainTempDeactivation4   = __("To completely clean up any data associated with this plugin at deactivation time, leave this unchecked.");
		$explainHideUngroupedPosts1 = __("Check this box if you don't want to see the default group on your blog.");
		$explainHideUngroupedPosts2 = __("When checked, the default group won't be returned by <code>wp_list_pages</code>, neither will it appear in the <code>PostGroups</code> widget.");
		$explainHideUngroupedPosts3 = __("Note that if you select this option, all your posts not belonging to a group won't appear on your blog at all. However, they can still be accessed via their permalink URL, in feeds, or by searching them.");
		$explainHideUngroupedPosts4 = __("In this case it would be better to <a href=\"edit.php?page=post_groups.php\">set a primary group</a>, so that your future posts will always go in a group should you forget to assign one to a post.");

		echo "
			<div class=\"wrap\">
			<h2>$title</h2>
			<form name=\"groupoptions\" method=\"post\" action=\"$actionPage\">";

		if (function_exists('wp_nonce_field')) {
			wp_nonce_field('postgroups-update-options');
		}

		echo "
			<table class=\"form-table\">
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"groups_in_wp_list_pages\">$wpListPagesOption</label></th>
			<td>
				<p>
					<input name=\"groups_in_wp_list_pages\" type=\"radio\" value=\"0\" class=\"tog\" $noCheckStatus/>
					$noValue
				</p>
				<p>
					<input name=\"groups_in_wp_list_pages\" type=\"radio\" value=\"1\" class=\"tog\" $inFrontCheckStatus/>
					$inFrontValue
				</p>
				<p>
					<input name=\"groups_in_wp_list_pages\" type=\"radio\" value=\"2\" class=\"tog\" $atTheBackCheckStatus/>
					$atTheBackValue
				</p>
				$explainPagesOption1<br/>
				$explainPagesOption2<br/>
				$explainPagesOption3
				<br class=\"clear\"/>&nbsp;
			</td>
			</tr>
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"groups_header_text\">$groupHeaderText</label></th>
			<td>
			<input name=\"groups_header_text\" type=\"text\" id=\"groups_header_text\" value=\"$headerTextValue\" size=\"50\" />
			<br class=\"clear\"/>&nbsp;
			</td>
			</tr>
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"ungrouped_posts_name\">$ungroupedPostsName</label></th>
			<td>
			<input name=\"ungrouped_posts_name\" type=\"text\" id=\"ungrouped_posts_name\" value=\"$ungroupedPostsNameValue\" size=\"50\" /><br/>
			<br/>
			$explainUngroupedPostName1<br/>
			$explainUngroupedPostName2<br/>
			$explainUngroupedPostName3
			<br class=\"clear\"/>&nbsp;<br/>
			<input name=\"hide_ungrouped_posts\" type=\"checkbox\" id=\"hide_ungrouped_posts\" $hideUngroupedPostsValue/>
			$explainHideUngroupedPosts1<br/>
			$explainHideUngroupedPosts2<br/>
			$explainHideUngroupedPosts3<br/>
			$explainHideUngroupedPosts4
			<br class=\"clear\"/>&nbsp;
			</td>
			</tr>
			<tr valign=\"top\">
			<th scope=\"row\"><label for=\"temp_deactivation\">$tempDeactivation</label></th>
			<td>
			<input name=\"temp_deactivation\" type=\"checkbox\" id=\"temp_deactivation\" $tempDeactivationValue/>
			$explainTempDeactivation1<br/>
			$explainTempDeactivation2<br/>
			<span style='color:red;'>$explainTempDeactivation3<br/>
			$explainTempDeactivation4</span>
			<br class=\"clear\"/>&nbsp;
			</td>
			</tr>
			</table>
			<p class=\"submit\">
			<input type=\"submit\" name=\"Submit\" value=\"$saveChanges\" />
			</p>
			</form>
			</div>";
	}

	/**
	 * delete_group() - Deletes one group.
	 */
	function delete_group() {
		global $wpdb;

		$groupID     = PostGroups::group_id();
		$redirectUrl = "edit.php?page=" . basename(__FILE__);

		if ($groupID != -1) {
			check_admin_referer('postgroups-delete-group_' . $groupID);

			$p2pg = PostGroups::post2postgroup_table();
			$wpdb->query('DELETE FROM ' . $p2pg . " WHERE postgroup_id = $groupID");

			$pg = PostGroups::postgroups_table();
			$wpdb->query('DELETE FROM ' . $pg . " WHERE id = $groupID");

			$redirectUrl .= "&message=1";

			if($groupID == PostGroups::get_primary_group_id()) {
				update_option('pg_primary_group_id', 0);
			}
		}

		wp_redirect($redirectUrl);
		exit();
	}

	/**
	 * update_group() - Updates an existing group.
	 */
	function update_group() {
		global $wpdb;

		$groupID     = PostGroups::group_id();
		$redirectUrl = "edit.php?page=" . basename(__FILE__);

		if($groupID != -1) {
			check_admin_referer('postgroups-update-group_' . $groupID);

			$groupName    = $wpdb->escape(trim($_POST['group_name']));
			$groupDesc    = $wpdb->escape(trim($_POST['group_description']));
			$groupOrder   = (int)trim($_POST['group_order']);

			if($groupOrder < 0) {
				$groupOrder = 0;
			}
			if($groupName == '') {
				$redirectUrl .= "&message=2";
			}
			else if(PostGroups::group_name_exists($groupName, $groupID) == 1) {
				$redirectUrl .= "&message=3";
			}
			else {
				$pg = PostGroups::postgroups_table();
				$wpdb->query("UPDATE $pg SET groupname = '$groupName', groupdescription = '$groupDesc', grouporder = $groupOrder WHERE id = $groupID");
				$redirectUrl .= "&message=5";

				$groupPrimaryValue = (int)$_POST['group_primary'];

				if($groupPrimaryValue == 1) {
					update_option('pg_primary_group_id', $groupID);
				}
				else if($groupID == PostGroups::get_primary_group_id()) {
					update_option('pg_primary_group_id', 0);
				}
			}
		}

		wp_redirect($redirectUrl);
		exit();
	}

	/**
	 * add_group() - Adds a new group.
	 */
	function add_group() {
		global $wpdb;

		check_admin_referer('postgroups-add-group'); 

		$redirectUrl = "edit.php?page=". basename(__FILE__);
		$groupName   = $wpdb->escape(trim($_POST['group_name']));
		$groupDesc   = $wpdb->escape(trim($_POST['group_description']));
		$groupOrder  = (int)trim($_POST['group_order']);

		if($groupOrder < 0) {
			$groupOrder = 0;
		}
		if($groupName == '') {
			$redirectUrl .= "&message=2";
		}
		else if(PostGroups::group_name_exists($groupName, -1) == 1) {
			$redirectUrl .= "&message=3";
		}
		else {
			$pg = PostGroups::postgroups_table();
			$wpdb->query("INSERT INTO $pg (groupname, groupdescription, grouporder) VALUE ('$groupName', '$groupDesc', $groupOrder)");
			$redirectUrl .= "&message=4";

			$groupPrimaryValue = (int)$_POST['group_primary'];
			if($groupPrimaryValue == 1) {
				$groupID = $wpdb->get_var("SELECT id FROM $pg WHERE groupname = '$groupName'");
				update_option('pg_primary_group_id', $groupID);
			}
		}

		wp_redirect($redirectUrl);
		exit();
	}

	/**
	 * assign_posts_to_group() - Assigns a series of posts to a group.
	 */
	function assign_posts_to_group() {
		global $wpdb;

		$groupID     = PostGroups::group_id();
		$redirectUrl = 'edit.php?page=' . basename(__FILE__);

		if($groupID != -1) {
			check_admin_referer('postgroups-post-assignments_' . $groupID);

			$pageNo       = (int)$_POST['p2gpage'];
			$redirectUrl .= '&action=edit&group=' . $groupID . '&p2gpage=' . $pageNo;

			if(isset($_POST['post-id'])) {
				$p2pg           = PostGroups::post2postgroup_table();
				$assignedPosts  = '';
				foreach( (array)$_POST['post-id'] as $post_id_assign ) {
					$assignedPosts .= $post_id_assign . ',';
				}
				$assignedPosts .= '-1';

				$wpdb->query("DELETE FROM $p2pg WHERE post_id IN ($assignedPosts)");
				$wpdb->query("INSERT INTO $p2pg SELECT id, $groupID FROM $wpdb->posts WHERE id IN ($assignedPosts)");
			}
		}

		wp_redirect($redirectUrl);
		exit();
	}

	/**
	 * edit_group_form() - Builds the form used to edit groups.
	 */
	function edit_group_form() {
		global $wpdb;

		$groupID  = PostGroups::group_id();
		$selfPage = "edit.php?page=" . basename(__FILE__);

		if($groupID != -1) {
			$pg           = PostGroups::postgroups_table();
			$group        = $wpdb->get_row("SELECT * FROM $pg WHERE id = $groupID");
			$heading      = __('Edit Group Details');
			$submit_text  = __('Update Group Details');
			$actionPage   = "$selfPage&action=editedgroup&group=$groupID";
			$form         = "<form name=\"editgroup\" id=\"editgroup\" method=\"post\" action=\"$actionPage\" class=\"validate\">";
			$nonce_action = 'postgroups-update-group_' . $groupID;
		}
		else {
			$heading      = __('Add Group');
			$submit_text  = __('Add Group');
			$actionPage   = "$selfPage&action=addgroup";
			$form         = "<form name=\"addgroup\" id=\"addgroup\" method=\"post\" action=\"$actionPage\" class=\"add:the-list: validate\">";
			$nonce_action = 'postgroups-add-group';
		}

		$groupNameLabel  = __('Group name');
		$groupNameValue  = attribute_escape(stripslashes($group->groupname));
		$groupDescLabel  = __('Group description (optional)');
		$groupDescValue  = attribute_escape(stripslashes($group->groupdescription));
		$groupOrderLabel = __('Group order');
		$groupOrderValue = $group->grouporder;
		if(!$group->grouporder) {
			$groupOrderValue = 0;
		}
		$groupPrimaryLabel    = __('Primary group');
		$groupPrimaryValueNo  = 'checked';
		$groupPrimaryValueYes = '';

		if($groupID != -1 && $groupID == PostGroups::get_primary_group_id()) {
			$groupPrimaryValueYes = 'checked';
			$groupPrimaryValueNo  = '';
		}

		$groupPrimaryExplain1 = __('Select \'yes\' if you want to set this group as the primary group.');
		$groupPrimaryExplain2 = __('When writing a new post, it will have this group selected by default. You can still change this assignment when writing the post.');

		echo "
			<div class=\"wrap\">
			<h2>$heading</h2>
			<div id=\"ajax-response\"></div>
			$form";

		if (function_exists('wp_nonce_field')) {
			wp_nonce_field($nonce_action);
		}

		$notPrimary = __('No');
		$isPrimary  = __('Yes');

		echo "
			<table class=\"form-table\">
				<tr class=\"form-field form-required\">
					<th scope=\"row\" valign=\"top\"><label for=\"group_name\">$groupNameLabel</label></th>
					<td>
						<input name=\"group_name\" id=\"group_name\" type=\"text\" value=\"$groupNameValue\" size=\"40\" /><br />
					</td>
				</tr>
				<tr class=\"form-field\">
					<th scope=\"row\" valign=\"top\"><label for=\"group_description\">$groupDescLabel</label></th>
					<td>
						<textarea name=\"group_description\" id=\"group_description\" rows=\"5\" cols=\"50\" style=\"width: 97%;\">$groupDescValue</textarea><br />
					</td>
				</tr>
				<tr class=\"form-field\">
					<th scope=\"row\" valign=\"top\"><label for=\"group_order\">$groupOrderLabel</label></th>
					<td>
						<input name=\"group_order\" id=\"group_order\" type=\"text\" value=\"$groupOrderValue\" size=\"40\" /><br />
					</td>
				</tr>
				<tr class=\"form-field\">
					<th scope=\"row\" valign=\"top\"><label for=\"group_primary\">$groupPrimaryLabel</label></th>
					<td>
						<input name=\"group_primary\" id=\"group_primary\" type=\"radio\" value=\"0\" $groupPrimaryValueNo />$notPrimary<br/>
						<input name=\"group_primary\" id=\"group_primary\" type=\"radio\" value=\"1\" $groupPrimaryValueYes />$isPrimary<br/>
						$groupPrimaryExplain1<br />
						$groupPrimaryExplain2<br />
					</td>
				</tr>
			</table>
			<p class=\"submit\"><input type=\"submit\" class=\"button\" name=\"submit\" value=\"$submit_text\" /></p>
			</form>
			</div>";

		if($groupID != -1) {
			echo "<br class='clear'/>";
			PostGroups::posts_to_group_bulk_assignment_form($groupID, $groupNameValue);
		}
	}

	/**
	 * posts_to_group_bulk_assignment_form() - Builds the posts-to-group assignment form.
	 */
	function posts_to_group_bulk_assignment_form($groupID, $groupName) {
		global $wp_query, $wpdb, $requestForBulkAssignment;

		$heading = __('Assign Posts to ');
		echo "<div class=\"wrap\">
			<h2>$heading \"$groupName\"</h2>";

		// Get all posts. This is a dummy request, so that we can capture the SQL statement used to retrieve 
		// all posts from the database. The statement is saved in the $requestForBulkAssignment variable.
		$requestForBulkAssignment = '';
		$wp_query->get_posts();

		$postCount    = $wpdb->get_var("SELECT COUNT(*) FROM ($requestForBulkAssignment) tbl");
		$postsPerPage = 20; // Hardcoded posts-per-page value; may be changed to a plugin option in the future.
		$maxPages     = (int)ceil($postCount / $postsPerPage);

		if(isset($_GET['p2gpage'])) {
			$pageNo = (int)$_GET['p2gpage'];
			if($pageNo < 1) {
				$pageNo = 1;
			}
			else if($pageNo > $maxPages) {
				$pageNo = $maxPages;
			}
		}
		else {
			$pageNo = 1;
		}

		$offsetLimit               = ($pageNo - 1) * $postsPerPage;
		$requestForBulkAssignment .= " LIMIT $offsetLimit, $postsPerPage";
		$pagePosts                 = $wpdb->get_results($requestForBulkAssignment);

		if (!empty($pagePosts)) {
			$postDate      = __('Date');
			$postName      = __('Post name');
			$groupName     = __('Current group');
			$submit_text   = __('Assign Selected Posts To This Group');
			$class         = '';

			// Navigation
			$selfPage      = "edit.php?page=" . basename(__FILE__);
			$navBaseUrl    = "$selfPage&action=edit&group=$groupID&p2gpage=";
			$prevPosts     = __('Previous posts');
			$prevPage      = $pageNo - 1;
			$prevPostsLink = (($pageNo > 1) ? "<a href=\"$navBaseUrl$prevPage\">&laquo;&nbsp;$prevPosts</a>" : '');
			$nextPosts     = __('Next posts');
			$nextPage      = $pageNo + 1;
			$nextPostsLink = (($pageNo < $maxPages) ? "<a href=\"$navBaseUrl$nextPage\">$nextPosts&nbsp;&raquo;</a>" : '');
			$navPostsLink  = '';
			if($prevPostsLink != '' || $nextPostsLink != '') {
				$separator    = (($prevPostsLink != '' && $nextPostsLink != '') ? '&nbsp;|&nbsp;' : '');
				$navPostsLink = "<p align=\"right\">$prevPostsLink$separator$nextPostsLink</p>";
			}

			$actionPage = "$selfPage&action=assign";

			echo "<form id=\"posts-assignments\" action=\"$actionPage\" method=\"post\">";

			$nonce_action = 'postgroups-post-assignments_' . $groupID;
			if (function_exists('wp_nonce_field')) {
				wp_nonce_field($nonce_action);
			}

			echo "
				<input type=\"hidden\" name=\"group\" id=\"group\" value=\"$groupID\"/>
				<input type=\"hidden\" name=\"p2gpage\" id=\"p2gpage\" value=\"$pageNo\"/>
				<table class=\"widefat\">
				<thead>
				<tr>
					<th scope=\"col\" class=\"check-column\" style=\"text-align: center\"><input type=\"checkbox\" id=\"check-all\" name=\"check-all\" onclick=\"setCheckStateAll('posts-assignments', 'check-all', this);\"/></th>
					<th scope=\"col\">$postDate</th>
					<th scope=\"col\">$postName</th>
					<th scope=\"col\">$groupName</th>
				</tr>
				</thead>";

			foreach($pagePosts as $pagePost) {
				$postDate  = mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $pagePost->post_date);
				$postName  = attribute_escape(stripslashes($pagePost->post_title));
				if($pagePost->groupname) {
					$groupName = attribute_escape(stripslashes($pagePost->groupname));
				}
				else {
					$groupName = '<i>&ndash;&nbsp;' . __('not in a group') . '&nbsp;&ndash;</i>';
				}
				$class       = 'alternate' == $class ? '' : 'alternate';
				if($pagePost->post_status == 'private') {
					$private   = __('Private');
					$postName .= " <strong>&mdash; $private</strong>";
				}
				echo "
					<tr class=\"$class\">
						<th scope=\"row\" class=\"check-column\" style=\"text-align: center\"><input type=\"checkbox\" name=\"post-id[]\" value=\"$pagePost->ID\" onclick=\"setCheckStateAll('posts-assignments', 'check-all', this);\"/></th>
						<td>$postDate</td>
						<td>$postName</td>
						<td>$groupName</td>
					</tr>";
			}

			echo "
				</table>
				$navPostsLink
				<p class=\"submit\"><input type=\"submit\" class=\"button\" name=\"submit\" value=\"$submit_text\" /></p>
				</form>";
		}
		else {
			$noPostsForAssignment = __("There are no posts. <a href=\"post-new.php\">Write a new post</a>.");
			echo "<table class=\"widefat\"><tr><td>$noPostsForAssignment</td></tr></table>";
		}

		echo "</div>";
	}

	/**
	 * default_management_page() - Outputs the default options page.
	 */
	function default_management_page() {
		global $wpdb, $wp_query, $class;

		$title        = __('Manage Groups');
		$idCol        = __('ID');
		$orderCol     = __('Order');
		$nameCol      = __('Name');
		$descCol      = __('Description');
		$postsCol     = __('Posts');
		$primaryCol   = __('Primary group?');
		$actionsCol   = __('Actions');
		$editAction   = __('Edit details/Assign posts');
		$deleteAction = __('Delete');
		$output       = '';

		$messages[1] = __('The group was deleted successfully.');
		$messages[2] = __('The group name cannot be empty.');
		$messages[3] = __('The group name already exists.');
		$messages[4] = __('The group has been added successfully.');
		$messages[5] = __('The group has been updated successfully.');

		if (isset($_GET['message'])) {
			$messageText = $messages[$_GET['message']];
			echo "
				<div id=\"message\" class=\"updated fade\">
					<p>$messageText</p>
				</div>";
		}

		echo "
			<div class=\"wrap\">
				<h2>$title (<a href=\"#addgroup\">add new</a>)</h2>
				<table class=\"widefat\">
				<thead>
				<tr>
					<th scope=\"col\" style=\"text-align: center\">$orderCol</th>
					<th scope=\"col\">$nameCol</th>
					<th scope=\"col\">$descCol</th>
					<th scope=\"col\" style=\"text-align: center\">$postsCol</th>
					<th scope=\"col\" style=\"text-align: center\">$primaryCol</th>
					<th colspan=\"2\" style=\"text-align: center\">$actionsCol</th>
				</tr>
				</thead>
				<tbody id=\"the-list\">";

		$pg     = PostGroups::postgroups_table();
		$p2pg   = PostGroups::post2postgroup_table();
		$groups = $wpdb->get_results(
			"SELECT id, groupname, groupdescription, grouporder, SUM(post) as num_posts
			FROM
				(SELECT pg.id, pg.groupname, pg.groupdescription, pg.grouporder, IF(post_id IS NULL, 0, 1) post 
				FROM $pg pg LEFT JOIN $p2pg p2pg ON pg.id = p2pg.postgroup_id) tbl 
			GROUP BY id, groupname, groupdescription, grouporder
			ORDER BY grouporder");

		$selfPage       = "edit.php?page=" . basename(__FILE__);
		$primaryGroupID = PostGroups::get_primary_group_id();

		if (!empty($groups)) {
			$deleteWarning = 
				__("Note that when deleting a group, all posts that were assigned to that  
				group will automatically go to the default group, which will then be 
				made visible on your blog. The name of this default group can be set on the 
				<a href=\"options-general.php?page=post_groups.php\">options page</a>.");

			echo "
				<tr>
				<td colspan=\"7\" style=\"text-align: center\"><i>$deleteWarning</i></td>
				</tr>";

			foreach($groups as $group) {
				$class    = (" class='alternate'" == $class ) ? '' : " class='alternate'";
				$idVal    = $group->id;
				$postsVal = $group->num_posts;
				$nameVal  = attribute_escape(stripslashes($group->groupname));
				$descVal  = attribute_escape(stripslashes($group->groupdescription));
				$orderVal = $group->grouporder;

				$nameNormalized = js_escape(attribute_escape(stripslashes($group->groupname)));

				$deleteUrl  = "$selfPage&action=delete&group=$idVal";
				$deleteUrl  = (function_exists('wp_nonce_url')) ? wp_nonce_url($deleteUrl, 'postgroups-delete-group_' . $idVal) : $deleteUrl;
				$primaryVal = ($primaryGroupID == $idVal) ? __('<strong>yes</strong>') : __('no');

				echo "
					<tr id=\"group-$idVal\"$class>
					<td align=\"center\">$orderVal</th>
					<td><strong>$nameVal</strong></td>
					<td>$descVal</td>
					<td align=\"center\">$postsVal</td>
					<td align=\"center\">$primaryVal</td>
					<td align=\"center\"><a href=\"$selfPage&action=edit&group=$idVal\" class=\"edit\">$editAction</a></td>
					<td align=\"center\"><a href=\"$deleteUrl\" class=\"delete\" onclick=\"return deleteGroup('$nameNormalized');\">$deleteAction</a></td>
					</tr>";
			}
		}
		else {
			$noGroupsMessage = __('No group was defined yet.');
			echo "
				<tr>
				<td colspan=\"7\" style=\"text-align: center\">$noGroupsMessage</td>
				</tr>";
		}

		echo "
				</tbody>
				</table>
			<div class=\"tablenav\">
			<br class=\"clear\" />
			</div>
			</div>
			<br class=\"clear\" />";

		PostGroups::edit_group_form();
	}

	/**
	 * management_page() - Adds content to the plugin management page in WordPress admin.
	 */
	function management_page() {
		$action = '';
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}

		switch($action) {
			case 'delete':
				PostGroups::delete_group();
				break;
			case 'edit':
				echo PostGroups::edit_group_form();
				break;
			case 'assign':
				PostGroups::assign_posts_to_group();
				break;
			case 'addgroup':
				PostGroups::add_group();
				break;
			case 'editedgroup':
				PostGroups::update_group();
				break;
			default:
				PostGroups::default_management_page();
				break;
		}
	}
}
?>