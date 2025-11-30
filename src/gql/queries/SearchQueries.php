<?php

namespace jrrdnx\searchassistant\gql\queries;

use jrrdnx\searchassistant\SearchAssistant;
use jrrdnx\searchassistant\gql\types\elements\SearchType;
use jrrdnx\searchassistant\gql\resolvers\elements\Search as SearchResolver;
use jrrdnx\searchassistant\gql\GqlPermissions;
use jrrdnx\searchassistant\gql\helpers\Gql as GqlHelper;

use craft\gql\base\Query;
use GraphQL\Type\Definition\Type;

class SearchQueries extends Query
{
    public static function getQueries($checkToken = true): array
    {
        // Log that we're attempting to register queries
        SearchAssistant::info('SearchQueries::getQueries() called');

        // Only register queries if we're in PRO mode and the plugin is enabled
        if (!SearchAssistant::getInstance()->is(SearchAssistant::EDITION_PRO)) {
            SearchAssistant::info('Plugin is not in PRO mode');
            return [];
        }

        if (!SearchAssistant::getInstance()->getSettings()->getEnabled()) {
            SearchAssistant::info('Plugin is not enabled');
            return [];
        }

        $queries = [];

        // Regular searches
        if (!$checkToken || GqlHelper::canQuerySearches()) {
            SearchAssistant::info('Registering searches query');
            $queries['searches'] = [
                'type' => Type::listOf(SearchType::getType()),
                'args' => [
                    'search' => [
                        'name' => 'search',
                        'type' => Type::string(),
                        'description' => 'Filter by search query',
                    ],
                    'limit' => [
                        'name' => 'limit',
                        'type' => Type::int(),
                        'description' => 'The number of searches to return',
                    ],
                    'offset' => [
                        'name' => 'offset',
                        'type' => Type::int(),
                        'description' => 'The number of searches to skip',
                    ],
                    'orderBy' => [
                        'name' => 'orderBy',
                        'type' => Type::string(),
                        'description' => 'The field to order by',
                    ],
                    'pageUrl' => [
                        'name' => 'pageUrl',
                        'type' => Type::string(),
                        'description' => 'Filter by page URL',
                    ],
                    'siteId' => [
                        'name' => 'siteId',
                        'type' => Type::int(),
                        'description' => 'Filter by site ID',
                    ],
                ],
                'resolve' => [SearchResolver::class, 'resolve'],
                'description' => 'Query all searches.',
                'requirePermission' => GqlPermissions::PERMISSION_SEARCHES_READ,
            ];
        }

        // Popular searches
        if (!$checkToken || GqlHelper::canQueryPopularSearches()) {
            SearchAssistant::info('Registering popularSearches query');
            $queries['popularSearches'] = [
                'type' => Type::listOf(SearchType::getType()),
                'args' => [
                    'limit' => [
                        'name' => 'limit',
                        'type' => Type::int(),
                        'description' => 'The number of searches to return',
                    ],
                    'pageUrl' => [
                        'name' => 'pageUrl',
                        'type' => Type::string(),
                        'description' => 'Filter by page URL',
                    ],
                    'siteId' => [
                        'name' => 'siteId',
                        'type' => Type::int(),
                        'description' => 'Filter by site ID',
                    ],
                ],
                'resolve' => [SearchResolver::class, 'resolve'],
                'description' => 'Query popular searches.',
                'requirePermission' => GqlPermissions::PERMISSION_SEARCHES_POPULAR,
            ];
        }

        // Recent searches
        if (!$checkToken || GqlHelper::canQueryRecentSearches()) {
            SearchAssistant::info('Registering recentSearches query');
            $queries['recentSearches'] = [
                'type' => Type::listOf(SearchType::getType()),
                'args' => [
                    'limit' => [
                        'name' => 'limit',
                        'type' => Type::int(),
                        'description' => 'The number of searches to return',
                    ],
                    'pageUrl' => [
                        'name' => 'pageUrl',
                        'type' => Type::string(),
                        'description' => 'Filter by page URL',
                    ],
                    'siteId' => [
                        'name' => 'siteId',
                        'type' => Type::int(),
                        'description' => 'Filter by site ID',
                    ],
                ],
                'resolve' => [SearchResolver::class, 'resolve'],
                'description' => 'Query recent searches.',
                'requirePermission' => GqlPermissions::PERMISSION_SEARCHES_RECENT,
            ];
        }

        SearchAssistant::info('Returning queries: ' . print_r(array_keys($queries), true));
        return $queries;
    }
}
