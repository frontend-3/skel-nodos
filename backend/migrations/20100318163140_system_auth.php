<?php

use Phinx\Migration\AbstractMigration;

class SystemAuth extends AbstractMigration
{
    /**
    public function change()
    {
    }
    */
    
    public function up()
    {
    
        $table = $this->table('web_system_auth_roles', array('id' => 'id', 'primary_key' => array('id')));
        $table
            ->addColumn('name', 'string',array('limit'=>100))
            ->addColumn('created_at', 'datetime',array('default'=>null))
            ->addColumn('updated_at', 'datetime',array('default'=>null))
            ->save();

        $table = $this->table('web_system_auth_permissions', array('id' => 'id', 'primary_key' => array('id')));
        $table
            ->addColumn('name', 'string', array('limit'=>100))
            ->addColumn('label', 'string', array('limit'=>100))
            ->addColumn('status', 'boolean')
            ->addColumn('created_at', 'datetime',array('default'=>null))
            ->addColumn('updated_at', 'datetime',array('default'=>null))
            ->save();

        $table = $this->table('web_system_auth_role_persmissions', array('id' => false, 'primary_key' => array('role_id', 'permission_id')));
        $table
            ->addColumn('role_id', 'integer')
                ->addForeignKey('role_id','web_system_auth_roles','id')
            ->addColumn('permission_id', 'integer')
                ->addForeignKey('permission_id','web_system_auth_permissions','id')
            ->addIndex(array('permission_id', 'role_id'))
            ->save();

        $table = $this->table('web_system_auth_user', array('id' => 'id', 'primary_key' => array('id')));
        $table
            ->addColumn('first_name', 'string', array('limit' => 100))
            ->addColumn('last_name', 'string', array('limit' => 100))
            ->addColumn('email', 'string', array('limit' => 100))
            ->addColumn('username', 'string', array('limit' => 100))
            ->addColumn('password', 'string', array('limit' => 100))
            ->addColumn('status', 'enum', array('values' => array('enabled', 'disabled'), 'default' => 'disabled'))
            ->addColumn('is_superuser', 'boolean',array('default'=>false,'null'=>true))
            ->addColumn('last_login', 'datetime', array('null' => true))
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', array('null' => true))

            ->addColumn('role_id', 'integer')
                ->addForeignKey('role_id','web_system_auth_roles','id')


            ->addIndex(array('username'), array('unique' => true))
            ->addIndex(array('email'), array('unique' => true))
            ->addIndex(array('status'))

            ->addColumn('created_by', 'integer', array('null' => true))
            ->addColumn('updated_by', 'integer', array('null' => true))
            ->save();

            $table = $this->table('web_system_auth_user');
            $table
                ->addForeignKey('created_by','web_system_auth_user','id', array('delete'=> 'CASCADE'))
                ->addForeignKey('updated_by','web_system_auth_user','id', array('delete'=> 'CASCADE'))
                ->save();

    }

    public function down()
    {
        $this->dropTable('web_system_auth_permissions');
        $this->dropTable('web_system_auth_roles');
        $this->dropTable('web_system_auth_role_persmissions');
        $this->dropTable('web_system_auth_user');
    }
}