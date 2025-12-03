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
use craft\db\Query;
use yii\db\Expression;

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

        if ($this->popularSearches) {
            // For popular searches, we need to group and aggregate
            // We set $this->select to ensure ElementQuery doesn't add conflicting columns
            $this->select = [
                $table . '.keywords',
                'SUM([[' . $table . '.searchCount]]) as [[searchCount]]',
                'MAX([[' . $table . '.lastSearched]]) as [[lastSearched]]',
                'MAX([[' . $table . '.pageUrl]]) as [[pageUrl]]',
                'MAX([[' . $table . '.numResults]]) as [[numResults]]',
                // Aggregate standard Element columns to satisfy GROUP BY
                'MAX([[elements.id]]) as [[id]]',
                'MAX([[elements.dateCreated]]) as [[dateCreated]]',
                'MAX([[elements.dateUpdated]]) as [[dateUpdated]]',
                'MAX([[elements.uid]]) as [[uid]]',
                'MAX(CASE WHEN [[elements.enabled]] THEN 1 ELSE 0 END) as [[enabled]]'
            ];

            $this->query
                ->groupBy([$table . '.keywords'])
                ->orderBy([new Expression('[[searchCount]] DESC')]);
        } else {
            // For other queries, select all fields normally
            $this->query->select([
                $table . '.pageUrl',
                $table . '.keywords',
                $table . '.numResults',
                $table . '.searchCount',
                $table . '.lastSearched'
            ]);

            if ($this->recentSearches) {
                $this->query->orderBy([
                    'lastSearched' => SORT_DESC
                ]);
            }
        }

        if ($this->siteId) {
            $this->subQuery->andWhere(Db::parseParam($table . '.siteId', $this->siteId));
        }

        if ($this->pageUrl) {
            $this->subQuery->andWhere(Db::parseParam($table . '.pageUrl', $this->pageUrl));
        }

        if ($this->keywords) {
            $this->subQuery->andWhere(Db::parseParam($table . '.keywords', $this->keywords));
        }

        if ($this->numResults) {
            $this->subQuery->andWhere([
                '>',
                'numResults',
                0
            ]);
        }

        if ($this->searchCount) {
            $this->subQuery->andWhere(Db::parseParam($table . '.searchCount', $this->searchCount));
        }

        return parent::beforePrepare();
    }
}
