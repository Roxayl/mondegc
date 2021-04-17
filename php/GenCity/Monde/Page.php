<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Page extends BaseModel {

    public function __construct($data = null) {

        $this->model = new PageModel($data);

    }

    static function getAllPages() {

        $sql = 'SELECT * FROM legacy_pages';
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

    public function updatePage($content) {

        $this->set('content', $content);
        $this->update();

    }

    public function update() {

        $sql = 'UPDATE legacy_pages SET content = %s WHERE this_id = %s';
        mysql_query(sprintf($sql,
            GetSQLValueString($this->model->content, 'text'),
            GetSQLValueString($this->model->this_id, 'text')));

    }

}