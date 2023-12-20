<?php

namespace common\helpers;

use yii\db\Migration;

class MigrationHelper
{
    public static function createForeignKeyAndIndex(Migration $migration, $ref_table, $local_column = null, $ref_column = "id", $delete = "CASCADE")
    {
        if (!$local_column) {
            $local_column = $ref_table."_id";
        }
        $migration->createIndex("idx-{$migration->tableName}-$local_column",
            $migration->tableName,
            $local_column
        );
        $migration->addForeignKey(
            "fk-{$migration->tableName}-$local_column",
            $migration->tableName,
            $local_column,
            $ref_table,
            $ref_column,
            $delete
        );
    }

    public static function dropForeignKeyAndIndex(Migration $migration, $local_column)
    {
        $migration->dropForeignKey(
            "fk-{$migration->tableName}-$local_column",
            $migration->tableName
        );

        $migration->dropIndex(
            "idx-{$migration->tableName}-$local_column",
            $migration->tableName
        );
    }

    public static function appendTimeColumns(Migration $migration)
    {
        $migration->addColumn($migration->tableName, 'created_at', $migration->integer()->comment("Время создания"));
        $migration->addColumn($migration->tableName, 'updated_at', $migration->integer()->comment("Обновленное время"));
        $migration->addColumn($migration->tableName, 'deleted_at', $migration->integer()->comment("Удаленное время"));
    }
}
