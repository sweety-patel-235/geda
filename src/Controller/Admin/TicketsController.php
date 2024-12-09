<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use App\Controller\Admin\DateTime;

class TicketsController extends AppController {
	
	/**
	 *
	 * The status of $CLOSE_TICKET is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
	var $CLOSE_TICKET=1;

	/**
	 *
	 * The status of $user_list is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
	var $user_list=array();

	/**
	 *
	 * The status of $arr is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
	var $arr=array();

	/**
	 *
	 * The status of $replay is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
	var $replay='';

	/**
	 *
	 * The status of $helpers is universe
	 *
	 * Potential value is array of helpers to be inherited
	 *
	 * @public array
	 *
	 */
	//public $helpers=array('Js','Time','Form','Userright','ExPaginator','TimeZone');
 	var $helpers = array('Time','Html','Form','ExPaginator');
    public function initialize()
    {
        // Always enable the CSRF component.
        parent::initialize();
        $this->loadComponent('Paginator');

        //$this->loadModel('Users');
        $this->loadModel('Tickets');
        $this->loadModel('TicketRemarks');
        $this->loadModel('Department');
        $this->loadModel('Users');
        $this->loadModel('Userroleright');
        $this->loadModel('Adminaction');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');

        $this->set('Userright',$this->Userright);
    }
	/**
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index()
	{
		//echo $this->Session->read('User.timezone');exit;

		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->LIST_TICKET;
		$this->setAdminArea();

		$arrTicketList		= array();
		$arrCondition		= array();
		$this->SortBy		= "Tickets.id";
		$this->Direction 	= "DESC";
		$this->intLimit		= 10;
		$option 			= array();
		$option['colName']	= array('id','to_id','created','action');

		$this->SetSortingVars('Tickets',$option);
		$arrCondition						= $this->_generateTicketSearchCondition();

		$arrCondition['Tickets.from_id'] 	= $this->Session->read('User.id');
		$arrCondition['Tickets.parent_id'] 	= 'NULL';
		
		//$arrCondition['between'] 			= array('Tickets.created','2015-05-20 06:59:59','2015-05-22 06:59:59');

		//$arrCondition['Tickets.created'] = "2015-05-21 06:59:59";

		//$arrCondition['Tickets.created BETWEEN ? and ?'] = array('2015-05-21 06:59:59','2015-05-22 06:59:59');
		/*$arrCondition['Tickets.created <='] = '2015-05-21 06:59:59';*/
		 //$between = array('Tickets.created', '2015-05-20 06:59:59', '2015-05-22 06:59:59');
		/*$query = $this->Tickets->find()->where(function ($exp, $q) {
	        return $exp->between('Tickets.created', '2015-05-01 06:59:59', '2015-05-22 06:59:59');
	    })->toArray();*/
		$this->paginate			= array(
									'contain' => ['Users'],
									'conditions' => $arrCondition,
									'group'=>array('Tickets.id'),
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=>$this->CurrentPage,
									'limit' => $this->intLimit);
		
		$arrTicketList			= $this->paginate('Tickets')->toArray();
		$option['dt_selector']	= 'table-example';
		$option['formId']		= 'formmain';
		$option['url']			= URL_HTTP.'admin/tickets';
		$JqdTablescr			= $this->JqdTable->create($option);
		
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set('status',$this->ticket_status);
		$this->set('ticketlist',$arrTicketList);
		$this->set("page_count",(isset($this->request->params['paging']['Ticket']['pageCount'])?$this->request->params['paging']['Ticket']['pageCount']:0));
		$out=array();
		
