<?php

namespace jrrdnx\searchassistant\gql\arguments\elements;

use craft\gql\base\ElementArguments;
use GraphQL\Type\Definition\Type;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class Search extends ElementArguments
{
    public static function getArguments(): array
    {
        return array_merge(parent::getArguments(), [
            'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
                'description' => 'The number of searches to return.',
            ],
            'offset' => [
                'name' => 'offset',
                'type' => Type::int(),
                'description' => 'The offset of the first search to return.',
            ],
            'orderBy' => [
                'name' => 'orderBy',
                'type' => Type::string(),
                'description' => 'The field to order by (lastSearched, searchCount).',
            ],
            'pageUrl' => [
                'name' => 'pageUrl',
                'type' => Type::string(),
                'description' => 'The page URL to filter by.',
            ],
            'siteId' => [
                'name' => 'siteId',
                'type' => Type::int(),
                'description' => 'The site ID to filter by.',
            ],
        ]);
    }
}