<?php

use Phinx\Migration\AbstractMigration;

class SeoContent extends AbstractMigration
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
        $table = $this->table('web_seo', array('id' => 'id', 'primary_key' => array('id')));
        $table
            ->addColumn('route', 'string', array('null' => true))
            ->addColumn('title', 'string', array('null' => true))
            ->addColumn('seo_title', 'string', array('null' => true))
            ->addColumn('seo_noindex', 'string', array('null' => true))
            ->addColumn('seo_description', 'string', array('null' => true))
            ->addColumn('seo_keywords', 'string', array('null' => true))
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', array('null' => true))
            ->addColumn('created_by', 'integer')
                ->addForeignKey('created_by','web_system_auth_user','id')
            ->addColumn('updated_by', 'integer', array('null' => true))
                ->addForeignKey('updated_by','web_system_auth_user','id')
            ->addIndex(array('route'))
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}