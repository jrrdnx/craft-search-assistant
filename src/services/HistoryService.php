<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\services;

use jrrdnx\searchassistant\SearchAssistant;
use jrrdnx\searchassistant\elements\HistoryElement;
use jrrdnx\searchassistant\records\HistoryRecord;

use Craft;
use craft\base\Component;
use craft\db\Table;
use craft\helpers\Db;
use craft\search\SearchQueryTerm;
use craft\search\SearchQueryTermGroup;
use DateTime;
use Dxw\CIDR\IP;

/**
 * History Service
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class HistoryService extends Component
{
    /**
     * Track searches
     */
    public function track($event): void
    {
        // Don't track if disabled
        if(!SearchAssistant::$plugin->getSettings()->getEnabled()) {
            return;
        }

        // Only track front-end requests
        if(!Craft::$app->request->getIsSiteRequest()) {
            return;
        }

        // Don't track if in IP ignore list
        if(self::checkIp(Craft::$app->getRequest()->getRemoteIP())) {
            return;
        }

        // Don't track if current user is logged in to control panel
        if(SearchAssistant::$plugin->getSettings()->getIgnoreCpUsers() && Craft::$app->getUser()->checkPermission('accessCp')) {
            return;
        }

        // Make sure we get the full search term and just the search term
        $keywords = $event->query->getQuery();
        foreach($event->query->getTokens() as $token) {
            if($token instanceof SearchQueryTermGroup) {
                foreach($token->terms as $term) {
                    if($term->phrase) {
                        $keywords = $term->term;
                    }
                }
            }
        }

        $search = HistoryElement::find()
            ->siteId(Craft::$app->sites->getCurrentSite()->id)
            ->pageUrl(explode('?', Craft::$app->getRequest()->getUrl())[0])
            ->keywords($keywords)
            ->one();

        if(!$search) {
            $search = new HistoryElement([
                'siteId' => (int)Craft::$app->sites->getCurrentSite()->id,
                'pageUrl' => (string)explode('?', Craft::$app->getRequest()->getUrl())[0],
                'keywords' => (string)$keywords,
                'numResults' => (int)count($event->results),
                'searchCount' => 1,
                'lastSearched' => Db::prepareDateForDb(new DateTime())
            ]);
            Craft::$app->elements->saveElement($search);
        } else {
            $search->numResults = count($event->results);
            $search->searchCount = $search['searchCount'] + 1;
            $search->lastSearched = Db::prepareDateForDb(new DateTime());
            Craft::$app->elements->saveElement($search);
        }
    }

    /**
     * @var bool
     */
    public static function checkIp($userIp): bool
    {
        foreach(SearchAssistant::$plugin->getSettings()->ipIgnore as $ipCidr) {
            $result = IP::contains($ipCidr[0], $userIp);
            $match = $result->unwrap();

            if ($match) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    public function getRecentSearches(array $options = [])
    {
        $sql = "
            SELECT
                `keywords`,
                MAX(lastSearched) AS `lastSearched`
            FROM
                ".HistoryRecord::tableName()." AS `history`,
                ".Table::ELEMENTS." AS `elements`
            WHERE
                `siteId` = ".Craft::$app->sites->getCurrentSite()->id."
                AND
                `history`.`id` = `elements`.`id`
                AND
                `elements`.`enabled` = 1
        ";

        if (!empty($options['pageUrl'])) {
            $sql .= "AND `history`.`pageUrl` = '".explode('?', $options['pageUrl'])[0]."'";
        }

        $sql .= "
                AND
                `history`.`numResults` > 0
            GROUP BY
                `keywords`
            ORDER BY
                `lastSearched` DESC
            LIMIT
                ".($options['limit'] ?? 5);

        $query = Craft::$app->getDb()->createCommand()->setSql($sql);

        return $query->queryAll();
    }

    /**
     *
     */
    public function getPopularSearches(array $options = [])
    {

        $query = HistoryElement::find()
            ->siteId(Craft::$app->sites->getCurrentSite()->id)
            ->popularSearches(true)
            ->numResults(true)
            ->limit($options['limit'] ?? 5);


        if (!empty($options['pageUrl'])) {
            $query->pageUrl(explode('?', $options['pageUrl'])[0]);
        }

        return $query->all();
    }

}
