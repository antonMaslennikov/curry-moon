<?php

    namespace admin\application\models;

    use \smashEngine\core\App AS App; 
    use admin\application\helpers\Pagination;
    
    use \PDO;

    class mailSubscription extends \application\models\mailSubscription
    { 
        protected $bind_array = null;
        
        public function getList()
        {
            $sql = 'SELECT {select} FROM {table} {where} ORDER BY `time` DESC';
                
            $sql = str_replace('{table}', self::db(), $sql);
            $sql = str_replace('{where}', 'WHERE 1', $sql);
            
            $this->bind_array = [];
            
            $smt = App::db()->prepare(str_replace('{select}', 'count(*) as c', $sql));
            $smt->execute($this->bind_array);

            $total = $smt->fetch();
            $total = $total['c'];

            $this->pagination = new Pagination($total, isset($_GET['pageSize'])?$_GET['pageSize']:200);

            $sql .= $this->pagination->applyLimit();

            $sql = str_replace('{select}', '*', $sql);

            $stmt = App::db()->prepare($sql);

            $stmt->execute($this->bind_array);
            $temp = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [];
            foreach ($temp as $k => $v) {
                $v['k'] = $total - ($this->pagination->getCurrentPage() * $this->pagination->getPageSize()) - $k;

                $data[$v['id']] = $v;
            }

            return [
                'page'=>$this->pagination->getTemplate(),
                'search'=>$this->search,
                'data'=>$data
            ];
        }
        
        
    }