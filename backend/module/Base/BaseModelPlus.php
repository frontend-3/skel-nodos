<?php

namespace Base;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Files\Model\File;

class BaseModelPlus {
    public $id;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;

    public $_table_name;
    public $_has_created_at = true;
    public $_has_updated_at = true;
    public $_has_created_by = false;
    public $_has_updated_by = false;
    public $_slug_field;
    public $_tableGateway;
    protected $_dbAdapter;

    public function __construct($dbAdapter, $table_name) {
        $this->_table_name = $table_name;
        $this->_dbAdapter  = $dbAdapter;
        $this->_sql        = new Sql($dbAdapter);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($this);

        $this->_tableGateway = new TableGateway(
            $this->_table_name, $this->_dbAdapter, null, $resultSetPrototype
        );
    }


    public function getTableName(){
        return $this->_table_name;
    }


    public function exchangeArray($data){
        $obj      = (object)$data;
        $keys     = (get_object_vars($obj));
        $keys_obj = array_keys(get_object_vars($this));

        foreach ($keys as $key => $value) {
            if($value != 'empty' && $value !== ''){
                if(in_array($key, $keys_obj)){
                    $this->{$key} = $value;
                }
            }
        }
    }


    public function exchangeArray2($data){
        $obj      = (object)$data;
        $keys     = (get_object_vars($obj));
        $keys_obj = array_keys(get_object_vars($this));

        foreach ($keys as $key => $value) {
            if(in_array($key, $keys_obj)){
                $this->{$key} = $value;
            }
        }
    }


    public function getArrayCopy() {
        return get_object_vars($this);
    }


