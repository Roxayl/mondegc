<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Page extends BaseModel {

    public function __construct($data = null) {

        $this->model = new PageModel($data);

    }

    static function getAllPages() {

        $sql = 'SELECT * FROM pages';
        $query = mysql_query($sql);

        $return = array();
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Page($row['this_id']);
        }
        return $return;

    }

    public function content() {

        return $this->model->content;

    }

    public function update($content) {

        $sql = 'UPDATE pages SET content = %s WHERE this_id = %s';
        mysql_query(sprintf($sql,
            GetSQLValueString($content, 'text'),
            GetSQLValueString($this->model->this_id, 'text')));
    }

}