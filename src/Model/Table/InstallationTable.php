<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class InstallationTable extends Table {
    /**
     * The status of $name is universe
     * Potential value are Class Name
     * @var String
     */
    var $TYPE_MODULES       = ['1'=>'Thin Film','2'=>'Monocrystalline','3'=>'Polycrystalline','4'=>'Any Other'];
    var $TYPE_INVERTERS     = ['1'=>'Hybrid Inverter','2'=>'PCU Inverter','3'=>'Micro Inverter','4'=>'String Inverter','5'=>'Central Inverter','6'=>'Any Other'];
    var $MAKE_INVERTERS     =  ['0' => 'Select',
                                '1' => 'ABB India Limited',
                                '2' => 'Consul Neowatt Power Solution Pvt. Ltd.',
                                '3' => 'AEI Power India Ltd.',
                                '4' => 'Delta Power Solutions India Pvt. Ltd.',
                                '5' => 'Enertech UPS Pvt. Ltd.',
                                '6' => 'Fronious India Pvt. Ltd.',
                                '7' => 'Fujiyama Power Systems (UTL)',
                                '8' => 'Huawaei International Pvt. Ltd.',
                                '9' => 'Jiangsu GoodWe Power Supply Technology Ltd.',
                                '10'=> 'Jiangsu ZeverSoolar New Energy Co. Ltd.',
                                '11'=> 'KACO New Energy GmbH',
                                '12'=>'Kstar New Energy Co. Ltd.',
                                '13'=>'Novergy Energy Solutions Pvt. Ltd.',
                                '14'=>'Optimal Power Synergy India Pvt. Ltd.',
                                '15'=>'Power One Micro System Pvt. Ltd.',
                                '28'=>'PVblink INVERTER',
                                '16'=>'Samil Power Co. Ltd.',
                                '17'=>'Schneider Electric India Pvt. Ltd.',
                                '18'=>'Shanghai Surpass Sun Electric Co. Ltd.',
                                '19'=>'Shenzhen Growatt New Energy',
                                '20'=>'Shenzhen JingFuYuan Tech. Co. Ltd.',
                                '21'=>'SMA Solar Technology AG',
                                '22'=>'Solax Power',
                                '23'=>'Studer innotec India Pvt. Ltd.',
                                '24'=>'Sun Grow Power Supply Co. Ltd.',
                                '25'=>'Techser Power Solutions Pvt. Ltd.',
                                '26'=>'Zever Solar',
                                '27'=>'Any Other'];
    var $MAKE_INVERTERS_SPIN     =  ['0' => '0',
                                '1' => '10',
                                '2' => '21',
                                '3' => '9',
                                '4' => '3',
                                '5' => '22',
                                '6' => '15',
                                '7' => '26',
                                '8' => '14',
                                '9' => '18',
                                '10'=> '16',
                                '11'=> '11',
                                '12'=>'25',
                                '13'=>'4',
                                '14'=>'5',
                                '15'=>'1',
                                '16'=>'23',
                                '17'=>'6',
                                '18'=>'12',
                                '19'=>'7',
                                '20'=>'19',
                                '21'=>'8',
                                '22'=>'24',
                                '23'=>'2',
                                '24'=>'17',
                                '25'=>'13',
                                '26'=>'20',
                                '27'=>'99',
                                '28'=>'27'];
    var $TYPE_INVERTERS_SPIN= ['1'=>'Hybrid','2'=>'PCU','3'=>'MICRO','4'=>'STRING','5'=>'CENTRAL','6'=>'Others'];
    var $INV_PHASE_TYPE     = ['1'=>'Single Phase','3'=>'3 Phase'];
    var $data               = array();
    public  $Name           = 'Installation';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'project_installation';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
    public function validationtab(Validator $validator)
    {   
        $validator->notEmpty('start_date', 'Start Date can not be blank.');
        $validator->notEmpty('end_date', 'End Date can not be blank');

        if(strtotime($this->data['start_date']) > strtotime($this->data['end_date'])){
            $validator->add("start_date", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Start date must be less than end date."
                    ]
                ]
            );
        }
        $flag_data_enter    = 0;
        $arr_module         = array();
        foreach($this->data['m_capacity'] as $key=>$val)
        {
            if(!empty($val) &&  !empty($this->data['m_modules'][$key]))
            {
                $flag_data_enter    = 1;
                $arr_module[]       = $val*$this->data['m_modules'][$key];
            }             
        }
        if($flag_data_enter == 0)
        {
            $validator->add("m_capacity", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Please enter at least one capacity and module."
                    ]
                ]
            );
        }
        $flag_data_enter    = 0;
        $arr_inverter       = array();
        foreach($this->data['i_capacity'] as $key=>$val)
        {
            if(!empty($val) &&  !empty($this->data['i_modules'][$key]))
            {
                $flag_data_enter    = 1;
                $arr_inverter[]     = $val*$this->data['i_modules'][$key];
            }             
        }
        if($flag_data_enter == 0)
        {
            $validator->add("i_capacity", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Please enter at least one capacity and module."
                    ]
                ]
            );
        }
        $module_sum     = array_sum($arr_module)/1000;
        $inverter_sum   = array_sum($arr_inverter)/1000;
        if(isset($this->data['applicationCategory']) && $this->data['applicationCategory']==3001)
        {
            if($this->data['approved_capacity']==10 || $this->data['approved_capacity']==6 || $this->data['approved_capacity']==100 || $this->data['approved_capacity']==50)
            {
                $plus_data  = $this->data['approved_capacity']; 
                $minus_data = $this->data['approved_capacity']-(($this->data['approved_capacity']*5)/100); 
            }
            else
            {
                $plus_data  = $this->data['approved_capacity']+(($this->data['approved_capacity']*5)/100);
                $minus_data = $this->data['approved_capacity']-(($this->data['approved_capacity']*5)/100);
            }
        }
        else
        {
            if($this->data['approved_capacity']==10 || $this->data['approved_capacity']==100)
            {
                $plus_data  = $this->data['approved_capacity']; 
                $minus_data = $this->data['approved_capacity']-(($this->data['approved_capacity']*5)/100); 
            }
            else
            {
                $plus_data  = $this->data['approved_capacity']+(($this->data['approved_capacity']*5)/100);
                $minus_data = $this->data['approved_capacity']-(($this->data['approved_capacity']*5)/100);
            }
        }
       /* if($module_sum>$plus_data ||  $minus_data>$module_sum)
        {
            $validator->add("cumulative_module", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Cumulative capacity of PV modules (kW) should less than ".$plus_data." and greater than ".$minus_data."."
                    ]
                ]
            );
        }*/
        /*if($inverter_sum>$this->data['approved_capacity'])
        {
            
            $validator->add("cumulative_inverter", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Cumulative capacity of PV Inverters(kW) should less than or equals to ".$this->data['approved_capacity']."."
                    ]
                ]
            );
        }*/ 
       return $validator;
    }
    public function customlessFunction($value, $context){
        return false;
    }
}