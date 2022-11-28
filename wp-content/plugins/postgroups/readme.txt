=== Post Groups ===
Contributors: csandu
Tags: navigation, pages, posts, postgroups, groups, sub-blogs, multi blog, multiblog, widget, plugin
Requires at least: 2.2.3
Tested up to: 2.5.1
Stable tag: 1.2.0

You can transform a default, single weblog WordPress installation into a multi-blog site with the help of PostGroups.

== Description ==

One feature that I was looking for in WordPress was the ability to place my posts in their own separate pages. Thus, I would have different "sub-blogs" defined for the same WordPress site instead of the single weblog allowed by the default installation. PostGroups is a plugin that allows you to create such groups of posts, and each group can be navigated individually.

For example, you can create a group of posts called "Quotations" where you'll place various citations from authors you like. Another group may be called "My pics", where users may see all your travel photos, and so on.

This might sound similar with using categories. However, browsing all posts that belong to a category is in fact an archive-like browsing; depending on your blog theme, the posts will be shown differently from the normal posts page. For example, the posts may have only their titles shown, while their content may not be displayed at all; the whole listing may have a 'Category Name Archives' heading, which is quite different from what a normal blog home page looks like.

This plugin really splits your blog in different blogs, where each such sub-blog looks the same. And you can still use the same category on posts belonging to different groups. For example, an entry placed in a "Musings" groups may show the world what were your thoughts from your last visit to London, and assign it a, well, "London" category (among others). At a later time you can have another post under the same category, but neatly placed in your "My travel pics" group, where you gather photos only.

### How it works

1. Once activated, you need to define your post groups in the management page. Note that the plugin will have no visible effect on your blog until you won't create at least one such group.
2. Once a group has been created, you can start assigning posts to it in the management page for that group. You can create as many groups as you want... well, as many as it's practical for your blog anyway.
3. On the options page for the PostGroups plugin, you can opt for having your groups shown along with the list of WordPress pages (as returned by the `wp_list_pages` function defined by Wordpress); if your blog theme is using its own method for retrieving these pages, you can still show your groups in the special widget offered by PostGroups.
4. All posts that are not assigned to a group will go by default to a separate "Ungrouped posts" group (this default name can also be changed in the options page). Note that this default group appears on your blog as long as there are posts that are not yet assigned to a group. (You can also opt to have this default group hidden at all times.)
5. You can also assign a post to a group at the moment when that post is written; you don't need to save or publish it and then go to that group management page.
6. If, for whatever reason, you need to temporarily deactivate this plugin, then you can set a special plugin option for this purpose; this will preserve all your groups and post-to-group assignments for a future reactivation.

Please report any issues in the [support forum](http://wordpress.org/tags/postgroups).

== Installation ==

Installing is very easy and it should take no more than a few minutes.

1. Unpack the downloaded file in your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Adjust the plugin options to your liking, then start creating your groups.

If you're upgrading from a previous version, just unpack the new version, overwriting the old plugin files.

== Frequently Asked Questions ==

= Why is my blog home page showing only some of my posts after I create my first post group? =

Once the first group is created, PostGroups will change your blog home page to feature only the latest posts from each group you add to your blog, ordered by their date. If you want to see all posts from a group, you need to navigate to that group from your blog main menu or from the PostGroup widget.

= What if my groups don't appear along my pages on my blog? =

PostGroups will append the groups to the list of pages as returned by the `wp_list_pages` function, should you choose to do so in the options page for the plugin. However, if your theme is using a different method to retrieve and display your pages, then you need to use the provided PostGroups widget to show the groups.

= Why do I see an "Ungrouped posts" group on my blog? I didn't create this group. =

All your posts that are not assigned to an existing group will go to a default group called 'Ungrouped posts'. If at some point you forget or don't want to assign a post to a group, then the plugin will automatically show this default group (the name used by this group can be set in the options page for PostGroups).

== Latest Changes ==

1.2.0 - July 4 - US Independence Day Release

* Added support for WP 2.5.x shortcodes. You can now use `[groupname]` inside a post; it will get replaced with the name of the group to whom the post belongs, or with the blog title if the post is not in a group. Other shortcodes may be added in the future.
* Fix for incompatibility with WP-Sticky.

1.1.0 - July 1 - Canada Day Release

* Added 'hide the ungrouped posts group' option.
* Added 'primary group' option.

== Screenshots ==

1. This is the options page for PostGroups. The effect of each option is fully described here.
2. This is the management page for the plugin.
3. This is the page where you can edit the details for a group, and where you can assign existing posts to a group.
4. You can also assign a post to a group when you're creating that post.