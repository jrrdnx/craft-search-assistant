<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\elements\db;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;

/**
 * History Element Query
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class HistoryElementQuery extends ElementQuery
{
    public bool $recentSearches = false;
    public bool $popularSearches = false;
    public mixed $siteId = null;
    public string $pageUrl = '';
    public string $keywords = '';
    public bool $numResults = true;
    public $searchCount;

    public function recentSearches($value): self
    {
        $this->recentSearches = $value;

        return $this;
    }

    public function popularSearches($value): self
    {
        $this->popularSearches = $value;

        return $this;
    }

    public function siteId($value): static
    {
        $this->siteId = $value;

        return $this;
    }

    public function pageUrl($value): self
    {
        $this->pageUrl = $value;

        return $this;
    }

    public function keywords($value): self
    {
        $this->keywords = $value;

        return $this;
    }

    public function numResults($value): self
    {
        $this->numResults = $value;

        return $this;
    }

    public function searchCount($value): self
    {
        $this->searchCount = $value;

        return $this;
    }

    public function lastSearched($value): self
    {
        $this->lastSearched = $value;

        return $this;
    }

    protected function beforePrepare(): bool
    {
        $table = 'search_assistant_history';

        // JOIN our table
        $this->joinElementTable($table);

        // SELECT the `pageUrl`, `keywords`, `numResults`, `searchCount`, and `lastSearched` columns
        $this->query->select([
            $table.'.pageUrl',
            $table.'.keywords',
            $table.'.numResults',
            $table.'.searchCount',
            $table.'.lastSearched'
        ]);

        if ($this->recentSearches) {
            $this->query->orderBy([
                'lastSearched' => SORT_DESC
            ]);
        }

        if ($this->popularSearches) {
            $this->query->orderBy([
                'searchCount' => SORT_DESC
            ]);
        }

        if ($this->siteId) {
            $this->subQuery->andWhere(Db::parseParam($table.'.siteId', $this->siteId));
        }

        if ($this->pageUrl) {
            $this->subQuery->andWhere(Db::parseParam($table.'.pageUrl', $this->pageUrl));
        }

        if ($this->keywords) {
            $this->subQuery->andWhere(Db::parseParam($table.'.keywords', $this->keywords));
        }

        if ($this->numResults) {
            $this->subQuery->andWhere([
                '>', 'numResults', 0
            ]);
        }

        if ($this->searchCount) {
            $this->subQuery->andWhere(Db::parseParam($table.'.searchCount', $this->searchCount));
        }

        return parent::beforePrepare();
    }
}