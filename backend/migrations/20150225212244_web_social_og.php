<?php

use Phinx\Migration\AbstractMigration;

class WebSocialOg extends AbstractMigration
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
        $table = $this->table('web_social_og', array('id' => 'id', 'primary_key' => array('id')));
        $table->addColumn('share_name', 'string',array('limit'=>500,'null'=>true))
            ->addColumn('share_caption','string',array('limit'=>500, 'null'=>true))
            ->addColumn('share_description','text',array('null'=>true))
            ->addColumn('share_image','string',array('limit'=>500, 'null'=>true))
            ->addColumn('share_pla_title','string',array('limit'=>500, 'null'=>true))
            ->addColumn('share_pla_description','string',array('limit'=>500, 'null'=>true))
            ->addColumn('share_pla_image','string',array('limit'=>500, 'null'=>true))
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}