			foreach($arrTicketList as $keyd=>$val)
			{
				$temparr	= array();
				$Actions	= array();
				foreach($option['colName'] as $key)
				{
					if($key == 'to_id'){
						$temparr[$key]='<b>'.ucfirst($val->subject).'</b><br/>From: '.$val->user['username'];
					}else if($key=='created'){
						$temparr[$key]=date('Y-m-d h:i:s',strtotime($val['created']));
					}else if($key=='action'){
						if($val['Ticket']['status']==0){
							$Actions[] = $this->Userright->linkCloseTicket(constant('WEB_ADMIN_URL').'tickets/closeticket/'.encode($val['id']),'<i class="fa fa-ban"> </i>','','rel="closeTicket" data-toggle="modal" data-target="#myModal" title="Close Ticket"',' ');
						}
						$Actions[] = $this->Userright->linkTicketHistory(constant('WEB_ADMIN_URL').'tickets/tickethistory/'.encode($val['id']),'<i class="fa fa-mail-reply"> </i>','','rel="listTicketReply" data-toggle="modal" data-target="#myModal" title="View Ticket History"','');
					} else {
						$temparr[$key]=$val[$key];
					}
					$temparr['action'] = implode("&nbsp;",$Actions);
				}
				$out[]=$temparr;
			}
		if($this->request->is('Ajax')) {
			header('Content-type: application/json');
			
			echo json_encode(array( "draw" => intval($this->request->data['draw']),
									"recordsTotal"    => intval( $this->request->params['paging']['Tickets']['count'] ),
									"recordsFiltered" => intval( $this->request->params['paging']['Tickets']['count'] ),
									"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * _generateTicketSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which Ticket condition to be generated if its not null
	 * @defination : Method is use to generate search condition using which admin Ticket data can be listed
	 *
	 */
	private function _generateTicketSearchCondition($id=null)
	{
		$arrCondition	= array();
		if(!empty($id)) $this->request->data['Ticket']['id'] = $id;
		if(isset($this->request->data) && count($this->request->data)>0)
		{
			if(isset($this->request->data['Ticket']['id']) && trim($this->request->data['Ticket']['id'])!='') {
				$strID = trim($this->request->data['Ticket']['id'],',');
				$arrCondition['Ticket.id'] = array_unique(explode(',',$strID));
			}
			if(isset($this->request->data['Ticket']['from_id']) && trim($this->request->data['Ticket']['from_id'])!='') {
				$arrCondition['Ticket.from_id'] = $this->request->data['Ticket']['from_id'];
			}
			
			if(isset($this->request->data['Ticket']['status']) && trim($this->request->data['Ticket']['status'])!='') {
				$arrCondition['Ticket.status'] = $this->request->data['Ticket']['status'];
			}
			if(!empty($this->request->data['Ticket']['search_period'])  && isset($this->request->data['Ticket']['search_date']) && $this->request->data['Ticket']['search_date']!='')
            {
				/*$arrDate=array('Ticket.created_date','Ticket.updated_date');
				if(in_array($this->data['Ticket']['search_date'], $arrDate))
				{*/
					if($this->request->data['Ticket']['search_period'] == 1 || $this->request->data['Ticket']['search_period'] == 2)
					{
						//$this->Tickets->setSearchDateParameter($this->request->data['Ticket']['search_period'],$this->modelClass);
						$arrSearchPara	=  $this->Tickets->setSearchDateParameter($this->request->data['Ticket']['search_period'],'Ticket');
						
						$this->request->data['Ticket']['DateFrom']		= $arrSearchPara['Ticket']['DateFrom'];/*Set::merge($this->request->data['Ticket'],$arrSearchPara);*/
						$this->request->data['Ticket']['DateTo']		= $arrSearchPara['Ticket']['DateTo'];/*Set::merge($this->request->data['Ticket'],$arrSearchPara);*/
						//$this->dateDisabled	= true;
					}
					$this->request->data['Ticket']['search_date'] 	= 'Tickets.created';

					$arrperiodcondi = $this->Tickets->findConditionByPeriod($this->request->data['Ticket']['search_date'],
																			$this->request->data['Ticket']['search_period'],
																			$this->request->data['Ticket']['DateFrom'],
																			$this->request->data['Ticket']['DateTo'],
																			$this->Session->read('User.timezone'));
					
					$arrCondition = array_merge($arrCondition,$arrperiodcondi);
					//$arrCondition = $arrperiodcondi;

				//}
            }
		}
		return $arrCondition;
	}

	/**
	 *
	 * manageticket
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for managing Ticket details if having master right to logged in user
	 *
	 */
	function manageticket($id=null)
	{

		//$this->layout='popup';
		$this->initAdminRightHelper();
        if(empty($id)){
            $this->intCurAdminUserRight = $this->Userright->ADD_TICKET;
            $pagetitle					= 'Add Ticket Details';
            //createa new entity
            if(empty($this->request->data))
            	$ticketsEntity = $this->Tickets->newEntity($this->request->data);
            else
            	$ticketsEntity = $this->Tickets->newEntity($this->request->data['Ticket']);
    	}
        else{
            $this->intCurAdminUserRight = $this->Userright->EDIT_TICKET;
            $pagetitle					= 'Edit Ticket Details';
            // createa patch entity
            $dataTickets = $this->Tickets->get(decode($id));

            if(empty($this->request->data['Ticket']))
            	$ticketsEntity = $this->Tickets->patchEntity($dataTickets,$this->request->data);
	        else
	            $ticketsEntity = $this->Tickets->patchEntity($dataTickets,$this->request->data['Ticket']);
        }

        $id = intval(decode($id));
        $this->setAdminArea();
		$arrError = array();

		if(!empty($this->request->data))
		{
			if(isset($this->request->data['Ticket']['to_id']) && !empty($this->request->data['Ticket']['to_id']))
        	{
            	$ticketsEntity->from_id	= $this->Session->read('User.id');
            	$ticketsEntity->to_id	= implode(',',$this->request->data['Ticket']['to_id']);
            	
            	if(empty($id)){
            		$ticketsEntity->Ticket['created'] = $this->NOW();
            	}else{
            		$ticketsEntity->Ticket['modified'] = $this->NOW();
            	}
            	
            	if($this->Tickets->save($ticketsEntity)) {
	            	$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_TICKET,$ticketsEntity->id,'Add Ticket id :: '.$ticketsEntity->id);
					$this->Flash->set('Ticket details add successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
	        		return $this->redirect(WEB_ADMIN_PREFIX.'/tickets');
	        	} else {	
        			$this->Flash->set('Invalid Ticket Details.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
	        		return $this->redirect(WEB_ADMIN_PREFIX.'/tickets');
            	}
	        }
        }
	 	else if(!empty($id)){
            $this->data = $this->Ticket->read(null, $id);
            if(!is_array($this->data) || empty($this->data)) {
                $this->Session->setFlash('Invalid Ticket Details');
                $this->redirect(array('action'=>'index'));
            }
		}
		$this->set('Ticket',$ticketsEntity);
		$this->set('data',$this->request->data);
		$this->set('status',$this->ticket_status);
		$this->set('department',$this->Department->GetDepartmentList());
		$this->set('userlist',$this->Users->GetAllUser());
		$this->set('title',$pagetitle);
		$this->set('arrError', $arrError);
        $this->set('id', $id);
	}

	/**
	 *
	 * replyticket
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for reply Ticket details if having master right to logged in user
	 *
	 */
	public function replyticket($type=null,$id=null)
	{
		$this->layout='popup';
		$this->initAdminRightHelper();
        $this->intCurAdminUserRight = $this->Userright->VIEW_TICKET_DETAIL;
        $this->setAdminArea();
		$id = intval(decode($id));

		if(!empty($this->data))
		{
			$this->Ticket->data = $this->data;
            if ($this->Ticket->validates()) 
            {
				if(!empty($id)){
					$this->request->data['Ticket']['from_id']	= $this->Session->read('User.id');
					$this->request->data['Ticket']['subject']	= 'Re:From-'.$this->Session->read('User.username');
					if(isset($this->data['Ticket']['parent_id']) && !empty($this->data['Ticket']['parent_id'])){
						$this->request->data['Ticket']['parent_id']	= $this->data['Ticket']['parent_id'];
					}else{
						$this->request->data['Ticket']['parent_id']	= $id;
					}
				}
				if($this->Ticket->save($this->data)){
					$this->flash('Ticket reply send successfully.','/index/');
				}
				else{
					 $this->Session->setFlash('Invalid Ticket Details');
                	$this->redirect(array('action'=>'index'));
				}
			}				
		}
		else if(!empty($id)){
            $this->data = $this->Ticket->getuserwiseticketlist($id);
            $this->user_list=$this->User->GetAllUser(explode(',',$this->data[0]['Ticket']['to_id']));
           	if(!is_array($this->data) || empty($this->data)) {
                $this->Session->setFlash('Invalid Ticket Details');
                $this->redirect(array('action'=>'index'));
            }
		}
		$this->set('data',$this->data);
		$this->set('id', $id);
		$this->set('users',$this->user_list);
		$this->set('type',$type);

	}
	
	/**
	 *
	 * ticketalert
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for view Ticket alert details if having master right to logged in user
	 *
	 */
	public function ticketalert()
	{
		$this->autoRender=false;
		$this->initAdminRightHelper();
		$btnViewTicket	= $this->Userright->checkadminrights($this->Userright->VIEW_TICKET_DETAIL);
		if($btnViewTicket){
			$this->arr=$this->Ticket->getticketalertlist($this->Session->read('User.id'));
		}
		echo json_encode($this->arr);
	}

	/**
	 *
	 * tickethistory
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for view Ticket alert details if having master right to logged in user
	 *
	 */
	public function tickethistory($id)
	{
		//$this->layout='popup';
		$this->layout=false;
		$this->initAdminRightHelper();
        $this->intCurAdminUserRight = $this->Userright->LIST_TICKET_HISTORY;
        $this->setAdminArea();
		$id=intval(decode($id));
		//pr($this->Tickets->gettickethistory($id));exit;
		$this->set('data',$this->Tickets->gettickethistory($id));
	}


	/**
	 *
	 * tickethistory
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for view Ticket alert details if having master right to logged in user
	 *
	 */
	public function closeticket($id)
	{
		$this->intCurAdminUserRight = $this->Userright->CLOSE_TICKET;
        $this->setAdminArea();

		$id 					= intval(decode($id));
		$dataTickets 			= $this->Tickets->get($id);
		$dataTickets->remarks 	= '';
		$ticketsPatchEntity 	= $this->Tickets->patchEntity($dataTickets,$this->request->data,['validate'=>'closeticket']);
		
		$this->set('Tickets',$ticketsPatchEntity);
		if(!empty($this->request->data))
		{	
			if(!empty($this->request->data['Tickets']['remarks']))
			{
				//last remark update in ticket table
				$this->Tickets->updateAll(
					['status'=>$this->CLOSE_TICKET,'remarks'=>trim($this->request->data['Tickets']['remarks']),'modified'=>$this->NOW()],
					['or'=>['id' => $id,'parent_id'=>$id]]
				);
				//add log in ticket detail
				$this->request->data['Tickets'];

				$EntityTicketRemarks 			= $this->TicketRemarks->newEntity($this->request->data['Tickets']);
				$EntityTicketRemarks->created 	= $this->NOW();
				$EntityTicketRemarks->ticket_id = $id;

				$this->TicketRemarks->save($EntityTicketRemarks);

				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->CLOSE_TICKET,$id,'Close Ticket id :: '.$id);
				$this->Flash->set('Ticket close successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				echo '<script>location.reload();</script>';
				$this->autoRender = false;
			}
			$this->set('is_ajax',true);
		}else{
			$this->set('is_ajax',false);
		}
	}
}