<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\records;

use craft\db\ActiveQuery;
use craft\db\ActiveRecord;
use craft\records\Element;

/**
 * Customers Record
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class HistoryRecord extends ActiveRecord
{
	public static function tableName()
	{
		return '{{%search_assistant_history}}';
	}

    public function getElement(): ActiveQuery
    {
        return $this->hasOne(Element::class, ['id' => 'id']);
    }
}