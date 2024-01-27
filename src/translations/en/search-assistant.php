<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

/**
 * Search Assistant English Translation
 *
 * Returns an array with the string to be translated (as passed to `Craft::t('search-assistant', '...')`) as
 * the key, and the translation as the value.
 *
 * http://www.yiiframework.com/doc-2.0/guide-tutorial-i18n.html
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
return [
	// Settings
    'enabled'                                           => 'Enabled',
    'pluginName'                                        => 'Plugin Name',
    'pluginNameInstructions'                            => 'The public-facing name of the plugin',
	'ipIgnore'                                          => 'IP/CIDR Ignore List',
    'ipIgnoreInstructions'                              => 'Requests matching these IPs will not be tracked',
    'ipCidrAddress'                                     => 'IP/CIDR Address',
    'note'                                              => 'Note',
    'addAnIpCidrAddress'                                => 'Add an IP/CIDR Address',
    'pleaseProvideValidIpCidr'                          => 'Please provide a valid IPv4 or IPv6 address or range',
    'ignoreCpUsers'                                     => 'Ignore control panel users',
    'ignoreCpUsersInstructions'                         => 'If true, will not track searches performed by users who are currently logged in to the control panel',
    'overridden'                                        => 'This is being overridden by the `{name}` setting in the `config/search-assistant.php` file.',

    // Permissions
    'viewFullHistory'                                   => 'View full history',
    'canChangeStatus'                                   => 'Can change status',
    'canDelete'                                         => 'Can delete',

    // CP sections
    'fullHistory'                                       => 'Full History',
    'status'                                            => 'Status',
    'pageUrl'                                           => 'Page URL',
    'keywords'                                          => 'Keywords',
    'numResults'                                        => '# Results Found',
    'searchCount'                                       => '# Times Searched',
    'firstSearched'                                     => 'First Searched',
    'lastSearched'                                      => 'Last Searched',
    'proVersionRequired'                                => 'The Pro version of this plugin is required to view this page',

    // Widgets
    'recentSearches'                                    => 'Recent Searches',
    'popularSearches'                                   => 'Popular Searches',
    'limit'                                             => 'Limit'
];
