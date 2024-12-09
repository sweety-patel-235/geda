<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ApplyonlineMessageTable extends AppTable
{
	var $table = 'applyonline_messages';
    public function initialize(array $config)
    {
        $this->table($this->table);
    }

    public function GetLastMessageByApplication($application_id=0,$message_id=0,$member_id=0)
    {
        $apply_online   = TableRegistry::get('ApplyOnlines');
        $apply_details  = $apply_online->find('all',array('conditions'=>array('id'=>$application_id)))->first();
        if (empty($message_id)) {
            $arrMessage = $this->find('all',
                                            [
                                                'fields'=>['ApplyonlineMessage.message','ApplyonlineMessage.created','ApplyonlineMessage.user_type','ApplyonlineMessage.ip_address',
                                                'members.name','members.member_type','members.area','members.circle','members.division',
                                                'members.subdivision','members.section','branch_masters.title'],
                                                'join'=>[['table'=>'members','type'=>'left','conditions'=>'ApplyonlineMessage.user_id = members.id'],
                                                ['table'=>'branch_masters','type'=>'left','conditions'=>'members.area = branch_masters.discom_id']],
                                                'conditions'=>['ApplyonlineMessage.application_id'=>$application_id],
                                                'order'=>['ApplyonlineMessage.id'=>'DESC']
                                            ])->first();
        } else {
            $arrMessage = $this->find('all',
                                            [
                                                'fields'=>['ApplyonlineMessage.message','ApplyonlineMessage.created','ApplyonlineMessage.user_type','ApplyonlineMessage.ip_address',
                                                'members.name','members.member_type','members.area','members.circle','members.division',
                                                'members.subdivision','members.section','branch_masters.title'],
                                                'join'=>[['table'=>'members','type'=>'left','conditions'=>'ApplyonlineMessage.user_id = members.id'],
                                                ['table'=>'branch_masters','type'=>'left','conditions'=>'members.area = branch_masters.discom_id']],
                                                'conditions'=>['ApplyonlineMessage.id'=>$message_id],
                                                'order'=>['ApplyonlineMessage.id'=>'DESC']
                                            ])->first();
        }
        $arrResult  = array();
        if (!empty($arrMessage))
        {
            $branch_masters_title   = $arrMessage->branch_masters['title'];
            if ($arrMessage->user_type == 0) {
                $arrResult['comment_by'] = "Installer";
            } else {
                $comment_by = " - ";
                switch ($arrMessage->members['member_type']) {
                    case '6001':
                        $comment_by = "GEDA";
                        $branch_masters_title   = 'Gujarat Energy Development Agency (GEDA)';
                        break;
                    case '6002':
                        $comment_by = "DISCOM";
                        break;
                    case '6002':
                        $comment_by = "CEI";
                        break;
                }
                if(!empty($member_id) && in_array($member_id, $apply_online->ALLOWED_APPROVE_GEDAIDS))
                {
                    $comment_by = $arrMessage->members['name'];
                }
                if ($arrMessage->members['member_type'] == 6002) {
                    $area 			= $arrMessage->members['area'];
                    $circle 		= $arrMessage->members['circle'];
                    $division 		= $arrMessage->members['division'];
                    $subdivision 	= $arrMessage->members['subdivision'];
                    $section 		= $arrMessage->members['section'];
                    if (!empty($section)) {
                        $comment_by .= " / Section";
                    } else if (!empty($subdivision)) {
                        $comment_by .= " / Subdivision";
                    } else if (!empty($division)) {
                        $comment_by .= " / Division";
                    } else if (!empty($circle)) {
                        $comment_by .= " / Circle";
                    } else if (!empty($circle)) {
                        $comment_by .= " / Area";
                    }
                }

                $arrResult['comment_by']    =  $branch_masters_title." (".$comment_by.")";
            }
            $arrResult['ip_address']        = $arrMessage->ip_address;
            $arrResult['message']           = nl2br($arrMessage->message);
            $arrResult['created']           = date(LIST_DATE_FORMAT,strtotime($arrMessage->created));
        }
        return $arrResult;
    }

    public function GetAllMessagesById($application_id=0,$member_id=0)
    {
        $apply_online   = TableRegistry::get('ApplyOnlines');
        $arrMessages    = $this->find('all',
                                        [
                                            'fields'=>['ApplyonlineMessage.message','ApplyonlineMessage.created','ApplyonlineMessage.user_type','ApplyonlineMessage.ip_address',
                                            'members.name','members.member_type','members.area','members.circle','members.division',
                                            'members.subdivision','members.section'],
                                            'join'=>[['table'=>'members','type'=>'left','conditions'=>'ApplyonlineMessage.user_id = members.id']],
                                            'conditions'=>['ApplyonlineMessage.application_id'=>$application_id],
                                            'order'=>['ApplyonlineMessage.id'=>'DESC']
                                        ])->toArray();
        $arrResults  = array();
        if (!empty($arrMessages))
        {
            foreach($arrMessages as $arrMessage)
            {
                $arrResult = array();
                if ($arrMessage->user_type == 0) {
                    $arrResult['comment_by'] = "Installer";
                } else {
                    $comment_by = " - ";
                    switch ($arrMessage->members['member_type']) {
                        case '6001':
                            $comment_by = "GEDA";
                            break;
                        case '6002':
                            $comment_by = "DISCOM";
                            break;
                        case '6002':
                            $comment_by = "CEI";
                            break;
                    }
                    if(!empty($member_id) && in_array($member_id, $apply_online->ALLOWED_APPROVE_GEDAIDS))
                    {
                        $comment_by = $arrMessage->members['name'];
                    }
                    if ($arrMessage->members['member_type'] == 6002) {
                        $area 			= $arrMessage->members['area'];
                        $circle 		= $arrMessage->members['circle'];
                        $division 		= $arrMessage->members['division'];
                        $subdivision 	= $arrMessage->members['subdivision'];
                        $section 		= $arrMessage->members['section'];
                        if (!empty($section)) {
                            $comment_by .= " / Section";
                        } else if (!empty($subdivision)) {
                            $comment_by .= " / Subdivision";
                        } else if (!empty($division)) {
                            $comment_by .= " / Division";
                        } else if (!empty($circle)) {
                            $comment_by .= " / Circle";
                        } else if (!empty($circle)) {
                            $comment_by .= " / Area";
                        }
                    }
                    $arrResult['comment_by'] = "(".$comment_by.")";
                }
                $arrResult['ip_address']    = $arrMessage->ip_address;
                $arrResult['message']       = nl2br($arrMessage->message);
                $arrResult['created']       = date(LIST_DATE_FORMAT,strtotime($arrMessage->created));
                array_push($arrResults,$arrResult);
            }
        }
        return $arrResults;
    }

    public function GetLastMessageByApplicationForClaim($application_id=0,$for_claim=0)
    {
        $arrMessage     = $this->find('all',
                                            [
                                                'fields'=>['ApplyonlineMessage.id','ApplyonlineMessage.message','ApplyonlineMessage.for_claim'],
                                                'conditions'=>['ApplyonlineMessage.application_id'=>$application_id,"for_claim"=>$for_claim],
                                                'order'=>['ApplyonlineMessage.id'=>'DESC']
                                            ])->first();
        $arrResult  = array("last_message_id"=>0,"last_message"=>"");
        if (!empty($arrMessage)) {
            $arrResult['last_message_id']   = encode($arrMessage->id);
            $arrResult['last_message']      = nl2br($arrMessage->message);
        }
        return $arrResult;
    }

    public function QueryRaisedForSubsidy($application_id=0,$for_claim=0)
    {
        $RecordCount    = 0;
        $arrMessage     = $this->find('all',
                                            [
                                                'fields'=>['ApplyonlineMessage.id'],
                                                'conditions'=>['ApplyonlineMessage.application_id'=>$application_id,"for_claim"=>$for_claim],
                                                'order'=>['ApplyonlineMessage.id'=>'DESC']
                                            ])->first();
        if (!empty($arrMessage)) {
            $RecordCount = 1;
        }
        return $RecordCount;
    }
    public function QueryRaisedBy($application_id=0,$for_claim=0)
    {
        $RecordCount    = 0;
        $arrMessage     = $this->find('all',
                                            [
                                                'fields'=>['ApplyonlineMessage.id','members.name'],
                                                'conditions'=>['ApplyonlineMessage.application_id'=>$application_id,'ApplyonlineMessage.user_type !='=>'0',"for_claim"=>$for_claim],
                                                'join'=>[["table"=>'members',"type"=>'left','conditions'=>['ApplyonlineMessage.user_id = members.id']]],
                                                'order'=>['ApplyonlineMessage.id'=>'DESC']
                                            ])->first();
        
        return $arrMessage;
    }
}
?>