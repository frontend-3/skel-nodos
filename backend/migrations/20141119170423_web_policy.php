<?php

use Phinx\Migration\AbstractMigration;

class WebPolicy extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('web_policy', array('id' => 'id', 'primary_key' => array('id')));
        $table->addColumn('slug', 'string',array('limit'=>200))
            ->addColumn('title', 'string',array('limit'=>200))
            ->addColumn('content', 'text')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addIndex(array('slug'), array('unique' => true))
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('web_policy');
    }
}
