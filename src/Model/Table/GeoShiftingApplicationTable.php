<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\View\View;
use Dompdf\Dompdf;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;
/**
 * @category  Class File
 * @author    Employee Code : -
 * @version   GED 1.0
 * @since     File available since GED
 */
class GeoShiftingApplicationTable extends AppTable
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
    var $table = 'geo_shifting_application';
    
    public function initialize(array $config)
    {
        $this->table($this->table);
    }
    public $ExportFields    = array("sr_no","registration_no","wtg_location","old_zone","old_x_cordinate","old_y_cordinate","modified_zone","modified_x_cordinate","modified_y_cordinate","comment","approved","approved_by","approved_date","wtg_verified","wtg_verified_by","wtg_verified_date","created_by","created_date");

    public $DefaultExportFields     = array("sr_no","registration_no","wtg_location","old_zone","old_x_cordinate","old_y_cordinate","modified_zone","modified_x_cordinate","modified_y_cordinate","comment","approved","approved_by","approved_date","wtg_verified","wtg_verified_by","wtg_verified_date","created_by","created_date");
    public $arrReportFields         = array("sr_no"                 => "sr_no",
                                        "registration_no"           => "registration_no",
                                        "wtg_location"              => "wtg_location",
                                        "old_zone"                  => "old_zone",
                                        "old_x_cordinate"           => "old_x_cordinate",
                                        "old_y_cordinate"           => "old_y_cordinate",
                                        "modified_zone"             => "modified_zone",
                                        "modified_x_cordinate"      => "modified_x_cordinate",
                                        "modified_y_cordinate"      => "modified_y_cordinate",
                                        "comment"                   => "comment",
                                        "approved"                  => "approved",
                                        "approved_by"               => "approved_by",
                                        "approved_date"             => "approved_date",
                                        "wtg_verified"              => "wtg_verified",
                                        "wtg_verified_by"           => "wtg_verified_by",
                                        "wtg_verified_date"         => "wtg_verified_date",
                                        "created_by"                => "created_by",
                                        "created_date"              => "created_date"
                                    );
    public function save_data($application_id,$arr_modules,$customer_id)
    {
        //echo"<pre>"; print_r($arr_modules); die();
        $saveShiftingData                                   = TableRegistry::get('GeoShiftingApplication'); 
        $saveShiftingData_entity                            = $saveShiftingData->newEntity(); 
        $saveShiftingData_entity->application_id            = $application_id;
        $saveShiftingData_entity->geo_application_id        = $arr_modules['geo_application_id'];
        $saveShiftingData_entity->wtg_location              = $arr_modules['wtg_location'];
        $saveShiftingData_entity->land_survey_no            = $arr_modules['land_survey_no'];
        
        $saveShiftingData_entity->old_zone                  = $arr_modules['old_zone'];
        $saveShiftingData_entity->old_x_cordinate           = $arr_modules['old_x_cordinate'];
        $saveShiftingData_entity->old_y_cordinate           = $arr_modules['old_y_cordinate'];

        $saveShiftingData_entity->modified_zone             = $arr_modules['modified_zone'];
        $saveShiftingData_entity->modified_x_cordinate      = $arr_modules['modified_x_cordinate'];
        $saveShiftingData_entity->modified_y_cordinate      = $arr_modules['modified_y_cordinate'];

        $saveShiftingData_entity->created_by                = $customer_id;
        $saveShiftingData_entity->created_date              = $this->NOW();
        $saveShiftingData->save($saveShiftingData_entity);


    }
    /**
     * generateApplicationPdf
     * Behaviour : public
     * @param : id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=true
     * @defination : Method is use to download .pdf file from modal popup of applyonline listing
     *
     */
    public function generateGeoApplicationShiftingVerifiedPdf($id,$isdownload=true,$mobile=false)
    {


        if(empty($id)) {
            return 0;
        } else {
            $view = new View();
            $view->layout               = false;
            $id                         = decode($id);
            $GeoApplicationVerification = TableRegistry::get('GeoApplicationVerification');
            $get_data                   = $GeoApplicationVerification->find('all',array('conditions'=>array('id'=>$id)))->first();
            
            $ApplicationGeoLocation     = TableRegistry::get('ApplicationGeoLocation');
            $Applications               = TableRegistry::get('Applications');
            $BranchMasters              = TableRegistry::get('BranchMasters');
            $DiscomMaster               = TableRegistry::get('DiscomMaster');
            $DistrictMaster             = TableRegistry::get('DistrictMaster');
            $TalukaMaster               = TableRegistry::get('TalukaMaster');
            $Developers                 = TableRegistry::get('Developers');
            $GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
            $ManufacturerMaster         = TableRegistry::get('ManufacturerMaster');
            $members                    = TableRegistry::get('members');
            $applicationDetails         = $Applications->viewDetailApplication($get_data->application_id);
            
            $InstallersData             = $Developers->find('all', array('conditions' => array('id' => $applicationDetails->installer_id)))->first();
            
            $developer_name = $InstallersData->installer_name;
            $developer_address = $InstallersData->address . ", " . $InstallersData->taluka . " ".$InstallersData->pincode. " ". $InstallersData->state ;
            
            $geo_application_data       = $this->find('all',array('conditions'=>array('application_id'=>$get_data->application_id)))->first();

            $members                    = $members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$geo_application_data->approved_by]])->first();

            $geo_ids= explode(',', $get_data->geo_id);
            $clashed_data       = $GeoApplicationClashedData->find('all',array('conditions'=>array('application_id'=>$get_data->application_id, 'shifting_id IS NOT NULL')))->first();
          
            if(empty($clashed_data)){
                $geo_application_data_array = $this->find('all',
                                            [ 'fields'=>['application_geo_location.id','application_geo_location.wtg_location','application_geo_location.wtg_verified_date','approved','application_geo_location.geo_village','comment','application_geo_location.geo_taluka','application_geo_location.zone','application_geo_location.land_survey_no','application_geo_location.geo_district','application_geo_location.x_cordinate','application_geo_location.y_cordinate','application_geo_location.wtg_make','application_geo_location.wtg_capacity','application_geo_location.wtg_rotor_dimension','geo_shifting_application_reject_log.reject_reason','application_geo_location.wtg_hub_height','members.address1','modified_x_cordinate','modified_y_cordinate','modified_zone'],
                                                'join'=>[
                                                    
                                               ['table'=>' geo_shifting_application_reject_log','type'=>'left','conditions'=>'GeoShiftingApplication.id = geo_shifting_application_reject_log.geo_shifting_application_id'],
                                                ['table'=>'application_geo_location','type'=>'left','conditions'=>'GeoShiftingApplication.geo_application_id = application_geo_location.id', 'application_geo_location.approved'=>1],
                                                ['table'=>'members','type'=>'left','conditions'=>'GeoShiftingApplication.approved_by = members.id']],
                                                'conditions'=>['GeoShiftingApplication.application_id'=>$get_data->application_id ,'GeoShiftingApplication.geo_application_id IN'=>$geo_ids,'OR'=>['GeoShiftingApplication.approved is NOT NULL']]
                                            ])->toArray();    

            }else{
                $geo_application_data_array = $this->find('all',
                                            [ 'fields'=>['application_geo_location.id','application_geo_location.wtg_location','application_geo_location.wtg_verified_date','approved','application_geo_location.geo_village','comment','application_geo_location.geo_taluka','application_geo_location.zone','application_geo_location.land_survey_no','application_geo_location.geo_district','application_geo_location.x_cordinate','application_geo_location.y_cordinate','application_geo_location.wtg_make','application_geo_location.wtg_capacity','application_geo_location.wtg_rotor_dimension','geo_shifting_application_reject_log.reject_reason','application_geo_location.wtg_hub_height','members.address1','modified_x_cordinate','modified_y_cordinate','modified_zone','geo_application_clashed_data.clashed_geo_id','geo_application_clashed_data.clashed_for'],
                                                'join'=>[
                                                    ['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'GeoShiftingApplication.geo_application_id = geo_application_clashed_data.clashed_geo_id','geo_application_clashed_data.shifting_id IS NOT NULL'],
                                                ['table'=>' geo_shifting_application_reject_log','type'=>'left','conditions'=>'GeoShiftingApplication.id = geo_shifting_application_reject_log.geo_shifting_application_id'],
                                                ['table'=>'application_geo_location','type'=>'left','conditions'=>'GeoShiftingApplication.geo_application_id = application_geo_location.id'],
                                                ['table'=>'members','type'=>'left','conditions'=>'GeoShiftingApplication.approved_by = members.id']],
                                                'conditions'=>['GeoShiftingApplication.application_id'=>$get_data->application_id ,'GeoShiftingApplication.geo_application_id IN'=>$geo_ids,'OR'=>['GeoShiftingApplication.approved is NOT NULL','geo_application_clashed_data.clashed_for is NOT NULL']]
                                            ])->group('application_geo_location.id')->toArray();     
            }
         //echo"<pre>"; print_r($geo_application_data_array); die();
            foreach ($geo_application_data_array as $key => $value) {
                $verified_date[] = $value['application_geo_location']['wtg_verified_date'];
            }
            $wtg_verified_date =$verified_date[0];
            //echo"<pre>"; print_r($members); die();
            if(!empty($applicationDetails->discom)){
                $discom_name        = $BranchMasters->find("all",['conditions'=>['id'=>$applicationDetails->discom]])->first();
                $discom_short_name  = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
            }
            $district           = $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
            $taluka             = $TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
            $wtg_make           = $ManufacturerMaster->find("list",['keyField'=>'id','valueField'=>'name'])->toArray();
            $district           = $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
            $geo_application_clashed_data   = $GeoApplicationClashedData->find("list",['keyField'=>'clashed_geo_id','valueField'=>'clashed_for'])->toArray();
            $view->set("pageTitle","GeoApplication");
            //$view->set('clashtext',$clashtext);
            $view->set('applicationDetails',$applicationDetails);
            $view->set('geo_application_data',$geo_application_data);
            $view->set('geo_application_data_array',$geo_application_data_array);
            $view->set('taluka',$taluka);
            $view->set('district',$district);
            $view->set('wtg_make',$wtg_make);
            $view->set('geo_application_clashed_data',$geo_application_clashed_data);
            $view->set('developer_name',$developer_name);
            $view->set('developer_address',$developer_address);
            $view->set('members',$members);
            $view->set('wtg_verified_date',$wtg_verified_date);
            //$view->set('geda_application_no',$applicationDetails->geda_application_no);
            
            $PDFFILENAME = getRandomNumber();
            $LETTER_APPLICATION_NO  = decode($id);
            $LETTER_APPLICATION_NO  = $applicationDetails->application_no;

            /* Generate PDF for estimation of project */
            require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
            $dompdf = new Dompdf($options = array());
            $dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
            $dompdf->set_option("isPhpEnabled", true);
            $view->set('dompdf',$dompdf);
            
            $html = $view->render('/Element/geo_application/download_geo_location_shifting_verified');
            $dompdf->loadHtml($html,'UTF-8');

            //$dompdf->setPaper('A4', 'portrait');
            $dompdf->setPaper('A4', 'landscape');
            //$dompdf->render();
            $dompdf->render();
            if($isdownload) {

                $dompdf->stream('applyonline-'.$LETTER_APPLICATION_NO); 
            }
            $output = $dompdf->output();
            header("Content-type:application/pdf");
            header("Content-Disposition:inline;filename=".$LETTER_APPLICATION_NO.".pdf");
            echo $output;
            die;
        }
    }

    /**
    * getDataApplications
    * Behaviour : public
    * Parameter : arrRequestData all parameter of request as well as session passed to this variable
    * @defination : Listing of applications.
    */
    public function getGeoLocationShiftingData($arrRequestData=array(),$SortBy,$Direction) 
    {
        $fields             = [ 'id',
                                'application_id',
                                'geo_application_id',
                                'wtg_location',
                                'old_x_cordinate',
                                'old_y_cordinate',
                                'payment_date',
                                'wtg_verified',
                                'modified_x_cordinate',
                                'modified_y_cordinate',
                                'application_geo_location.id',
                                'applications.installer_id',
                                'developer_customers.name',
                                'district_master.name',
                                'application_geo_location.geo_district',
                                'application_geo_location.geo_taluka',
                                'applications.registration_no',
                                
                                //'geo_application_clashed_data.clashed_for',
                                //'geo_application_clashed_data.created',
                                'members.address1',
                                'developers.installer_name'
                              ];
        
                                
        $arrOrderBy     = explode("|",$arrRequestData['order_by_form']);
        $join_arr       = [ 
                            'application_geo_location'  => ['table'=>'application_geo_location','type'=>'left','conditions'=>'GeoShiftingApplication.geo_application_id=application_geo_location.id'],
                            'applications'  => ['table'=>'applications','type'=>'left','conditions'=>'GeoShiftingApplication.application_id = applications.id'],
                            'application_category'  => ['table'=>'application_category','type'=>'left','conditions'=>'application_geo_location.application_type=application_category.id'],
                              // 'geo_application_clashed_data'  => ['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'GeoShiftingApplication.id = geo_application_clashed_data.shifting_id'],
                            'district_master'       => ['table'=>'district_master','type'=>'left','conditions'=>'application_geo_location.geo_district=district_master.id'],'members'   => ['table'=>'members','type'=>'left','conditions'=>'GeoShiftingApplication.approved_by = members.id']
                                            ];
      
        // if(!empty($arrRequestData['customer_id'])) {
            
            $condition_arr      = array();
             array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = applications.customer_id']);
             array_push($join_arr,['table'=>'developers','type'=>'left','conditions'=>'developers.id = applications.installer_id']);
            $str_group_by       = 1;
            $flag_stages_table  = 0;
            
            
            // $condition_arr['OR'] = array('application_geo_location.payment_status'=>1,'application_geo_location.payment_status'=>2);
            if(isset($arrRequestData['application_type']) && !empty($arrRequestData['application_type'])) {
                $condition_arr['application_geo_location.application_type']           = $arrRequestData['application_type'];
            }
            if(isset($arrRequestData['geo_taluka']) && !empty($arrRequestData['geo_taluka'])) {
                $condition_arr['application_geo_location.geo_taluka']         = $arrRequestData['geo_taluka'];
            }
            if(isset($arrRequestData['geo_district']) && !empty($arrRequestData['geo_district'])) {
                $condition_arr['application_geo_location.geo_district']           = $arrRequestData['geo_district'];
            }
            if(isset($arrRequestData['wtg_location']) && !empty($arrRequestData['wtg_location'])) {
                $condition_arr['GeoShiftingApplication.wtg_location']           = $arrRequestData['wtg_location'];
            }
            if(isset($arrRequestData['action_by']) && !empty($arrRequestData['action_by'])) {
                $condition_arr['members.address1']          = $arrRequestData['action_by'];
            }
            if(isset($arrRequestData['wtg_verified']) && ($arrRequestData['wtg_verified']!='') ) {
                $condition_arr['GeoShiftingApplication.wtg_verified']           = $arrRequestData['wtg_verified'];
            }
            if(isset($arrRequestData['installer_name']) && !empty($arrRequestData['installer_name'])) {
                $condition_arr['developers.installer_name like ']   = '%'.$arrRequestData['installer_name'].'%';
            }
            if(isset($arrRequestData['payment_status']) && $arrRequestData['payment_status']!='') {
                $condition_arr['GeoShiftingApplication.payment_status'] = $arrRequestData['payment_status'];
            }
            if(isset($arrRequestData['provisional_search_no']) && $arrRequestData['provisional_search_no']!='') {
                $condition_arr['applications.registration_no like ']    = '%'.$arrRequestData['provisional_search_no'].'%';
            }
            if(isset($arrRequestData['payment_date']) && $arrRequestData['payment_date']!='') {
                $arrRequestData['payment_date'] = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['payment_date'])));
                $condition_arr['GeoShiftingApplication.payment_date like '] = '%'.$arrRequestData['payment_date'].'%';
            }
            if(isset($arrRequestData['x_cordinate']) && $arrRequestData['x_cordinate']!='') {
                $condition_arr['GeoShiftingApplication.x_cordinate like ']  = $arrRequestData['modified_x_cordinate'];
            }
            if(isset($arrRequestData['y_cordinate']) && $arrRequestData['y_cordinate']!='') {
                $condition_arr['GeoShiftingApplication.y_cordinate like ']  = $arrRequestData['modified_y_cordinate'];
            }
 
            // if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
            //  if($arrRequestData['application_status'] == 3){
            //      $condition_arr['OR'] = array('application_geo_location.approved is NULL','application_geo_location.approved'=>3);
            //  } else if($arrRequestData['application_status'] == 5){
            //      $condition_arr['GeoShiftingApplication.approved'] = 5;
            //  }else{
            //      $condition_arr['GeoShiftingApplication.approved']   = $arrRequestData['application_status'];
            //  }
                
            // }
            
            if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
                if($arrRequestData['application_status'] == 3){

                    $condition_arr['GeoShiftingApplication.approved']   = 3;
                } else if($arrRequestData['application_status'] == 4){
                     $condition_arr['GeoShiftingApplication.approved']   = 4;
                }else if($arrRequestData['application_status'] == 1){
                    $condition_arr['GeoShiftingApplication.approved']   = 1;
                }else if($arrRequestData['application_status'] == 2){
                    $condition_arr['GeoShiftingApplication.approved']   = 2;
                }
                
            }

           //echo"<pre>"; print_r($arrRequestData); die();
            if(isset($arrRequestData['DateFrom']) && !empty($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateTo'])){
                //$where_data     = ['application_geo_location.id IS NOT'=>NULL];
                $condition_arr  = ["GeoShiftingApplication.created_date BETWEEN :start AND :end"];
                // $condition_arr['application_geo_location.payment_status '] = 1;
                
            }else{
                $condition_arr['GeoShiftingApplication.id IS NOT']  = NULL;
            }

            $condition_arr['OR'] = array('GeoShiftingApplication.payment_status'=>1,'GeoShiftingApplication.approved'=>2);
            $ApplyOnlinesList   = $this->find("all",[
                'fields'        => $fields,
                'join'          => $join_arr,
                'conditions'    => $condition_arr,
                //'group'    => $str_group_by,
                //'order'           => [$arrOrderBy[0]=>$arrOrderBy[1],'application_geo_location.created_date'=>$arrOrderBy[1]]]
                //'order'=>[$SortBy=>$Direction]
                'order'         => [$SortBy=>$Direction]]
            );
            // $ApplyOnlinesList   = $this->find("all",[
            //     'fields'        => $fields,
            //     'join'          => $join_arr,
            //     'conditions'    => $condition_arr,
            //     //'group'    => $str_group_by,
            //     'order'         => [$arrOrderBy[0]=>$arrOrderBy[1],'Applications.created'=>$arrOrderBy[1]]]);
           if(isset($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateFrom']) && !empty($arrRequestData['DateTo'])){
                $StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['DateFrom'])))." 00:00:00";
                $EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $arrRequestData['DateTo'])))." 23:59:59";
                $ApplyOnlinesList->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
            }
            //echo"<pre>"; print_r($arrRequestData['DateFrom']); die();
            //echo"<pre>"; print_r($ApplyOnlinesList); die();
            $arrResult['list']              = $ApplyOnlinesList;
            return $arrResult;
        // } 
    }
    /**
    * getDataApplications
    * Behaviour : public
    * Parameter : arrRequestData all parameter of request as well as session passed to this variable
    * @defination : Listing of applications.
    */
    public function getGeoLocationShiftingData_Downloadxl($arrRequestData=array(),$SortBy,$Direction) 
    {
        $fields             = [ 'id',
                                'application_id',
                                'geo_application_id',
                                'wtg_location',
                                'old_zone',
                                'old_x_cordinate',
                                'old_y_cordinate',
                                'payment_date',
                                'created_by',
                                'created_date',
                                'wtg_verified',
                                'modified_zone',
                                'modified_x_cordinate',
                                'modified_y_cordinate',
                                'approved',
                                'approved_by',
                                'approved_date',
                                'wtg_verified',
                                'wtg_verified_by',
                                'wtg_verified_date',
                                'application_geo_location.id',
                                'applications.installer_id',
                                'developer_customers.name',
                                'district_master.name',
                                'application_geo_location.geo_district',
                                'application_geo_location.geo_taluka',
                                'applications.registration_no',
                                
                                'geo_application_clashed_data.clashed_for',
                                'geo_application_clashed_data.created',
                                'members.address1',
                                'developers.installer_name'
                              ];
        
                                
        $arrOrderBy     = explode("|",$arrRequestData['order_by_form']);
        $join_arr       = [ 
                            'application_geo_location'  => ['table'=>'application_geo_location','type'=>'left','conditions'=>'GeoShiftingApplication.geo_application_id=application_geo_location.id'],
                            'applications'  => ['table'=>'applications','type'=>'left','conditions'=>'GeoShiftingApplication.application_id = applications.id'],
                            'application_category'  => ['table'=>'application_category','type'=>'left','conditions'=>'application_geo_location.application_type=application_category.id'],
                            'geo_application_clashed_data'  => ['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'GeoShiftingApplication.id = geo_application_clashed_data.shifting_id'],
                            'district_master'       => ['table'=>'district_master','type'=>'left','conditions'=>'application_geo_location.geo_district=district_master.id'],'members'   => ['table'=>'members','type'=>'left','conditions'=>'GeoShiftingApplication.approved_by = members.id']
                                            ];
      
        // if(!empty($arrRequestData['customer_id'])) {
            
            $condition_arr      = array();
             array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = applications.customer_id']);
             array_push($join_arr,['table'=>'developers','type'=>'left','conditions'=>'developers.id = applications.installer_id']);
            $str_group_by       = 'GeoShiftingApplication.id';
            $flag_stages_table  = 0;
            
            
            // $condition_arr['OR'] = array('application_geo_location.payment_status'=>1,'application_geo_location.payment_status'=>2);
            if(isset($arrRequestData['application_type']) && !empty($arrRequestData['application_type'])) {
                $condition_arr['application_geo_location.application_type']           = $arrRequestData['application_type'];
            }
            if(isset($arrRequestData['geo_taluka']) && !empty($arrRequestData['geo_taluka'])) {
                $condition_arr['application_geo_location.geo_taluka']         = $arrRequestData['geo_taluka'];
            }
            if(isset($arrRequestData['geo_district']) && !empty($arrRequestData['geo_district'])) {
                $condition_arr['application_geo_location.geo_district']           = $arrRequestData['geo_district'];
            }
            if(isset($arrRequestData['wtg_location']) && !empty($arrRequestData['wtg_location'])) {
                $condition_arr['GeoShiftingApplication.wtg_location']           = $arrRequestData['wtg_location'];
            }
            if(isset($arrRequestData['action_by']) && !empty($arrRequestData['action_by'])) {
                $condition_arr['members.address1']          = $arrRequestData['action_by'];
            }
            if(isset($arrRequestData['wtg_verified']) && ($arrRequestData['wtg_verified']!='') ) {
                $condition_arr['GeoShiftingApplication.wtg_verified']           = $arrRequestData['wtg_verified'];
            }
            if(isset($arrRequestData['installer_name']) && !empty($arrRequestData['installer_name'])) {
                $condition_arr['developers.installer_name like ']   = '%'.$arrRequestData['installer_name'].'%';
            }
            if(isset($arrRequestData['payment_status']) && $arrRequestData['payment_status']!='') {
                $condition_arr['GeoShiftingApplication.payment_status'] = $arrRequestData['payment_status'];
            }
            if(isset($arrRequestData['provisional_search_no']) && $arrRequestData['provisional_search_no']!='') {
                $condition_arr['applications.registration_no like ']    = '%'.$arrRequestData['provisional_search_no'].'%';
            }
            if(isset($arrRequestData['payment_date']) && $arrRequestData['payment_date']!='') {
                $arrRequestData['payment_date'] = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['payment_date'])));
                $condition_arr['GeoShiftingApplication.payment_date like '] = '%'.$arrRequestData['payment_date'].'%';
            }
            if(isset($arrRequestData['x_cordinate']) && $arrRequestData['x_cordinate']!='') {
                $condition_arr['GeoShiftingApplication.x_cordinate like ']  = $arrRequestData['modified_x_cordinate'];
            }
            if(isset($arrRequestData['y_cordinate']) && $arrRequestData['y_cordinate']!='') {
                $condition_arr['GeoShiftingApplication.y_cordinate like ']  = $arrRequestData['modified_y_cordinate'];
            }
 
            // if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
            //  if($arrRequestData['application_status'] == 3){
            //      $condition_arr['OR'] = array('application_geo_location.approved is NULL','application_geo_location.approved'=>3);
            //  } else if($arrRequestData['application_status'] == 5){
            //      $condition_arr['GeoShiftingApplication.approved'] = 5;
            //  }else{
            //      $condition_arr['GeoShiftingApplication.approved']   = $arrRequestData['application_status'];
            //  }
                
            // }
            
            if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
                if($arrRequestData['application_status'] == 3){

                    $condition_arr['GeoShiftingApplication.approved']   = 3;
                } else if($arrRequestData['application_status'] == 4){
                     $condition_arr['GeoShiftingApplication.approved']   = 4;
                }else if($arrRequestData['application_status'] == 1){
                    $condition_arr['GeoShiftingApplication.approved']   = 1;
                }else if($arrRequestData['application_status'] == 2){
                    $condition_arr['GeoShiftingApplication.approved']   = 2;
                }
                
            }

           //echo"<pre>"; print_r($arrRequestData); die();
            if(isset($arrRequestData['DateFrom']) && !empty($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateTo'])){
                //$where_data     = ['application_geo_location.id IS NOT'=>NULL];
                $condition_arr  = ["GeoShiftingApplication.created_date BETWEEN :start AND :end"];
                // $condition_arr['application_geo_location.payment_status '] = 1;
                
            }else{
                $condition_arr['GeoShiftingApplication.id IS NOT']  = NULL;
            }

            $condition_arr['OR'] = array('GeoShiftingApplication.payment_status'=>1,'GeoShiftingApplication.approved'=>2);
            $ApplyOnlinesList   = $this->find("all",[
                'fields'        => $fields,
                'join'          => $join_arr,
                'conditions'    => $condition_arr,
                'group'    => $str_group_by,
                //'order'           => [$arrOrderBy[0]=>$arrOrderBy[1],'application_geo_location.created_date'=>$arrOrderBy[1]]]
                //'order'=>[$SortBy=>$Direction]
                'order'         => [$SortBy=>$Direction]]
            );
            // $ApplyOnlinesList   = $this->find("all",[
            //     'fields'        => $fields,
            //     'join'          => $join_arr,
            //     'conditions'    => $condition_arr,
            //     //'group'    => $str_group_by,
            //     'order'         => [$arrOrderBy[0]=>$arrOrderBy[1],'Applications.created'=>$arrOrderBy[1]]]);
           if(isset($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateFrom']) && !empty($arrRequestData['DateTo'])){
                $StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['DateFrom'])))." 00:00:00";
                $EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $arrRequestData['DateTo'])))." 23:59:59";
                $ApplyOnlinesList->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
            }
            //echo"<pre>"; print_r($arrRequestData['DateFrom']); die();
            //echo"<pre>"; print_r($ApplyOnlinesList); die();
            $arrResult['list']              = $ApplyOnlinesList;
            return $arrResult;
        // } 
    }
     
}