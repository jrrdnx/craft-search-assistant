<?php

namespace jrrdnx\searchassistant\gql\types\generators;

use jrrdnx\searchassistant\gql\interfaces\elements\Search as SearchInterface;
use jrrdnx\searchassistant\gql\types\elements\Search as SearchType;
use jrrdnx\searchassistant\elements\HistoryElement;

use craft\gql\base\GeneratorInterface;
use craft\gql\base\ObjectType;
use craft\gql\GqlEntityRegistry;
use GraphQL\Type\Definition\Type;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class HistoryElementType extends ObjectType implements GeneratorInterface
{
    public static function generateTypes($context = null): array
    {
        $types = [];

        // Register the interface
        $interfaceName = SearchInterface::getName();
        $interfaceType = SearchInterface::getType();
        if ($interfaceType instanceof Type) {
            $types[$interfaceName] = $interfaceType;
        }

        // Register the concrete type
        $typeName = SearchType::getName();
        $type = SearchType::getType();
        if ($type instanceof Type) {
            $types[$typeName] = $type;
        }

        return $types;
    }

    public static function getName(): string
    {
        return 'HistoryElement';
    }

    public static function getType(): Type
    {
        if ($type = GqlEntityRegistry::getEntity(self::getName())) {
            return $type;
        }

        return GqlEntityRegistry::createEntity(self::getName(), new self([
            'name' => static::getName(),
            'fields' => SearchType::getFieldDefinitions(),
            'description' => 'This is the HistoryElement type.',
            'interfaces' => [
                SearchInterface::getType(),
            ],
            'resolveType' => function($value) {
                if ($value instanceof HistoryElement) {
                    return SearchType::getType();
                }
                return null;
            },
        ]));
    }
}