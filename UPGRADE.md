# Upgrade guide
- [Upgrading to 1.0.5 from 1.0.4](#upgrade-1.0.5)

- [Upgrading from Shahiem Seymor's Frontend User Roles Manager](#roles)

<a name="upgrade-1.0.5"></a>
## Upgrading To 1.0.5

**This is an important update that contains breaking changes.**

 Passage Permission Keys twig function "inGroup" now uses the unique user group code rather than the user group name. If you want to still use the not unique group name, you may use the new function "inGroupName".

 If you think you may have used "inGroup" in your pages then it is recommended that you search your themes directory for "inGroup" and either change it to "inGroupName" or change the name of the group to the code of the group you want to check for.

 There are also new class methods that can be used in you plugins.  You can read about them in the documentation.

<a name="roles"></a>
## Upgrading from Shahiem Seymor's Frontend User Roles Manager

If you have "Frontend User Roles Manager" installed there will be data transfer buttons
available in the "Passage Keys" list in the Back-end.  You may use them to transfer data from 
"Frontend User Roles Manager" to the "Passage Keys" plugin tables.

These buttons will go away if you uninstall "Frontend User Roles Manager".