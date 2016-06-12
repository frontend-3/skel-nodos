<?php

use Phinx\Migration\AbstractMigration;

class WebUbigeoDistricts extends AbstractMigration
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
        $table = $this->table('web_ubigeo_districts', array('id' => 'id', 'primary_key' => array('id')));
        $table->addColumn('name', 'string',array('limit'=>100))
            ->addColumn('cod_dpto', 'integer',array('limit'=>11))
            ->addForeignKey('cod_dpto','web_ubigeo_departments','id')
            ->addColumn('cod_prov', 'integer',array('limit'=>11))
            ->addForeignKey('cod_prov','web_ubigeo_provinces','id')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('web_ubigeo_districts');
    }
}