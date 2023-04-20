<?php

namespace GenCity\Monde;
use Squirrel\BaseModel;

class Page extends BaseModel
{
    public function __construct(int|string|array|null $data = null)
    {
        $this->model = new PageModel($data);
    }

    static function getAllPages(): array
    {
        $sql = 'SELECT * FROM legacy_pages';
        $query = mysql_query($sql);

        $return = [];
        while($row = mysql_fetch_assoc($query)) {
            $return[] = new Page($row['this_id']);
        }
        return $return;
    }

    public function content(): ?string
    {
        return $this->model->content;
    }

    public function updatePage(?string $content): void
    {
        $this->set('content', $content);
        $this->update();
    }

    public function update(): void
    {
        $sql = 'UPDATE legacy_pages SET content = %s WHERE this_id = %s';
        mysql_query(sprintf($sql,
            escape_sql($this->model->content, 'text'),
            escape_sql($this->model->this_id, 'text')));
    }
}
