<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\variables;

use jrrdnx\searchassistant\SearchAssistant;

use Craft;
use yii\base\Behavior;

/**
 * Craft Variable Behavior
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class CraftVariableBehavior extends Behavior
{
    public function recentSearches(array $options = [])
    {
        return SearchAssistant::$plugin->history->getRecentSearches($options);
    }

    public function popularSearches(array $options = [])
    {
        return SearchAssistant::$plugin->history->getPopularSearches($options);
    }
}