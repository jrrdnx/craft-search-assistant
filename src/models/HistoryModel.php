<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\models;

use craft\base\Model;
use DateTime;

/**
 * History Model
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class HistoryModel extends Model
{
	public int $id;
    public int $siteId;
	public string $pageUrl;
	public string $keywords;
	public int $numResults;
    public int $searchCount;
    public DateTime $lastSearched;
	public DateTime $dateCreated;
    public DateTime $dateUpdated;

	public function init(): void
	{
		parent::init();
	}

	public function rules(): array
	{
		return [
			[['id', 'siteId', 'numResults', 'searchCount'], 'int'],
			[['pageUrl', 'keywords'], 'string'],
			[['id', 'siteId', 'pageUrl', 'keywords', 'numResults', 'lastSearched', 'dateCreated', 'dateUpdated'], 'required'],
		];
	}
}