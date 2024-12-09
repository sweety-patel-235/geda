<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\Query;

class AutoProcessLogsTable extends Table
{
    public function GetLastProcessedPageId($process_name)
    {
        $current_datetime       = date_create(date('Y-m-d H:i:s'));
        $query = $this->find('all')->where(['process_name' => $process_name]);

        $autolog = $query->first();

        if(!empty($autolog)) {
            if(empty($autolog->last_page_no)) {
                $diff       = date_diff($autolog->updated, $current_datetime);
                $days_diff  = $diff->format('%d');
                if($days_diff >=15) {
                    return 1;
                }
            }
            return $autolog->last_page_no;
        } else {
            $AutoProcessLog                 = $this->newEntity();

            $AutoProcessLog->process_name   = $process_name;
            $AutoProcessLog->last_page_no   = 1;
            $AutoProcessLog->created        = date('Y-m-d H:i:s');
            $AutoProcessLog->updated        = date('Y-m-d H:i:s');

            if ($this->save($AutoProcessLog)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function UpdateAutoProcessLogLastPage($script_name, $page_no)
    {
        $this->updateAll(['last_page_no' => $page_no, 'updated' => date('Y-m-d H:i:s')], ['process_name' => $script_name]);
    }
}