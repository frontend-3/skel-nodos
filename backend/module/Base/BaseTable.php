<?php

namespace Base;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

require_once('functions.php');

class BaseTable {
    public $tableName;
    public $tableGateway;
    protected $adapter;
    protected $model;
    
    public function __construct($dbAdapter, $model){
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
        $this->model = $model;
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($model);
        $this->tableName = $model->db_table;
        $this->tableGateway = new TableGateway(
            $this->tableName, $this->dbAdapter, null, $resultSetPrototype);
    }

    public function fetchAll($paginated = false, $condition=null) {
        if ($paginated) {
            $model = new $this->model();
            $select = new Select($this->tableName);
            if(!is_null($condition)){
                $select->where($condition);
            }
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype($model);
            $paginatorAdapter = new DbSelect(
                $select, $this->tableGateway->getAdapter(), $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        return $resultSet = $this->tableGateway->select();
    }

    public function all($columns=null, $condition=null, $order=null, $group=null) {
        if(is_array($columns)){
            $select = new Select();
            $select->from($this->tableName);
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
            $statement = $this->sql->prepareStatementForSqlObject($select);
            return $statement->execute();
        }
        return $this->tableGateway->select(function (Select $select) use ($condition,$order,$group){
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
        return $this->sql->getSqlstringForSqlObject($select);
    }

    public function first($condition=null, $columns=null){
        if(is_array($columns)){
            $select = new Select();
            $select->from($this->tableName);
            $select->columns($columns);
            if(!is_null($condition)){
                $select->where($condition);
            }
            $statement = $this->sql->prepareStatementForSqlObject($select);
            return $statement->execute()->current();
        }
        return $this->tableGateway->select(function (Select $select) use ($condition){
            $select->where($condition);
        })->current();
    }

    public function delete($condition=null){
        if(!is_null($condition)){
            $this->tableGateway->delete($condition);
        }
    }

    public function delete_all($adapter, $table_name='') {
        $sql = new Sql($adapter);
        if(!is_null($this->tableName)){
            $table_name = $this->tableName;
        }
        $select = $sql->delete($table_name);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
    }

    private function toArray($model){
        $objs = object_to_array($model);
        $data = array();
        foreach ($objs as $key=>$value) {
            if(!is_null($value)){
                $data[$key]=$value;
            }
        }
        unset($data['add_updated']);
        unset($data['slug_field']);
        unset($data['csrf_token']);
        unset($data['db_table']);
        unset($data['add_created']);
        unset($data['date_format']);

        return $data;
    }

    public function get_slug($field, $exclude_id = 0) {
        $slug = $this->slugify($field);
        $query = "select * from " . $this->tableName . " where slug REGEXP '^{$slug}(-[0-9]*)?$'";
        if($exclude_id>0){
            $query.= " AND id != $exclude_id";
        }
        $slugCount = $this->tableGateway->getAdapter()->query($query, array())->count();
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    public function slugify($title) {
        $title=strtr(utf8_decode($title), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $slug = trim($title); // trim the string
        $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug); // only take alphanumerical characters, but keep the spaces and dashes too...
        $slug = str_replace(' ', '-', $slug); // replace spaces by dashes
        $slug = strtolower($slug);  // make it lowercase
        return $slug;
    }

    public function save_array($data, $type = ''){
        $time = date('Y-m-d H:i:s');
        $id = 0;
        if(!array_key_exists('id',$data)){
            if($this->model->add_updated){
                $data['created_at']=$time;
                $data['updated_at']=$time;
            }
        }else{
            if($this->model->add_updated){
                $data['updated_at'] = $time;
            }
            $id = $data['id'];
        }

        if ($id == 0 || $type =='load') {
            try {
                $this->tableGateway->insert($data);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            $id = $this->tableGateway->lastInsertValue;
        } else {
            if ($this->first(array($id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $id;
    }

    public function save($model,$load='') {
        $data = $this->toArray($model);
        $id = !array_key_exists('id', $data) ? 0 : $data['id'];
        $time = date('Y-m-d H:i:s');
        if($model->slug_field!=''){
            $data['slug'] = $this->get_slug($data[$this->model->slug_field],$id);
        }
        if ($id == 0 || $load=='load') 
        {
            if($this->model->add_updated){
                $data['created_at']=$time;
                $data['updated_at']=$time;
            }
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
        } 
        else 
        {
            if($this->model->add_updated){
                $data['updated_at']=$time;
            }
            $this->tableGateway->update($data, array('id' => $id));
        }
        return $id;
    }

    public function insert($model) {
        $data = $this->toArray($model);
        $time = date('Y-m-d H:i:s');
        $data['created_at']=$time;
        $this->tableGateway->insert($data);
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }

} 