    public function fetchAll($paginated = false, $condition = null, $order = null) {
        if($paginated) {
            $select = new Select($this->_table_name);
            if(!is_null($condition)){
                $select->where($condition);
            }
            if(!is_null($order)){
                $select->order($order);
            }
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype($this);
            $paginatorAdapter = new DbSelect(
                $select, $this->_tableGateway->getAdapter(), $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        return $resultSet = $this->_tableGateway->select();
    }


    public function all($columns = null, $condition = null, $order = null, $group = null) {
        if(is_array($columns)){
            $select = new Select();
            $select->from($this->_table_name);
            $select->columns($columns);

            if(!is_null($condition)){
                $select->where($condition);
            }

            if(!is_null($order)){
                $select->order($order);
            }

            if(!is_null($group)){
                $select->group($order);
            }

            $statement = $this->_sql->prepareStatementForSqlObject($select);

            return $statement->execute();
        }
        return $this->_tableGateway->select(function (Select $select) use ($condition, $order, $group){
            if(!is_null($condition)){
                $select->where($condition);
            }
            if(!is_null($order)){
                $select->order($order);
            }
            if(!is_null($group)){
                $select->group($order);
            }
        });
    }


    private function getPrintSQL($select){
        return $this->_sql->getSqlstringForSqlObject($select);
    }


    public function find($id){
        $select = new Select();
        $select->from($this->_table_name);
        $select->where(array('id' => $id));
        $statement = $this->_sql->prepareStatementForSqlObject($select);
        return $statement->execute()->current();
    }


    public function first($condition = null, $columns = null){
        if(is_array($columns)){
            $select = new Select();
            $select->from($this->_table_name);
            $select->columns($columns);

            if(!is_null($condition)){
                $select->where($condition);
            }

            $statement = $this->_sql->prepareStatementForSqlObject($select);

            return $statement->execute()->current();
        }
        return $this->_tableGateway->select(function (Select $select) use ($condition){
            $select->where($condition);
        })->current();
    }


    public function get_by_sql($sql) {
        return $this->_tableGateway->getAdapter()->query($sql, array())->toArray();
    }


    public function get_first_by_sql($sql) {
        return $this->_tableGateway->getAdapter()->query($sql, array())->current();
    }


    public function delete($condition){
        $this->_tableGateway->delete($condition);
    }


    public function delete_all() {
        $this->_tableGateway->delete(1);
    }


    public function toArray($model){
        $objs = object_to_array($model);
        $data = array();
        foreach ($objs as $key => $value) {
            if(!is_null($value)){
                $data[$key] = $value;
            }
        }

        unset($data['csrf_token']);
        //unset($data['db_table']);
        unset($data['_has_created_at']);
        unset($data['_has_updated_at']);
        unset($data['_has_created_by']);
        unset($data['_has_updated_by']);
        unset($data['_slug_field']);
        unset($data['_dbAdapter']);
        unset($data['_tableGateway']);
        unset($data['_sql']);
        unset($data['_table_name']);

        return $data;
    }


    public function get_slug($field, $exclude_id = 0) {
        $slug = $this->slugify($field);

        $query = "
            SELECT count(id) cantidad 
              FROM " . $this->_table_name . "
             WHERE slug REGEXP '^" . $slug . "(-[0-9]*)?$'
        ";

        if ($exclude_id > 0) {
            $query .= " AND id != $exclude_id";
        }

        $slugCount = $this->_tableGateway->getAdapter()->query($query, array())->current();
        
        return ($slugCount->cantidad > 0) ? "{$slug}-" . $slugCount->cantidad : $slug;
    }


    public function slugify($title) {
        return slugify($title);
    }


    public function save_array($data, $type = ''){
        $time = date('Y-m-d H:i:s');
        $id = 0;

        if(!array_key_exists('id',$data)) {
            if($this->_has_created_at) {
                $data['created_at']=$time;
                $data['updated_at']=$time;
            }
        }
        else {
            if($this->_has_updated_at) {
                $data['updated_at'] = $time;
            }

            $id = $data['id'];
        }

        if ($id == 0 || $type =='load') {
            try {
                $this->_tableGateway->insert($data);
            }
            catch (\Exception $e) {
                echo $e->getMessage();
            }
            $id = $this->_tableGateway->lastInsertValue;
        }
        else {
            if($this->first(array($id))) {
                $this->_tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $id;
    }


    public function save() {
        $data = $this->toArray($this);
        $id   = !array_key_exists('id', $data) ? 0 : $data['id'];
        $time = date('Y-m-d H:i:s');

        if($this->_slug_field != '') {
            $data['slug'] = $this->get_slug($data[$this->_slug_field], $id);
        }

        if ($id == 0) {
            if($this->_has_created_at) {
                $data['created_at'] = $time;
                $data['updated_at'] = $time;
            }

            $this->_tableGateway->insert($data);
            $id = $this->_tableGateway->lastInsertValue;
            $this->id = $id;
        }
        else {
            if($this->_has_updated_at) {
                $data['updated_at'] = $time;
            }
            $this->_tableGateway->update($data, array('id' => $id));
        }

        return $id;
    }


    public function insert($model) {
        $data               = $this->toArray($model);
        $time               = date('Y-m-d H:i:s');
        $data['created_at'] = $time;

        $this->_tableGateway->insert($data);

        return $this->_tableGateway->lastInsertValue;
        return $id;
    }


    public function load_files() {
    // Convenience function for easy loading of Files, provided by the Files
    // Module. Be sure you have added it to the project.
        $fm = new File($this->_dbAdapter);

        $fs = $fm->all(NULL, array('table_name' => $this->getTableName(), 'table_id' => $this->id));
        $r  = array();

        foreach($fs as $f) {
            $this->{$f->name} = $f; // It's magic!
        }

        return $this;
    }


    public function files() {
        if(!isset($this->__files)) {
            $fm = new File($this->_dbAdapter);
            $this->__files = $fm->get_object_for($this->getTableName(), $this->id);
        }

        return $this->__files;
    }


    public function has_file($name) {
        $this->files();

        return isset($this->files()->$name);
    }


    /**
     * Consulta la cantidad de registros
     * en la entidad de la base de datos
     * de forma condicionada y agrupada
     *
     * @return int
     */

    public function count($condition = null,$group = null){
        $select = new Select();
        $select->from($this->_table_name);
        $select->columns(array('num'=> new Expression('COUNT(*)')), false);

        if(!is_null($condition)){
            $select->where($condition);
        }

        if(!is_null($group)){
            $select->group($group);
        }
        $statement = $this->_sql->prepareStatementForSqlObject($select);

        return $statement->execute()->current()['num'];
    }


    /**
     * Consulta si un determinado registro existe o no
     * en la entidad de la base de datos
     *
     * @return boolean
     */
    function exists($where_pk = '')
    {
        $table = $this->_table_name;

        if ($where_pk) {
            if (is_numeric($where_pk)) {
                $query = "SELECT COUNT(*) as existe FROM $table WHERE id = '$where_pk'";
            } else {
                $query = "SELECT COUNT(*) as existe FROM $table WHERE $where_pk";
            }
        }
        $num = $this->get_first_by_sql($query);

        return $num->existe;
    }


    public function model($classname) {
        $classname = '\\' . $classname;
        return new $classname($this->_dbAdapter);
    }
}

?>
