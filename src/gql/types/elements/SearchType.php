<?php

namespace jrrdnx\searchassistant\gql\types\elements;

use jrrdnx\searchassistant\gql\interfaces\elements\Search as SearchInterface;

use craft\gql\GqlEntityRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class SearchType extends ObjectType
{
    public static function getName(): string
    {
        return 'SearchType';
    }

    public static function getType(): Type
    {
        if ($type = GqlEntityRegistry::getEntity(self::getName())) {
            return $type;
        }

        return GqlEntityRegistry::createEntity(self::getName(), new static([
            'name' => self::getName(),
            'fields' => fn() => self::getFieldDefinitions(),
            'description' => 'This type represents a search query.',
            'interfaces' => [
                SearchInterface::getType(),
            ],
        ]));
    }

    public static function getFieldDefinitions(): array
    {
        return SearchInterface::getFieldDefinitions();
    }
}