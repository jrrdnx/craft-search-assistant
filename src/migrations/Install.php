<?php
/**
 * @link https://jarrodnix.dev/
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\migrations;

use jrrdnx\searchassistant\elements\HistoryElement;
use jrrdnx\searchassistant\records\HistoryRecord;

use Craft;
use craft\db\Migration;
use craft\records\Element;

/**
 * Installation Migration
 *
 * @author Jarrod D Nix
 * @since 1.0.0
 */
class Install extends Migration
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        return (
            $this->createTableHistory()
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        // Delete all HistoryElement entries from `elements` table
        $historyElements = HistoryElement::find()->all();
        foreach($historyElements as $historyElement) {
            Craft::$app->elements->deleteElement($historyElement);
        }

        // Drop table
        $this->dropTableIfExists(HistoryRecord::tableName());

        return true;
    }

    public function createTableHistory(): bool
    {
        $table = HistoryRecord::tableName();

        if(!$this->db->tableExists($table)) {
            $this->createTable($table, [
                'id' => $this->primaryKey(),
                'siteId' => $this->integer()->notNull(),
                'pageUrl' => $this->string()->notNull(),
                'keywords' => $this->string()->notNull(),
                'numResults' => $this->integer()->notNull(),
                'searchCount' => $this->integer()->notNull(),
                'lastSearched' => $this->dateTime()->notNull(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid'=> $this->uid(),
            ]);

            $this->createIndex(null, $table, [
                'siteId',
                'pageUrl',
                'keywords'
            ], true);
            $this->createIndex(null, $table, 'numResults');
            $this->createIndex(null, $table, 'searchCount');

            // Give it a foreign key to the elements table:
            $this->addForeignKey(
                null,
                $table,
                'id',
                Element::tableName(),
                'id',
                'CASCADE',
                null
            );
        }

        return true;
    }
}
