<?php
/**
 * @link https://jarrodnix.dev/
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\migrations;

use jrrdnx\searchassistant\records\HistoryRecord;

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
