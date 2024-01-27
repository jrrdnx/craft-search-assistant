<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\widgets;

use jrrdnx\searchassistant\SearchAssistant;

use Craft;
use craft\base\Widget;

/**
 * Recent Searches Widget
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class RecentSearchesWidget extends Widget
{
    // Public Properties
    // =========================================================================

    /**
     * @var int
     */
    public int $limit = 10;

    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('search-assistant', 'recentSearches');
    }

    public static function icon(): string
    {
        return Craft::getAlias('@jrrdnx/searchassistant/icon-mask.svg');
    }

    // Public Methods
    // =========================================================================

    public function rules(): array
    {
        $rules = parent::rules();
        return $rules;
    }

    public function getSettingsHtml(): null|string
    {
        return Craft::$app->getView()->renderTemplate(
            'search-assistant/widgets/recentSearchesSettings',
            [
                'widget' => $this
            ]
        );
    }

    public function getBodyHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'search-assistant/widgets/recentSearches',
            [
                'recentSearches' => SearchAssistant::$plugin->history->getRecentSearches([
                    'limit' => (int)$this->limit
                ])
            ]
        );
    }
}