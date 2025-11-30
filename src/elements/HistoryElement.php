<?php

/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\elements;

use jrrdnx\searchassistant\elements\db\HistoryElementQuery;
use jrrdnx\searchassistant\records\HistoryRecord;
use jrrdnx\searchassistant\SearchAssistant;

use Craft;
use craft\base\Element;
use craft\elements\actions\Restore;
use craft\elements\actions\SetStatus;
use craft\elements\User;
use craft\helpers\Db;
use craft\helpers\UrlHelper;
use DateTime;
use yii\web\Response;

/**
 * History Element
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class HistoryElement extends Element
{
    public ?int $id = null;
    public ?int $siteId;
    public string $pageUrl = '';
    public string $keywords = '';
    public int $numResults;
    public int $searchCount;
    public string $lastSearched = '';
    public ?DateTime $dateCreated = null;
    public ?DateTime $dateUpdated = null;

    public static function displayName(): string
    {
        return Craft::t('search-assistant', 'status');
    }

    public static function hasContent(): bool
    {
        return false;
    }

    public static function hasTitles(): bool
    {
        return false;
    }

    public static function hasStatuses(): bool
    {
        return true;
    }

    public static function isLocalized(): bool
    {
        return true;
    }

    public function getSupportedSites(): array
    {
        return [
            Craft::$app->sites->getCurrentSite()->id
        ];
    }

    public static function find(): HistoryElementQuery
    {
        return new HistoryElementQuery(static::class);
    }

    public function beforeSave(bool $isNew): bool
    {
        return parent::beforeSave($isNew);
    }

    public function afterSave(bool $isNew): void
    {
        if (!$this->propagating) {
            if ($isNew) {
                Db::insert(HistoryRecord::tableName(), [
                    'id' => $this->id,
                    'siteId' => $this->siteId,
                    'pageUrl' => $this->pageUrl,
                    'keywords' => $this->keywords,
                    'numResults' => $this->numResults,
                    'searchCount' => $this->searchCount,
                    'lastSearched' => $this->lastSearched,
                ]);
            } else {
                Db::update(HistoryRecord::tableName(), [
                    'siteId' => $this->siteId,
                    'pageUrl' => $this->pageUrl,
                    'keywords' => $this->keywords,
                    'numResults' => $this->numResults,
                    'searchCount' => $this->searchCount,
                    'lastSearched' => $this->lastSearched,
                ], [
                    'id' => $this->id
                ]);
            }
        }

        parent::afterSave($isNew);
    }

    protected static function defineSources(?string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => Craft::t('search-assistant', 'fullHistory'),
                'criteria' => [
                    'numResults' => false
                ],
                'defaultSort' => ['lastSearched', 'desc']
            ],
            [
                'key' => 'recent-searches',
                'label' => Craft::t('search-assistant', 'recentSearches'),
                'criteria' => [
                    'recentSearches' => true
                ],
                'defaultSort' => ['lastSearched', 'desc']
            ],
            [
                'key' => 'popular-searches',
                'label' => Craft::t('search-assistant', 'popularSearches'),
                'criteria' => [
                    'popularSearches' => true
                ],
                'defaultSort' => ['searchCount', 'desc']
            ]
        ];
    }

    public function __toString(): string
    {
        return (string)'';
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'keywords' => ['label' => Craft::t('search-assistant', 'keywords')],
            'pageUrl' => ['label' => Craft::t('search-assistant', 'pageUrl')],
            'numResults' => ['label' => Craft::t('search-assistant', 'numResults')],
            'searchCount' => ['label' => Craft::t('search-assistant', 'searchCount')],
            'dateCreated' => ['label' => Craft::t('search-assistant', 'firstSearched')],
            'lastSearched' => ['label' => Craft::t('search-assistant', 'lastSearched')]
        ];
    }

    protected static function defineSearchableAttributes(): array
    {
        return [
            'keywords',
            'pageUrl'
        ];
    }

    protected static function defineSortOptions(): array
    {
        return [
            'lastSearched' => Craft::t('search-assistant', 'lastSearched'),
            'keywords' =>  Craft::t('search-assistant', 'keywords'),
            'pageUrl' =>  Craft::t('search-assistant', 'pageUrl'),
            'numResults' => Craft::t('search-assistant', 'numResults'),
            'searchCount' => Craft::t('search-assistant', 'searchCount'),
            'dateCreated' => Craft::t('search-assistant', 'firstSearched')
        ];
    }

    protected static function defineActions(?string $source = null): array
    {
        return [
            Restore::class,
            SetStatus::class
        ];
    }

    public function canView(User $user): bool
    {
        if ($user->can('searchAssistant:viewFullHistory')) {
            return true;
        }

        return false;
    }

    public function canSave(User $user): bool
    {
        if ($user->can('searchAssistant:canChangeStatus')) {
            return true;
        }

        return false;
    }

    public function canDelete(User $user): bool
    {
        if ($user->can('searchAssistant:canDelete')) {
            return true;
        }

        return false;
    }

    public function prepareEditScreen(Response $response, string $containerId): void
    {
        $response->crumbs([
            [
                'label' => SearchAssistant::$plugin->getSettings()->getPluginName(),
                'url' => UrlHelper::cpUrl('search-assistant'),
            ]
        ]);
    }

    public static function gqlTypeName(): string
    {
        return 'Search';
    }
}
