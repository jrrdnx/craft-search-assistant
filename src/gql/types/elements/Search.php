<?php

namespace jrrdnx\searchassistant\gql\types\elements;

use jrrdnx\searchassistant\elements\HistoryElement;
use jrrdnx\searchassistant\gql\interfaces\elements\Search as SearchInterface;

use craft\gql\GqlEntityRegistry;
use craft\gql\types\elements\Element;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

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
    private static string $typeName = 'Search';

    public static function getName(): string
    {
        return self::$typeName;
    }

    public static function getFieldDefinitions(): array
    {
        return SearchInterface::getFieldDefinitions();
    }

    /**
     * @inheritdoc
     */
    public function __construct(array $config)
    {
        $config['interfaces'] = [
            SearchInterface::getType(),
        ];

        $config['isTypeOf'] = function($value) {
            return $value instanceof HistoryElement;
        };

        parent::__construct($config);
    }

    /**
     * Get the Type instance for this element type
     */
    public static function getType($fields = null): Type
    {
        $typeName = self::getName();

        if ($type = GqlEntityRegistry::getEntity($typeName)) {
            return $type;
        }

        $type = GqlEntityRegistry::createEntity($typeName, new ObjectType([
            'name' => $typeName,
            'fields' => self::class . '::getFieldDefinitions',
            'description' => 'This is the Search type that implements the SearchInterface.',
            'interfaces' => [
                SearchInterface::getType(),
            ],
            'isTypeOf' => function($value) {
                return $value instanceof HistoryElement;
            },
        ]));

        return $type;
    }
}