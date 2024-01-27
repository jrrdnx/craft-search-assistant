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
 * Popular Searches Widget
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class PopularSearchesWidget extends Widget
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
        return Craft::t('search-assistant', 'popularSearches');
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
            'search-assistant/widgets/popularSearchesSettings',
            [
                'widget' => $this
            ]
        );
    }

    public function getBodyHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'search-assistant/widgets/popularSearches',
            [
                'popularSearches' => SearchAssistant::$plugin->history->getPopularSearches([
                    'limit' => (int)$this->limit
                ])
            ]
        );
    }
}