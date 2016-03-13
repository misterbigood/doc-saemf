=== Oasis Workflow ===
Contributors: nuggetsol
Tags: workflow, work flow, review, assignment, publish, inbox, workflow history, audit,versioning, auto submit, approval workflow, editorial workflow, notifications, oasis workflow, editorial, revisions, document revision, version control, collaboration, document management, revision scheduling, duplication, clone, revise, revise article
Requires at least: 3.6
Tested up to: 4.4
Stable tag: 1.9

Automate your WordPress Editorial Workflow with Oasis Workflow. Simple, intuitive drag and drop workflow builder to streamline your editorial process.

== Description ==

Oasis Workflow is a powerful feature rich plugin designed to automate any editorial workflow process using a simple, intuitive graphical user interface (GUI).

= The plugin provides three simple process/task templates: =
* Assignment - represents task related to content generation.
* Review - represents task related to content review.
* Publish - represents the actual "publish" task.

**Visual Work flow Designer**
 - Configure your workflow using our easy drag and drop designer interface. See screen shots for more detail.

**Role-Based routing definitions allow you to assign tasks dynamically**
 - By using role-based routing, you can ensure that your process moves forward as quickly as possible without sacrificing accountability.

**Inbox**
 - Users can view their current assignments and sign off their tasks once it's completed.

**Process history lets users retrace their steps**
 - For auditing purposes a record is created every time an article is routed through a workflow process. The process history also captures the comments added by the user during task sign off.

**Reassign - How to pass the buck?**
 - What if you have been assigned a workflow task, but you feel you are not the appropriate person to complete it? No worry, you can use the re-assign feature to pass the task to another person. 

**Due Date and Email reminders** help you to publish your articles on time.

**Out of the box workflow**
- To get you started, the plugin comes with an out of the box workflow. You can modify the workflow to suit your needs. 

**If you are looking for additional functionality, check out our "Pro" version - Oasis Workflow Pro: https://www.oasisworkflow.com/pricing-purchase**
**It comes with additional features like,** 

