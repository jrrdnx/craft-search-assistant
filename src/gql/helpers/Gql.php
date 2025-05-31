<?php

namespace jrrdnx\searchassistant\helpers;

use jrrdnx\searchassistant\gql\GqlPermissions;

use Craft;
use craft\helpers\Gql as GqlHelper;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.2.0
 */
class Gql extends GqlHelper
{
    public static function canQuerySearches(): bool
    {
        $allowedEntities = self::getAllowedEntities();
        return in_array(GqlPermissions::PERMISSION_SEARCHES_READ, $allowedEntities);
    }

    public static function canQueryPopularSearches(): bool
    {
        $allowedEntities = self::getAllowedEntities();
        return in_array(GqlPermissions::PERMISSION_SEARCHES_POPULAR, $allowedEntities);
    }

    public static function canQueryRecentSearches(): bool
    {
        $allowedEntities = self::getAllowedEntities();
        return in_array(GqlPermissions::PERMISSION_SEARCHES_RECENT, $allowedEntities);
    }

    private static function getAllowedEntities(): array
    {
        try {
            return Craft::$app->getGql()->getActiveSchema()?->scope ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }
}