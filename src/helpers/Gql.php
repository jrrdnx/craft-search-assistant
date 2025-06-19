<?php

namespace jrrdnx\searchassistant\gql\helpers;

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
        $canQuery = in_array(GqlPermissions::PERMISSION_SEARCHES_READ, $allowedEntities);
        Craft::info('canQuerySearches: ' . ($canQuery ? 'true' : 'false'), __METHOD__);
        return $canQuery;
    }

    public static function canQueryPopularSearches(): bool
    {
        $allowedEntities = self::getAllowedEntities();
        $canQuery = in_array(GqlPermissions::PERMISSION_SEARCHES_POPULAR, $allowedEntities);
        Craft::info('canQueryPopularSearches: ' . ($canQuery ? 'true' : 'false'), __METHOD__);
        return $canQuery;
    }

    public static function canQueryRecentSearches(): bool
    {
        $allowedEntities = self::getAllowedEntities();
        $canQuery = in_array(GqlPermissions::PERMISSION_SEARCHES_RECENT, $allowedEntities);
        Craft::info('canQueryRecentSearches: ' . ($canQuery ? 'true' : 'false'), __METHOD__);
        return $canQuery;
    }

    private static function getAllowedEntities(): array
    {
        try {
            $schema = Craft::$app->getGql()->getActiveSchema();
            if (!$schema) {
                Craft::warning('No active GraphQL schema found', __METHOD__);
                return [];
            }
            $scope = $schema->scope ?? [];
            Craft::info('Active schema scope: ' . print_r($scope, true), __METHOD__);
            return $scope;
        } catch (\Exception $e) {
            Craft::error('Error getting GraphQL schema: ' . $e->getMessage(), __METHOD__);
            return [];
        }
    }
}