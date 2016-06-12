<?php

namespace Base;


class BaseModel 
{
    public $id;
    public $db_table;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;
    public $add_updated;
    public $slug_field;
    public $add_created;
    public $date_format;

    public function __construct($tablename,$updated=true,$slug_field='',$created=false,$date_format = null){
        $this->db_table = $tablename;
        $this->add_updated = $updated;
        $this->slug_field = $slug_field;
        $this->add_created = $created;
        $this->date_format = $date_format;
    }

    public function formatFieldDates()
    {
        if(!is_null($this->date_format)){
            foreach ($this->date_format as $key => $value) {
                if(!is_null($this->{$value})){
                    $this->{$value} = date('d/m/Y',strtotime($this->{$value}));    
                }
            }
        }
    }

    public function getTableName(){
        return $this->db_table;
    }

    public function exchangeArray($data){
        $obj = (object)$data;
        $keys=(get_object_vars($obj));
        $keys_obj = array_keys(get_object_vars($this));
        foreach ($keys as $key=>$value) {
            if($value != 'empty' && $value!=''){
                if(in_array($key,$keys_obj)){
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }
} 