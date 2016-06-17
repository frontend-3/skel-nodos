<?php

namespace Files\Model;
use Base\BaseModelPlus;

class File extends BaseModelPlus {
    public $name;
    public $path;
    public $size;
    public $width;
    public $height;
    public $table_id;
    public $table_name;
    public $created_at;
    public $filename;
    public $position;

    public function __construct($dbAdapter){
        parent::__construct($dbAdapter, 'web_files', array());
    }
    
    
    public function get_file_for($table_name, $table_id, $name) {
        return $this->first(array('table_name' => $table_name, 'table_id' => $table_id, 'name' => $name));
    }
    
    
    public function fullname() {
        return $this->path . $this->filename;
    }
    
    
    public function get_object_for($table_name, $table_id) {
        $files = $this->all(NULL, array('table_name' => $table_name, 'table_id' => $table_id));
        
        $list = array();
        
        if($files === false) {
            return new \stdClass();
        }
        
        foreach($files as $file) {
           $list[$file->name][] = $file;
        }
        
        $r = new \stdClass();
        
        foreach($list as $name => $l) {
            if(count($l) > 1) {
                $r->$name = $l;
            }
            else {
                $r->$name = $l[0];
            }
        }
        
        return $r;
    }
}

?>