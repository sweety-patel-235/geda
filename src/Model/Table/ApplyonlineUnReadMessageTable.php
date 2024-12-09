<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
/**
 * @category  Class File
 * @author    Kalpak Prajapati
 * @version   GEDA
 * @since     File available since V1
 */
class ApplyonlineUnReadMessageTable extends AppTable
{
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'applyonline_unread_messages';
	public function initialize(array $config)
    {
		$this->table($this->table);
    }

    public function saveUnReadMessage($message_id=0,$message_for=0)
    {
    	$ApplyonlineUnReadMessageEntity				= $this->newEntity();
		$ApplyonlineUnReadMessageEntity->message_id = $message_id;
		$ApplyonlineUnReadMessageEntity->message_for= $message_for;
		$ApplyonlineUnReadMessageEntity->msg_read 	= 0;
		$ApplyonlineUnReadMessageEntity->created 	= $this->NOW();
		$this->save($ApplyonlineUnReadMessageEntity);
    }

    public function MarkAsRead($message_id=0)
    {
    	$MessageRow 			= $this->get($message_id);
    	$MessageRow->msg_read 	= 1;
    	$MessageRow->read_at  	= $this->NOW();
    	$this->save($MessageRow);
    }

    public function getUnreadMessageCount($message_for=0)
    {
    	$arrMessage     = $this->find('all',
                                            [
                                                'fields'=>['ApplyonlineUnReadMessage.id'],
                                                'conditions'=>['message_for'=>intval($message_for),"msg_read"=>0]
                                            ])->toArray();
		return sizeof($arrMessage);
    }

    public function GetUnreadMessages($message_for=0)
    {
    	$arrMessage     = $this->find('all',
                                        [
                                            'fields'=>[	'Unread_ID'=>'ApplyonlineUnReadMessage.id','ApplyonlineMessage.message','ApplyonlineMessage.created',
                                            			'ApplyonlineMessage.user_type','ApplyonlineMessage.ip_address',
                                            			'Msg_Query'=>'ApplyonlineMessageQuery.message'],
                                            'join'=>[
                                            			['alias'=>'ApplyonlineMessage','table'=>'applyonline_messages','type'=>'left','conditions'=>'ApplyonlineUnReadMessage.message_id = ApplyonlineMessage.id'],
                                            			['table'=>'members','type'=>'left','conditions'=>'ApplyonlineMessage.user_id = members.id'],
                                            			['alias'=>'ApplyonlineMessageQuery','table'=>'applyonline_messages','type'=>'left','conditions'=>'ApplyonlineMessage.reply_msg_id = ApplyonlineMessageQuery.id'],
                                            		],
                                            'conditions'=>['ApplyonlineUnReadMessage.message_for'=>$message_for,'ApplyonlineUnReadMessage.msg_read'=>0],
                                            'order'=>['ApplyonlineMessage.id'=>'DESC']
                                        ]);
		return $arrMessage->toArray();
    }
}
