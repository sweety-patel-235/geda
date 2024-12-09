<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Controller\Component;

use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class UserrolesController extends AppController
{

    /**
     *
     * The status of $name is universe
     *
     * Potential value is name of class
     *
     * @public string
     *
     */
    public $name = 'Userroles';

    /**
     *
     * The status of $helpers is universe
     *
     * Potential value is array of helpers to be inherited
     *
     * @public array
     *
     */
    //public $helpers = array('Js', 'Time', 'Userright', 'Serviceright', 'ExPaginator', 'TimeZone');
    var $helpers = array('Time','Html','Form','ExPaginator');
    public function initialize()
    {
        // Always enable the CSRF component.
        parent::initialize();
        $this->loadComponent('Paginator');

        //$this->loadModel('Users');
        $this->loadModel('Userrole');
        $this->loadModel('Userroleright');
        $this->loadModel('Adminaction');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');

        //$this->loadModel('Department');
        //$this->loadModel('UserDepartment');

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
        $this->intCurAdminUserRight = $this->Userright->LIST_ADMIN_USER_ROLES;
        $this->setAdminArea();
        $arrAdminuserList = array();
        $arrUserType = array();
        $arrCondition = array();
        $this->SortBy = "Userrole.id";
        $this->Direction = "DESC";
        $this->intLimit = 10;
        $option = array();
        $option['colName'] = array('id', 'rolename', 'created', 'modified', 'action');
        $this->SetSortingVars('Userrole', $option);
		
        $arrCondition = $this->_generateAdminuserroleSearchCondition();
	
        $this->paginate = array('conditions' => $arrCondition,
                                'order' => array($this->SortBy => $this->Direction),
                                'page' => $this->CurrentPage,
                                'limit' => $this->intLimit);
        
        $arrAdminuserroleList = $this->paginate('Userrole')->toArray();
        $arrUserType[''] = "Select";
        if (is_array($this->usertypes) && count($this->usertypes) > 0) {
            foreach ($this->usertypes as $key => $value) $arrUserType[$key] = $value;
        }
        $option['dt_selector'] = 'grid_table';
        $option['formId'] = 'formmain';
        $option['url'] = WEB_ADMIN_PREFIX.'userroles';
        $JqdTablescr = $this->JqdTable->create($option);
        $this->set('JqdTablescr', $JqdTablescr);
        $this->set('arrAdminuserroleList', $arrAdminuserroleList);
        $this->set('arrUserType', $arrUserType);
        $this->set('period', $this->period);
        $this->set('limit', $this->intLimit);
        $this->set("CurrentPage", $this->CurrentPage);
        $this->set("SortBy", $this->SortBy);
        $this->set("Direction", $this->Direction);
        $this->set("page_count", (isset($this->request->params['paging']['Userrole']['pageCount']) ? $this->request->params['paging']['Userrole']['pageCount'] : 0));
        $out = array();
        $blnEditAdminuserroleRights = $this->Userright->checkadminrights($this->Userright->EDIT_ADMIN_USER_ROLE);
        $blnDeleteAdminuserroleRights = $this->Userright->checkadminrights($this->Userright->DELETE_ADMIN_USER_ROLE);
        
        foreach ($arrAdminuserroleList as $key => $val) {
            $temparr = array();
            foreach ($option['colName'] as $key) {
                if($key == 'modified') {
                    $temparr['modified'] = ((!empty($val['modified'])) ? date('d-m-Y H:i:s',strtoTime($val['modified'])) : '00-00-0000 00:00:00');
                }
                if (isset($val[$key])) {
                    if($key == 'created') {
                        $temparr['created'] = date('d-m-Y H:i:s',strtoTime($val['created']));
                    } else if($key != 'modified') {
                        $temparr[$key] = $val[$key];
                    }
                }
                if ($key == 'action') {
                    $temparr['action'] = '';
                    if ($blnEditAdminuserroleRights)
                        $temparr['action'] .= $this->Userright->linkEditAdminuserrole(constant('WEB_ADMIN_URL') . 'userroles/manage/' . encode($val['id']), '<i class="fa fa-edit"></i>', '', 'editRecord', ' alt="Edit Admin user role" title="Edit Admin user role"') . "<br>";
                    if ($blnDeleteAdminuserroleRights)
                        $temparr['action'] .= $this->Userright->linkDeleteAdminuserrole(constant('WEB_ADMIN_URL') . 'userroles/delete/' . $val['id'], '#Delete', '', 'actionRecord', ' title="Delete"') . "<br>";
                }
            }
            $out[]=$temparr;
        }
        if ($this->request->is('ajax')) {
            header('Content-type: application/json');
            echo json_encode(array("draw" => intval($this->request->data['draw']),
                "recordsTotal" => intval($this->request->params['paging']['Userrole']['count']),
                "recordsFiltered" => intval($this->request->params['paging']['Userrole']['count']),
                "data" => $out));
            die;
        }
    }

    /**
     *
     * _generateAdminuserroleSearchCondition
     *
     * Behaviour : Private
     *
     * @param : $id  : Id is use to identify for which user condition to be generated if its not null
     * @defination : Method is use to generate search condition using which admin user role data can be listed
     *
     */
    private function _generateAdminuserroleSearchCondition($id = null)
    {
        $arrCondition = array();
        $blnSinCompany = true;
        if (!empty($id)) $this->request->data['Userrole']['id'] = $id;
        if (isset($this->request->data) && count($this->request->data) > 0) {
            if (isset($this->request->data['Userrole']['id']) && trim($this->request->data['Userrole']['id']) != '') {
                $strID = trim($this->request->data['Userrole']['id'], ',');
                $arrCondition['Userrole.id'] = array_unique(explode(',', $strID));
            }

            if (isset($this->request->data['Userrole']['rolename']) && $this->request->data['Userrole']['rolename'] != '') {
                $arrCondition['Userrole.rolename LIKE'] = '%' . $this->request->data['Userrole']['rolename'] . '%';
            }

            if (isset($this->request->data['Userrole']['rights']) && $this->request->data['Userrole']['rights'] != '') {
                //$arrCondition['Userrole.rights LIKE'] = '%'.$this->request->data['Userrole']['email'].'%';
            }

            if (isset($this->request->data['Userrole']['search_date']) && $this->request->data['Userrole']['search_date'] != '') {
                /*$arrDate = array('Userrole.created', 'Userrole.modified');
                if (in_array($this->request->data['Userrole']['search_date'], $arrDate)) {
                */  if ($this->request->data['Userrole']['search_period'] == 1 || $this->request->data['Userrole']['search_period'] == 2) {
                        $arrSearchPara = $this->Userrole->setSearchDateParameter($this->request->data['Userrole']['search_period'], 'Userrole');
                        //$this->request->data['Userrole'] = Set::merge($this->request->data['Userrole'], $arrSearchPara);
                        $this->request->data['Userrole'] = array_merge($this->request->data['Userrole'],$arrSearchPara['Userrole']);
                        
                        $this->dateDisabled = true;
                    }

                    $arrperiodcondi = $this->Userrole->findConditionByPeriod($this->request->data['Userrole']['search_date'],
                                                                            $this->request->data['Userrole']['search_period'],
                                                                            $this->request->data['Userrole']['DateFrom'],
                                                                            $this->request->data['Userrole']['DateTo'],
                                                                            $this->Session->read('Userrole.timezone'));
                    $arrCondition = array_merge($arrCondition, $arrperiodcondi);
                //}
            }
        }
        return $arrCondition;
    }

    /**
     *
     * manage (add edit)
     *
     * Behaviour : Public
     *
     * @defination : Method is use to "manage" (add edit) new admin user role and provide specific righs using admin interface
     *
     */
    public function manage($id = null)
    {
        //$this->initAdminRightHelper();
        if (empty($id)){
            $this->intCurAdminUserRight = $this->Userright->ADD_ADMIN_USER_ROLE;
            $userroleEntity = $this->Userrole->newEntity($this->request->data(),['validate' => 'add']);
            $userroleEntity->created = $this->NOW();
        } else {
            $decodeId = intval(decode($id));
            $this->intCurAdminUserRight = $this->Userright->EDIT_ADMIN_USER_ROLE;
            
            $userroleData = $this->Userrole->get($decodeId);
            $userroleEntity = $this->Userrole->patchEntity($userroleData, $this->request->data(),['validate' => 'edit']);
            $userroleEntity->modified = $this->NOW();
        }

        $this->setAdminArea();
        $arrAdminDefaultRights = array();
        $arrAdminTransaction = array();
        $arrError = array();
        
        if (!empty($this->request->data)) {
            //if ($this->Userrole->validates()) {
                if ($this->Userrole->save($userroleEntity)) {
                    $this->Userroleright->deleteAll(array('Userroleright.roleid' => $userroleEntity->id), false);

                    if (isset($this->request->data['Userrole']['rights'])) {
                        foreach ($this->request->data['Userrole']['rights'] as $rollkey => $intRights) {
                            if (!empty($intRights)) {
                                $this->Userroleright->saveAdminuserRoleRight($userroleEntity->id, $intRights);
                            }
                        }
                    }
                    if (empty($id)) {
                        $this->writeadminlog($this->Session->read('User.id'), $this->Adminaction->ADD_ADMIN_USER_ROLE, $userroleEntity->id, 'Added Admin user role id::' . $userroleEntity->id);
                        $this->Flash->set('Admin user role has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                        return $this->redirect(array('controller' => 'userroles'));
                    } else {
                        if ($id == $this->Session->read('User.usertype')) {
                            $this->User->GenerateAdminuserRightSession($this->Session->read('User.id'), $this->Session);
                            $this->RegenerateAdminMenu();
                        }
                        $this->writeadminlog($this->Session->read('User.id'), $this->Adminaction->EDIT_ADMIN_USER_ROLE, $userroleEntity->id, 'Updated Admin user role id::' . $userroleEntity->id);
                        $this->Flash->set('Admin user role has been updated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                        return $this->redirect(array('controller' => 'userroles'));
                    }
                }
            //}
        } else if (!empty($id)) {
            //$this->request->data = $this->Userrole->get($id);

            /*if (!is_array($this->request->data) || empty($this->request->data)) {
                $this->flash('Invalid admin user role.', '/users/userroles/');
            }*/
            $arrAdminUserRoleRights = $this->Userroleright->find('all', array('conditions' => array('roleid' => $decodeId), 'fields' => ['trnmoduleid','trntypeid']))->toArray();
            /*pr($arrAdminUserRoleRights);exit;*/
            if (is_array($arrAdminUserRoleRights)) {
                if (isset($this->request->data['Userrole']['rights']))
                    unset($this->request->data['Userrole']['rights']);
                if (count($arrAdminUserRoleRights) > 0) {
                    foreach ($arrAdminUserRoleRights as $arrRights) {
                        
                        $this->request->data['Userrole']['rights'][$arrRights->trnmoduleid . '_' . $arrRights->trntypeid] = $arrRights->trnmoduleid . '_' . $arrRights->trntypeid;
                    }
                }
            }
        }
        $userrights = $this->Session->read('User.userrights');
        $arrAdminRoleRights = $this->Userroleright->getAllAdminUserRoleRight($this->Session->read('User.usertype'));

        if (count($userrights) > 0) {
            $arrModuleRight = $this->Admintransaction->getUserModuleRights($userrights);
        }
        $arrUserrights = array();
        if (count($arrModuleRight) > 0) {
            foreach ($arrModuleRight as $arrRights) {
                $arrUserrights[] = $arrRights->trnmoduleid . '_' . $arrRights->trntype;
            }
        }
        $arrUserrights = array_unique($arrUserrights);
        if (count($arrAdminRoleRights) > 0) {
            foreach ($arrAdminRoleRights as $key => $arrRights) {
                $arrtype = array();
                $arrtype = split(",", $arrRights);
                foreach ($arrtype as $type) {
                    $arrUserrights[] = $key . '_' . $type;
                }
            }
        }
        $arrUserrights = array_unique($arrUserrights);
        $arradmintrntype = $this->Admintrntype->find('all')->toArray();
        $arradmintrnmodule = $this->Admintrnmodule->find('all')->toArray();
        $this->set('Userrole',$userroleEntity);
        $this->set('arrUserrights', $arrUserrights);
        $this->set('arradmintrntype', $arradmintrntype);
        $this->set('arradmintrnmodule', $arradmintrnmodule);
        $this->set('data', $this->request->data);
        $this->set('arrError', $arrError);
        $this->set('id', $id);
    }
}
