<?php

use Phinx\Migration\AbstractMigration;

class WebSocial extends AbstractMigration
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
        $table = $this->table('web_social', array('id' => 'id', 'primary_key' => array('id')));
        $table->addColumn('uid', 'biginteger',array('limit'=>11,'signed'=>'enable'))
            ->addColumn('type', 'string',array('limit'=>10))
            ->addColumn('outh_token', 'text')
            ->addColumn('user_id', 'integer',array('limit'=>11))
            ->addForeignKey('user_id','web_users','id')
            ->addColumn('response','text')
            ->addColumn('expires','integer',array('limit'=>11))
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('web_social');
    }
}