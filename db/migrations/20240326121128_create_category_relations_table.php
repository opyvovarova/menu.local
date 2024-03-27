<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoryRelationsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
       // Создание таблицы  для хранения связей между категориями
        if(!$this->hasTable('category_relations')) {
            $table = $this->table('category_relations');
            $table->addColumn('parent_id', 'integer')
                  ->addColumn('child_id', 'integer')
                   ->addForeignKey('parent_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                   ->addForeignKey('child_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                   ->create();
        }
    }
}
