# Passage plugin

( Installation code : __kurtjensen.mycalendar__ ) Requires ( __RainLab.User__ )
This plugin adds a front end user group permission system to [OctoberCMS](http://octobercms.com).

Download the plugin to the plugins directory and logout and log in back into October backend. Go to the Passage Keys page via the side menu in users in the backend and add your permissions/keys.

###User Permision / Passage Key Entry######

In the backend under Users (Rainlab.Users) you will find a sidemenu item called __"Passage Keys".__  This is where you enter your permission names and an optional description.


In the backend under Users you will find a button at the top called __"User Groups"__. Press button to see groups.  When editing a group you will find check boxes at the bottom for each "Passage Key".  This is where you assign permissions for each user group.


###User Permisions in Pages or Partials######

On a page you may restrict access to a portion of view by using the following code:

    {% if can('calendar_meetings') %}

    <p>This will show only if the user belongs to a Rainlab.User Usergroup that includes the permision named "calendar_meetings".</p>

    {% else %}

    <p>This will show if the user DOES NOT belong to a Rainlab.User Usergroup that include the permision named "calendar_meetings".</p>

    {% endif %}


    <p>This will show for all users regardless of permissions.</p>


###User Permisions in Your Own Plugins######

In your plugin you may restrict access to a portion of code:


	$permission_keys_by_name = \KurtJensen\Passage\Plugin::globalPassageKeys();

	// Then check the array against the keys you want to use

	if(in_array( 'view_magic_dragon', $permission_keys_by_name)) {
		// Do stuff
	}

	/**
	* or
	* 
	* 	lets say you have a model that uses a permission field and want to
	*  	see if model permission matches.
	* 
	* 	Example:
	* 	$model->perm = 5 which came from a dropdown that contained keys 
	* 	from \KurtJensen\Passage\Plugin::globalPassageKeys();
	**/

	// flip the array to get keys
	$permission_keys_by_id = array_flip(\KurtJensen\Passage\Plugin::globalPassageKeys());
	$model = Model::first();
	if(in_array( $model->perm, $permission_keys_by_id)) {
		// Do stuff
	}


## Like this plugin?
If you like this plugin or if you use some of my plugins, you can help me by submiting a review in the market. Small donations also help keep me motivated. 

Please do not hesitate to find me in the IRC channel or contact me for assistance.
Sincerely 
Kurt Jensen