<?php

namespace jrrdnx\searchassistant\gql;

use jrrdnx\searchassistant\gql\types\elements\SearchType;
use jrrdnx\searchassistant\gql\interfaces\elements\Search as SearchInterface;
use jrrdnx\searchassistant\gql\queries\SearchQueries;
use jrrdnx\searchassistant\gql\types\generators\HistoryElementType;

use craft\events\RegisterGqlTypesEvent;
use craft\events\RegisterGqlQueriesEvent;
use craft\services\Gql;
use craft\gql\TypeLoader;
use craft\gql\GqlEntityRegistry;
use yii\base\Event;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class RegisterGqlTypes
{
    public static function init(): void
    {
        // Register queries first
        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_QUERIES,
            function(RegisterGqlQueriesEvent $event) {
                $queries = SearchQueries::getQueries();
                if (!empty($queries)) {
                    foreach ($queries as $key => $value) {
                        $event->queries[$key] = $value;
                    }
                }
            }
        );

        // Register interface and types
        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_TYPES,
            function(RegisterGqlTypesEvent $event) {
                self::registerTypes($event);
            }
        );
    }

    public static function registerTypes(RegisterGqlTypesEvent $event): void
    {
        // Register the interface
        $interfaceName = SearchInterface::getName();
        if (!GqlEntityRegistry::getEntity($interfaceName)) {
            TypeLoader::registerType($interfaceName, fn() => SearchInterface::getType());
        }

        // Register the concrete type
        $typeName = SearchType::getName();
        if (!GqlEntityRegistry::getEntity($typeName)) {
            TypeLoader::registerType($typeName, fn() => SearchType::getType());
        }

        // Register the interface and concrete type with the GQL system
        $event->types[] = SearchInterface::class;
        $event->types[] = SearchType::class;

        // Generate and register types from the generator
        $types = HistoryElementType::generateTypes();
        foreach ($types as $name => $type) {
            if (!GqlEntityRegistry::getEntity($name)) {
                GqlEntityRegistry::createEntity($name, $type);
            }
        }
    }
}