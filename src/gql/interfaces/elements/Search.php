<?php

namespace jrrdnx\searchassistant\gql\interfaces\elements;

use jrrdnx\searchassistant\elements\HistoryElement;
use jrrdnx\searchassistant\gql\types\elements\Search as SearchType;

use Craft;
use craft\gql\GqlEntityRegistry;
use craft\gql\interfaces\Element;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InterfaceType;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class Search extends Element
{
    /**
     * @var string
     */
    private static string $typeName = 'SearchInterface';

    public static function getName(): string
    {
        return self::$typeName;
    }

    public static function getType($fields = null): Type
    {
        $typeName = self::getName();

        if ($type = GqlEntityRegistry::getEntity($typeName)) {
            return $type;
        }

        $fields = self::getFieldDefinitions();

        $type = GqlEntityRegistry::createEntity($typeName, new InterfaceType([
            'name' => $typeName,
            'fields' => $fields,
            'description' => 'This is the interface implemented by all Searches.',
            'resolveType' => function ($value) {
                if ($value instanceof HistoryElement) {
                    return SearchType::getType();
                }
                return null;
            },
        ]));

        return $type;
    }

    public static function getFieldDefinitions(): array
    {
        $fields = array_merge(parent::getFieldDefinitions(), [
            'pageUrl' => [
                'name' => 'pageUrl',
                'type' => Type::string(),
                'description' => 'The URL of the page where the search was performed.',
            ],
            'keywords' => [
                'name' => 'keywords',
                'type' => Type::string(),
                'description' => 'The search keywords used.',
            ],
            'numResults' => [
                'name' => 'numResults',
                'type' => Type::int(),
                'description' => 'The number of results returned.',
            ],
            'searchCount' => [
                'name' => 'searchCount',
                'type' => Type::int(),
                'description' => 'The number of times this search has been performed.',
            ],
            'lastSearched' => [
                'name' => 'lastSearched',
                'type' => Type::string(),
                'description' => 'The date/time this search was last performed.',
            ],
        ]);

        return Craft::$app->getGql()->prepareFieldDefinitions($fields, self::getName());
    }
}
