<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
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
        if(!$this->hasTable('categories')) {
            $table = $this->table('categories');
            $table->addColumn('name', 'string')
                ->addColumn('alias', 'string')
                ->addColumn('parent_id', 'integer', ['null' => true])
                ->addForeignKey('parent_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
                ->create();
        }
    }
}
