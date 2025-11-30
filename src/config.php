<?php

/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

/**
 * Search Assistant config.php
 *
 * This file exists only as a template for the Search Assistant settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'search-assistant.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    /*******************************************************************************
     *	CONTROL PANEL
     ******************************************************************************/
    // Enable/disable search tracking and the control panel section
    'enabled' => true,

    // Enable/disable debug mode
    'debugMode' => false,

    // The public-facing name of the plugin
    'pluginName' => 'Search Assistant',

    // IP/CIDR ignore list, requests matching these IPs will not be tracked
    'ipIgnore' => [
        ['::1', 'IPv6 localhost'],
        ['127.0.0.1', 'IPv4 localhost']
    ],

    // If true, will not track searches performed by users who are currently logged in to the control panel
    'ignoreCpUsers' => true
];
