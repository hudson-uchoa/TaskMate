<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTasksTable extends AbstractMigration
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
        $table = $this->table('tasks');

        $table
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('user_id', 'integer') 
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
            ->addColumn('priority', 'string', ['limit' => 20, 'default' => 'normal'])
            ->addColumn('due_date', 'timestamp', ['null' => true])
            ->addColumn('category', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('reminder_at', 'timestamp', ['null' => true])
            ->addColumn('completed_at', 'timestamp', ['null' => true])
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->create();
    }
}
