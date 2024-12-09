<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class CheckUserRoleTable extends AppTable
{

    var $MasterPages              = ['Contact_details'=>1,
                                'Forward'=>1,
                                'Customer_confirm'=>1,
                                'Site_survey'=>1,
                                'Commercial'=>1,
                                'Terms_and_condition'=>1,
                                'Proposal'=>1,
                                'Work_order'=>1,
                                'Execution'=>1,
                                'Commissioning'=>1,
                                'My_Project'=>1,
                                'Solar_Calculator'=>1,
                                'Customer_leads'=>1,
                                'Apply_Online'=>1,
                                'Site_survey'=>1,
                                'Important_document'=>1,
                                'Financial_Incentives'=>1,
                                'Find_installer'=>1,
                                'Profile'=>1,
                                'Company_Profile'=>1,
                                'My_Projects'=>1,
                                'My_saved_survey'=>1,
                                'Subscription'=>1,
                                'Financial_Incentives'=>1,
                                'Offer'=>1,
                                'Call_Support'=>1
                                ];
    var $MasterHome = [ 'My_Project'=>1,
                        'Solar_Calculator'=>1,
                        'Customer_leads'=>1,
                        'Apply_Online'=>1,
                        'Site_survey'=>1,
                        'Important_document'=>1,
                        'Financial_Incentives'=>1,
                        'Find_installer'=>1];

    var $MasterSidebar = ['Profile'=>1,
                        'Company_Profile'=>1,
                        'My_Projects'=>1,
                        'My_saved_survey'=>1,
                        'Subscription'=>1,
                        'Site_survey'=>1,
                        'Customer_leads'=>1,
                        'Financial_Incentives'=>1,
                        'Offer'=>1,
                        'Call_Support'=>1];


    var $MasterProjectDashboard = [
                                    'Contact_details'=>1,
                                    'Forward'=>1,
                                    'Customer_confirm'=>1,
                                    'Site_survey'=>1,
                                    'Commercial'=>1,
                                    'Terms_and_condition'=>1,
                                    'Proposal'=>1,
                                    'Work_order'=>1,
                                    'Execution'=>1,
                                    'Commissioning'=>1];

    var $project_dashboard_permission   = [
                                        '5001'=>['Contact_details'=>1,
                                                'Customer_confirm'=>1,
                                                'Site_survey'=>1,
                                                ],
                                        '5002'=>['Contact_details'=>1,
                                                'Commercial'=>1,
                                                'Terms_and_condition'=>1,
                                                'Proposal'=>1,
                                                'Work_order'=>1,
                                                ],
                                        '5003'=>['Contact_details'=>1,
                                                'Forward'=>1,
                                                'Customer_confirm'=>1,
                                                ],
                                        '5004'=>['Contact_details'=>1,
                                                'Work_order'=>1,
                                                'Execution'=>1,
                                                'Commissioning'=>1,
                                                ],
                                        '5005'=>['Contact_details'=>1,
                                                'Forward'=>1,
                                                'Customer_confirm'=>1,
                                                'Site_survey'=>1,
                                                'Commercial'=>1,
                                                'Terms_and_condition'=>1,
                                                'Proposal'=>1,
                                                'Work_order'=>1,
                                                'Execution'=>1,
                                                'Commissioning'=>1
                                                 ],
                                        'no_role'=>[],

                                        ];

    var $home_screen_dashboard          = [
                                        '5001'=>['My_Project'=>1,
                                                'Solar_Calculator'=>1,
                                                'Apply_Online'=>1,
                                                'Site_survey'=>1,
                                                'Important_document'=>1,
                                                'Financial_Incentives'=>1,
                                                'Find_installer'=>1,
                                                ],
                                        '5002'=>['My_Project'=>1,
                                                'Solar_Calculator'=>1,
                                                'Apply_Online'=>1,
                                                'Site_survey'=>1,
                                                'Important_document'=>1,
                                                'Financial_Incentives'=>1,
                                                'Find_installer'=>1,
                                                ],
                                        '5003'=>['My_Project'=>1,
                                                'Solar_Calculator'=>1,
                                                'Customer_leads'=>1,
                                                'Apply_Online'=>1,
                                                'Site_survey'=>1,
                                                'Important_document'=>1,
                                                'Financial_Incentives'=>1,
                                                'Find_installer'=>1,
                                                ],
                                        '5004'=>['My_Project'=>1,
                                                'Solar_Calculator'=>1,
                                                'Apply_Online'=>1,
                                                'Site_survey'=>1,
                                                'Important_document'=>1,
                                                'Financial_Incentives'=>1,
                                                'Find_installer'=>1,
                                                ],
                                        '5005'=>['My_Project'=>1,
                                                'Solar_Calculator'=>1,
                                                'Customer_leads'=>1,
                                                'Apply_Online'=>1,
                                                'Site_survey'=>1,
                                                'Important_document'=>1,
                                                'Financial_Incentives'=>1,
                                                'Find_installer'=>1
                                                 ],

                                        'no_role'=>['Solar_Calculator'=>1],

                                        ];

    var $side_menu                      = [
                                        '5001'=>['Profile'=>1,
                                                'My_Projects'=>1,
                                                'My_saved_survey'=>1,
                                                'Subscription'=>1,
                                                'Site_survey'=>1,
                                                'Financial_Incentives'=>1,
                                                'Offer'=>1,
                                                'Call_Support'=>1,
                                                ],
                                        '5002'=>['Profile'=>1,
                                                'My_Projects'=>1,
                                                'My_saved_survey'=>1,
                                                'Subscription'=>1,
                                                'Site_survey'=>1,
                                                'Financial_Incentives'=>1,
                                                'Offer'=>1,
                                                'Call_Support'=>1,
                                        ],
                                        '5003'=>['Profile'=>1,
                                                'My_Projects'=>1,
                                                'My_saved_survey'=>1,
                                                'Subscription'=>1,
                                                'Site_survey'=>1,
                                                'Customer_leads'=>1,
                                                'Financial_Incentives'=>1,
                                                'Offer'=>1,
                                                'Call_Support'=>1,
                                        ],
                                        '5004'=>['Profile'=>1,
                                                'My_Projects'=>1,
                                                'My_saved_survey'=>1,
                                                'Subscription'=>1,
                                                'Site_survey'=>1,
                                                'Financial_Incentives'=>1,
                                                'Offer'=>1,
                                                'Call_Support'=>1,
                                        ],
                                        '5005'=>['Profile'=>1,
                                                'Company_Profile'=>1,
                                                'My_Projects'=>1,
                                                'My_saved_survey'=>1,
                                                'Subscription'=>1,
                                                'Site_survey'=>1,
                                                'Customer_leads'=>1,
                                                'Financial_Incentives'=>1,
                                                'Offer'=>1,
                                                'Call_Support'=>1
                                        ],
                                        'no_role'=>['Profile'=>1,
                                            'Offer'=>1,
                                            'Call_Support'=>1,
                                        ],
                                       ];




    public function getuserrole($userrole="", $forwhat="") {
             $roles = array();
            switch ($forwhat) {
                case 'project_dashboard_permission':
                    $expload_roles = explode(',',$userrole);
                    $project_dashboard = array();

                    for($i=0; $i<count($expload_roles); $i++) {
                        foreach ($expload_roles as $role) {

                            if(!empty($this->project_dashboard_permission[$role])) {
                                $project_dashboard[$role] = $this->project_dashboard_permission[$role];
                            }

                        }
                    }

                    if(!empty($project_dashboard)) {
                        foreach ($project_dashboard as $role => $rolepermission) {
                            foreach ($rolepermission as $permission => $value) {
                                $roles['project_dashboard'][$permission] = $value;
                            }
                        }
                    }else{
                        $roles['project_dashboard'] = array();
                    }

                    $project_keys = array_keys($this->MasterProjectDashboard);
                    $permission_project_keys = array_keys($roles['project_dashboard']);
                    $home = array_diff($project_keys,$permission_project_keys);
                    if(!empty($home)){
                        foreach ($home as $key => $permission){
                            $roles['project_dashboard'][$permission] = 0;
                        }
                    }



            break;
            case 'home_side':
                $expload_roles = explode(',',$userrole);
                $home_screen = array();
                $sidebar  = array();
                $project_dashboard = array();

                   for($i=0; $i<count($expload_roles); $i++) {
                       foreach ($expload_roles as $role) {
                           if(!empty($this->home_screen_dashboard[$role])) {
                               $home_screen[$role] = $this->home_screen_dashboard[$role];
                           }
                           if(!empty($this->side_menu[$role])) {
                               $sidebar[$role] = $this->side_menu[$role];
                           }
                           if(!empty($this->project_dashboard_permission[$role])) {
                               $project_dashboard[$role] = $this->project_dashboard_permission[$role];
                           }
                       }
                   }
                   foreach ($home_screen as $role=>$rolepermission) {
                       foreach ($rolepermission as $permission => $value) {
                           $roles['home_screen'][$permission] = $value;
                        }
                   }

                   foreach ($sidebar as $role=>$rolepermission) {
                        foreach ($rolepermission as $permission =>$value){
                           $roles['sidebar'][$permission] = $value;
                        }
                   }

                    foreach ($project_dashboard as $role => $rolepermission) {
                        foreach ($rolepermission as $permission => $value) {
                            $roles['project_dashboard'][$permission] = $value;
                        }
                    }

                    if(!empty($roles['project_dashboard'])) {
                        $project_keys = array_keys($this->MasterProjectDashboard);
                        $permission_project_keys = array_keys($roles['project_dashboard']);
                        $home = array_diff($project_keys, $permission_project_keys);
                        if (!empty($home)) {
                            foreach ($home as $key => $permission) {
                                $roles['project_dashboard'][$permission] = 0;
                            }
                        }
                    }else{

                       foreach ($this->MasterProjectDashboard as $key => $value) {
                            $roles['project_dashboard'][$key] = 0;
                       }
                    }




                   $home_screen_keys = array_keys($this->MasterHome);
                   $permission_home_keys = array_keys($roles['home_screen']);
                   $home = array_diff($home_screen_keys,$permission_home_keys);
                   if(!empty($home)){
                       foreach ($home as $key => $permission){
                           $roles['home_screen'][$permission] = 0;
                       }
                   }

                    $home_screen_keys = array_keys($this->MasterSidebar);
                    $permission_home_keys = array_keys($roles['sidebar']);
                    $home = array_diff($home_screen_keys,$permission_home_keys);
                    if(!empty($home)){
                        foreach ($home as $key => $permission){
                            $roles['sidebar'][$permission] = 0;
                        }
                    }

            break;
                case 'all':
                    $roles['project_dashboard'] = $this->MasterProjectDashboard;
                    $roles['home_screen'] = $this->MasterHome;
                    $roles['sidebar'] = $this->MasterSidebar;

            break;
                default:
                    $roles['project_dashboard'] =array();
                    $roles['home_screen'] = $this->home_screen_dashboard['no_role'];

                    $home_screen_keys = array_keys($this->MasterHome);
                    $permission_home_keys = array_keys($roles['home_screen']);
                    $home = array_diff($home_screen_keys,$permission_home_keys);
                    if(!empty($home)){
                        foreach ($home as $key => $permission){
                            $roles['home_screen'][$permission] = 0;
                        }
                    }

                    $roles['sidebar'] = $this->side_menu['no_role'];

                    $home_screen_keys = array_keys($this->MasterSidebar);
                    $permission_home_keys = array_keys($roles['sidebar']);
                    $home = array_diff($home_screen_keys,$permission_home_keys);
                    if(!empty($home)){
                        foreach ($home as $key => $permission){
                            $roles['sidebar'][$permission] = 0;
                        }
                    }



            }
        return $roles;

    }

}
?>