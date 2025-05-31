<?php

namespace jrrdnx\searchassistant\gql\resolvers\elements;

use jrrdnx\searchassistant\elements\HistoryElement;
use jrrdnx\searchassistant\helpers\Gql as GqlHelper;
use jrrdnx\searchassistant\SearchAssistant;

use craft\gql\base\ElementResolver;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class Search extends ElementResolver
{
    public static function resolve($source, array $arguments, $context, ResolveInfo $resolveInfo): mixed
    {
        $fieldName = $resolveInfo->fieldName;

        switch ($fieldName) {
            case 'searches':
                if (!GqlHelper::canQuerySearches()) {
                    return [];
                }
                $query = HistoryElement::find();
                foreach ($arguments as $key => $value) {
                    if (method_exists($query, $key)) {
                        $query->$key($value);
                    }
                }
                return $query->all();

            case 'popularSearches':
                if (!GqlHelper::canQueryPopularSearches()) {
                    return [];
                }
                $results = SearchAssistant::$plugin->history->getPopularSearches([
                    'limit' => $arguments['limit'] ?? 10,
                    'pageUrl' => $arguments['pageUrl'] ?? null,
                    'siteId' => $arguments['siteId'] ?? null
                ]);

                // Convert raw SQL results to proper format
                return array_map(function($result) {
                    // Find the most recent search for these keywords to get additional data
                    $search = HistoryElement::find()
                        ->keywords($result['keywords'])
                        ->orderBy(['lastSearched' => SORT_DESC])
                        ->one();

                    return [
                        'id' => $search ? $search->id : null,
                        'keywords' => $result['keywords'],
                        'searchCount' => (int)$result['searchCount'],
                        'pageUrl' => $search ? $search->pageUrl : null,
                        'numResults' => $search ? $search->numResults : null,
                        'lastSearched' => $search ? $search->lastSearched : null,
                    ];
                }, $results);

            case 'recentSearches':
                if (!GqlHelper::canQueryRecentSearches()) {
                    return [];
                }
                $results = SearchAssistant::$plugin->history->getRecentSearches([
                    'limit' => $arguments['limit'] ?? 10,
                    'pageUrl' => $arguments['pageUrl'] ?? null,
                    'siteId' => $arguments['siteId'] ?? null
                ]);

                // Convert raw SQL results to proper format
                return array_map(function($result) {
                    // Find the search record for these keywords
                    $search = HistoryElement::find()
                        ->keywords($result['keywords'])
                        ->orderBy(['lastSearched' => SORT_DESC])
                        ->one();

                    return [
                        'id' => $search ? $search->id : null,
                        'keywords' => $result['keywords'],
                        'searchCount' => $search ? $search->searchCount : 0,
                        'pageUrl' => $search ? $search->pageUrl : null,
                        'numResults' => $search ? $search->numResults : null,
                        'lastSearched' => $result['lastSearched'],
                    ];
                }, $results);

            default:
                return [];
        }
    }

    public static function prepareQuery($source, array $arguments, $fieldName = null): mixed
    {
        if ($source === null) {
            // If this is the beginning of a resolver chain, start fresh
            $query = HistoryElement::find();
        } else {
            // If not, get the prepared element query
            $query = $source->$fieldName;
        }

        // Return the query if it's preloaded
        if (is_array($query)) {
            return $query;
        }

        // Apply arguments to query
        foreach ($arguments as $key => $value) {
            if (method_exists($query, $key)) {
                $query->$key($value);
            } elseif (property_exists($query, $key)) {
                $query->$key = $value;
            }
        }

        // Check permissions based on query type
        if (isset($arguments['popular']) && $arguments['popular']) {
            $canQuery = GqlHelper::canQueryPopularSearches();
            if (!$canQuery) {
                return [];
            }
            return SearchAssistant::$plugin->history->getPopularSearches([
                'limit' => $arguments['limit'] ?? 10,
                'pageUrl' => $arguments['pageUrl'] ?? null,
                'siteId' => $arguments['siteId'] ?? null
            ]);
        } elseif (isset($arguments['recent']) && $arguments['recent']) {
            $canQuery = GqlHelper::canQueryRecentSearches();
            if (!$canQuery) {
                $query->id(0); // This ensures no results
                return $query;
            }
        } else {
            $canQuery = GqlHelper::canQuerySearches();
            if (!$canQuery) {
                $query->id(0); // This ensures no results
                return $query;
            }
        }

        return $query;
    }
}