* Multiple Workflows - Allows you to create multiple workflows.
* [Auto Submit](http://www.oasisworkflow.com/auto-submit-to-workflow) - Allows you to automatically submit to workflow(s) with certain conditions.
* [Revise published content and add workflow support to revised content](http://www.oasisworkflow.com/workflow-support-for-updating-published-content) - Use workflow to edit your published content while keeping the published article online.
* And add-ons, like "Editorial Contextual Comments", "Teams", "Front End shortcodes"
* And much more.. 

If you want to try out the "Pro" features, send us a message at [Request a Demo Site](https://www.oasisworkflow.com/request-a-demo-site) and we will setup a demo site for you to try out the "Pro" features. As a bonus, we will also add our add-ons to the demo site. 
More details for each feature, screenshots and documentation can be found on [our website](http://www.oasisworkflow.com/).

= Supported languages =
* English
* Spanish
* French
* Italian
* Swedish
 
= Translators =
* French (fr_FR) - [Baptiste Rieg](http://www.batrieg.com)
* Italian (it_IT) - [Martino Stenta](https://profiles.wordpress.org/molokom)
* Swedish (sv_SE) - Norbert Kustra

If you need help setting up the roles, we recommend [Capability Manager Enhanced plugin](https://wordpress.org/plugins/capability-manager-enhanced/ "Capability Manager Enhanced plugin") OR [User Role Editor plugin](http://wordpress.org/extend/plugins/user-role-editor/ "User Role Editor plugin").

= Videos to help you get started with Oasis Workflow =

Editing the Out of the box workflow (applicable to both the "free" and "pro" version)

[youtube https://www.youtube.com/watch?v=TLWrjTvsTRs]

How it works? Understand the process (applicable to both the "free" and "pro" version)

[youtube https://www.youtube.com/watch?v=_R2uVWQicsM]

Modify a workflow which has posts/pages currently in progress (applicable to both the "free" and "pro" version)

[youtube https://www.youtube.com/watch?v=mJ2hPsSBGcE]

Creating and Editing a workflow (applicable to "Pro" version only)

[youtube https://www.youtube.com/watch?v=PKHJN_X--Vs]

How to manage published content via workflow - revise published content (applicable to "Pro" version only)

[youtube http://www.youtube.com/watch?v=J4qJG7-F1qQ]

== Installation ==

1. Download the plugin zip file to your desktop
2. Upload the plugin to WordPress
3. Activate Oasis Workflow by going to Workflow Admin --> Settings
4. You are now ready to use Oasis Workflow! Build Your Workflow and start managing your editorial content flow.

== Frequently Asked Questions ==

For [Frequently Asked Questions](http://oasisworkflow.com/faq) plus documentation, plugin help, go [here](http://oasisworkflow.com)

== Screenshots ==

1. Visual Work flow designer
2. More examples.. of the workflow designer
3. Even more examples.. of the workflow designer
4. Role-based routing
5. Inbox
6. Sign off
7. Process history


== Changelog ==

= Version 1.9 =
* Fixed duplicate Post Author appearing in the list of available users.
* Added edit_others_posts and edit_others_pages to the author role by default.

= Version 1.8 =
* Compatibility with WordPress 4.4
* Added support for Advanced Custom Fields (ACF) plugin to invoke ACF validation before "Submit to Workflow" and/or "Sign off".
* Added Javascript hooks to invoke custom functionality before displaying the "Submit to Workflow" and/or "Sign off" popup.
* Changed background color for inbox items which are past due date.

= Version 1.7 =
* End date on the workflows is not required any more. If not specified it will be considered to be valid for ever.
* Merged Workflows and Workflow Admin into one menu option called "Workflows".
* Added Reports - to view current assignments and to view what is/is not in the workflow.
* Added "delete workflow" to delete any unwanted revisions.

= Version 1.6 =
* WE RECOMMEND TAKING A DATABASE BACKUP BEFORE UPGRADING TO THIS VERSION *
* Major multisite related change -
* For a multisite installation, you can now control the workflow configuration and workflows at the site level. The Workflow Admin menu is moved from Network Admin to Site Admin.
* Users can view the history of the post/page from within their inbox with just one click.
* Users can view all the comments posted on the post from within their inbox. This will help the user to easily remember the context for the various comments/changes. 
Since we changed the structure of how we are storing the comments, you will notice the comment date missing on the old comments. Going forward, the comment date will work as usual.  


= Version 1.5 =
* Fixed CSRF and SQL injection security related issues.
* Default the workflow if there is only one applicable workflow.
* Added multi-select and multi-push/pull for the assignee and available users.
* Added more sort options on the inbox.
* Allow to save step even when there are items in the workflow.
* Added a new settings tab for configuring workflow terminology. You can now specify your own terminology for various actions.

= Version 1.4 =
* Fixed abort
* Allow for past publish date when signing off the last task.
* Added new hooks for "submit to workflow", "sign off", "workflow complete".

= Version 1.3 =
* Show Update button for published articles.
* Added "hide upgrade notice" link.
* Fixed menu position to have a unique position.

= Version 1.2 =
* Fixed date format for publish date
* Fixed issue with due date javascript

= Version 1.1 =
* Email Settings - A new tab in the Settings page, to better control how and when emails are sent from Oasis Workflow for task assignments, reminders and post publish.
* Abort Workflow is added to the Inbox page. This will allow the users to abort the workflow from their inbox.
* History Graphic - Show workflow graphic on the post page. Configurable via Workflow Settings page.
* Added "Delete/Purge" History feature
* Added sorting on the Workflow Inbox page. Users can now sort their workflow inbox via post title.
* Added "self review" to the workflows.
* Fixed default ordering on the inbox page.
* Fixed status change issue on "submit to workflow"
* Fixed add_query_arg() and remove_query_arg() usage

= Version 1.0.20 =
* Fixed php error related to date locale (hopefully the last update related to date issues)
* Tested for Wordpress 4.1.1

= Version 1.0.19 =
* Fixed a php error related to missing date on workflow edit.
* Added post types to workflow selection. Now you can choose the post types which should go through the workflow.
* Made the roles drop down to be multi-site compatible. Now you will be able to see roles from all the sites.
* Added a custom role called - Post Author.
* Fixed "clear date" function on submit step popup.
* Fixed Page/Post delete to delete the inbox items related to the deleted post/page

= Version 1.0.18 =
* fixed dd/mm/yyyy format for future publish date

= Version 1.0.17 =
* Made the date formats compatible with Wordpress date formats
* Added a setting for default due date
* bug fixes

= Version 1.0.16 =
* Fixed compatibility issues with Wordpress 4.1
* Added Italian translation
* bug fixes

= Version 1.0.15 =
* Fixed future date issue related to timezones
* Fixed post revision schedule
* Modified the DB to make it easier to add more features

= Version 1.0.14 =
* fixed compatibility issues with Wordpress 4.0
* added missing calendar images
* fixed compatibility issues with Visual Composer Plugin.
* removed "quick edit" from Workflow Inbox
* bug fixes

= Version 1.0.13 =
* Load the JS and CSS scripts only when needed. This helps with compatibility issues with other plugins.
* Allow setting of future publish date on submit to workflow.
* fixed german translations.
* fixed compatibility issues with Wordpress 3.9

= Version 1.0.12 =
* fixed issue with workflow history discrepancies and abort workflow action.
* fixed DB related issues with NULL and NOT NULL.
* fixed multisite issue related to switch and restore blog.

= Version 1.0.11 =
* added german translation files
* fixed the issues with Strict PHP - non static function called in static fashion
* fixed update datetime issue with the workflow
* changed post title to be a simple text in the subject line  

= Version 1.0.10 =
* made publish step a multi-user assignment step with claim process.
* after sign off, the user will be redirected to the inbox page.
* fixed issue with permalink being changed after publish from the inbox page.
* fixed the issue with unnecessary call to post_publish hook.
* fixed to remove a warning message related to mysql_real_escape_string()

= Version 1.0.9 =
* removed a call to wp-load.php to help with performance
* added visual indicator to the first step

= Version 1.0.8 =
* Updated the Inbox menu to display the number of inbox items.
* The plugin will now come with an out of the box workflow when installed for the first time. This will help getting started with the plugin with little or no effort. Simply activate the workflow process from Workflow Admin --&gt; Settings page and you are ready to use the workflow.
* Auto select of user during the sign off process, if there is one and only one user for that given role.
* Due dates are not required/shown unless "reminder emails" are set to be required on the settings page.
* Added French translation files.
* Added Sign off button on the Posts page. This will help to sign off the post/page even when you are not in your inbox.
* Fixed issues related to IE compatibility. The plugin should function well in IE 9 and IE 10.
* Fixed issue with sign off caused due to the addition of  "take over" functionality by core Wordpress.
* We have removed the connection type from the connection settings popup. The plugin defaults to one specific connection type. You might see the workflow visual representation to be a bit awkward. All you have to do is to save the workflow and it will auto-correct the connections.

= Version 1.0.7 =
* Bug fixes.
* minor enhancements

= Version 1.0.6 =
* Internationalization(I18N) and localization (L10N) support added.
* Bug fixes.
* minor enhancements

= Version 1.0.5 =
* Multi site enhancements. Moved the Workflow Admin to Network Admin, so workflows can be shared between all the sites.
* No need to duplicate the workflows for new sites inside a multi site environment.
* Note: 
* 1. Workflows previously created in sub sites except the main site will NOT be available anymore. 
If these workflows are different, they need to be recreated with this upgrade.
* 2. Make sure to complete all the existing workflows for sub sites, to avoid any unexpected behavior. 

= Version 1.0.4 =
* Made the assignment step a multi-user step, where multiple users can be assigned the work however only one can claim it.
* Configuration - Roles who are allowed to publish post without going through a workflow.
* Set "publish" as the success step for the publish step.
* Bug fixes.

= Version 1.0.3 =
* Added an option for admin to detach the post from oasis workflow and go back to normal wordpress behavior.
* Added reminder email AFTER certain due date feature.
* Change the post title placeholder to be a link.
* Bug fixes.

= Version 1.0.2 =
* Made WP 3.5 compatible

= Version 1.0.1 =
* Added Multisite capability.
* Admin can now view another user's inbox and signoff on behave of other users.
* Bug fixes.

= Version 1.0.0 =
* Initial version