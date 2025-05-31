<?php

namespace jrrdnx\searchassistant\gql;

use craft\events\RegisterGqlSchemaComponentsEvent;
use craft\services\Gql;
use yii\base\Event;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class GqlPermissions
{
    // Permission constants
    public const PERMISSION_SEARCHES_READ = 'searchAssistant.searches:read';
    public const PERMISSION_SEARCHES_POPULAR = 'searchAssistant.searches:popular';
    public const PERMISSION_SEARCHES_RECENT = 'searchAssistant.searches:recent';

    public static function init(): void
    {
        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_SCHEMA_COMPONENTS,
            function(RegisterGqlSchemaComponentsEvent $event) {
                $event->queries['Search Assistant'] = [
                    self::PERMISSION_SEARCHES_READ => [
                        'label' => 'View all searches',
                        'description' => 'Allows querying for all searches.',
                    ],
                    self::PERMISSION_SEARCHES_POPULAR => [
                        'label' => 'View popular searches',
                        'description' => 'Allows querying for popular searches.',
                    ],
                    self::PERMISSION_SEARCHES_RECENT => [
                        'label' => 'View recent searches',
                        'description' => 'Allows querying for recent searches.',
                    ],
                ];
            }
        );
    }
}