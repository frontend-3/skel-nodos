<?php

// Library of global functions

function or_set(&$a, $b) {
// A poor replacement for Ruby's a ||= b
// Assigns the value to "a" unless it already exists
    if(!isset($a)) {
      $a = $b;
    }
}


function reverse_merge(&$a, $b) {
// Does an or_set() on an array. Usable for setting default options
// with arrays.
    foreach($b as $k => $v) {
        if(isset($a[$k]) && is_array($a[$k])) {
            if(!empty($v)) {
                reverse_merge($a[$k], $v);
            }
        }
        else {
            or_set($a[$k], $v);
        }
    }
}


function slugify($title) {
    $title = strtr(utf8_decode($title), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $slug = trim($title);
    $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug); // Only take alphanumerical characters, but keep the spaces and dashes too...
    $slug = str_replace(' ', '-', $slug); // Replace spaces by dashes
    $slug = preg_replace('/-+/', '-', $slug); // Replace multiple dashes with one
    $slug = strtolower($slug);  // Make it lowercase
    
    return $slug;
}


function object_to_array($data) {
    if (is_array($data) || is_object($data)) {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}


function parse_to_sql_date($date) {
    // Parses a date in the format "dd/mm/yyyy" and returns a SQL-friendly date
    if(preg_match("/\A([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $date, $m)) {
        $day   = $m[1];
        $month = $m[2];
        $year  = $m[3];
        
        return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
    }
    
    return false;
}


function parse_sql_date_to_date($date) {
    // Parses an SQL date to the format "dd/mm/yyyy"
    if(preg_match("/\A([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m)) {
        $year  = $m[1];
        $month = $m[2];
        $day   = $m[3];
        
        return $day . '/' . $month . '/' . $year;
    }
    
    return false;
}

