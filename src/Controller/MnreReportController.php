<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;

class MnreReportController extends AppController
{

	public function initialize()
	{
		parent::initialize();
		$this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->set('pageTitle','MNRE Statistics');
		$currentYear    = date('Y');
		for($i=2018;$i<=$currentYear;$i++) {
			$arrYears[$i]   = $i;
		}
		$arrDiscoms	= array(11=>"DGVCL",12=>"MGVCL",13=>"UGVCL",14=>"PGVCL",15=>"Torrent Power Ahmedabad",16=>"Torrent Power Surat");
		$arrMonths = array();
		for ($i=1; $i<=12; $i++) {
			$Month = date("M",strtotime("2018-".($i > 9?$i:"0".$i)."-01"));
			$arrMonths[$i] = $Month;
		}
		$this->set('arrYears',$arrYears);
		$this->set('arrDiscoms',$arrDiscoms);
		$this->set('arrMonths',$arrMonths);
	}

	public function index()
	{
		$member_id = $this->Session->read("Members.id");
		if(empty($member_id)) {
			return $this->redirect('home');
		}
		$this->layout 			= 'frontend';
		$Year 					= isset($this->request->data['Year'])?$this->request->data['Year']:date("Y");
		$discom 				= isset($this->request->data['discom'])?$this->request->data['discom']:"";
		$Month 					= isset($this->request->data['Month'])?$this->request->data['Month']:date("m");
		$arrFilter				= array('discom'=>$discom,"Year"=>$Year,"Month"=>$Month);
		$arrMonthwiseApplication= $this->MonthwiseApplicationStatisticsForApplication(4,$Year,$arrFilter);
		$arrMonthwiseCapacity 	= $this->MonthwiseApplicationStatisticsForCapacity(4,$Year,$arrFilter);

		$Year_1 				= isset($this->request->data['Year_1'])?$this->request->data['Year_1']:date("Y");
		$discom_1 				= isset($this->request->data['discom_1'])?$this->request->data['discom_1']:"";
		$Month_1 				= isset($this->request->data['Month_1'])?$this->request->data['Month_1']:date("m");
		$arrFilter_1			= array('discom'=>$discom_1,"Year"=>$Year_1,"Month"=>$Month_1);
		$arrDaywiseCapacity 	= $this->DaywiseApplicationStatisticsForCapacity(4,$arrFilter_1);
		$arrDaywiseApplication 	= $this->DaywiseApplicationStatisticsForApplication(4,$arrFilter_1);
		if (date("Y") == $Year) {
			$CurrentMonth = date("m",strtotime($this->NOW()));
		} else {
			$CurrentMonth = 12;
		}
		for ($i=1; $i<=$CurrentMonth; $i++) {
			$Month 										= date("M",strtotime($Year."-".($i > 9?$i:"0".$i)."-01"));
			$MonthWiseStatistics[$i]['Month'] 			= $Month;
			$MonthWiseStatistics[$i]['App_Count'] 		= isset($arrMonthwiseApplication[$i])?$arrMonthwiseApplication[$i]:0;
			$MonthWiseStatistics[$i]['App_Capacity'] 	= isset($arrMonthwiseCapacity[$i])?$arrMonthwiseCapacity[$i]:0;
		}
		$this->set("MonthWiseStatistics",$MonthWiseStatistics);
		$this->set("selectedyear",$Year);
		$this->set("selecteddiscom",$discom);
		$this->set("selectedMonth",$Month);

		$this->set("selectedyear_1",$Year_1);
		$this->set("selecteddiscom_1",$discom_1);
		$this->set("selectedmonth_1",$Month_1);
		$this->set("arrDaywiseCapacity",$arrDaywiseCapacity);
		$this->set("arrDaywiseApplication",$arrDaywiseApplication);
	}

	public function MonthwiseApplicationStatisticsForApplication($state = 4,$Year="",$arrFilter=array())
	{
		$MonthwiseStats     = array();
		$arrStatusStats 	= array();
		$YEAR 				= (!empty($Year)?$Year:date("Y"));
		$WhereCondition                 = array();
		$WhereCondition['states.id']    = $state;
		if (isset($arrFilter['discom']) && !empty($arrFilter['discom'])) {
			$WhereCondition['ApplyOnlines.discom'] = intval($arrFilter['discom']);
		}
		$ApplyOnlines = $this->ApplyOnlines->find();
		$ApplyOnlines->hydrate(false);
		$WhereCondition['apply_online_approvals.stage IN'] = array($this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$ApplyOnlines->select([ 'Apply_Month' => 'MONTH(apply_online_approvals.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])
		->group('Apply_Month')
		->join(	[
					['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
					['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id']
				]);
		$ApplyOnlines->where(array_merge($WhereCondition,array(function ($exp,$q) use ($YEAR) {
			$StartTime  = $YEAR."-01-01 00:00:00";
			$EndTime    = $YEAR."-12-31 23:59:59";
			return $exp->between('apply_online_approvals.created', $StartTime, $EndTime);
		})));
		$resultArray = $ApplyOnlines->toList();
		if (!empty($resultArray)) {
			foreach ($resultArray as $resultRow) {
				$arrStatusStats[$resultRow['Apply_Month']] = $resultRow['count'];
			}
		}
		if (date("Y") == $YEAR) {
			$CurrentMonth = date("m",strtotime($this->NOW()));
		} else {
			$CurrentMonth = 12;
		}
		for ($i=1; $i<=$CurrentMonth; $i++) {
			$MonthwiseStats[$i] = isset($arrStatusStats[$i])?$arrStatusStats[$i]:0;
		}
		return $MonthwiseStats;
	}

	public function MonthwiseApplicationStatisticsForCapacity($state = 4,$Year="",$arrFilter=array())
	{
		$MonthwiseStats     = array();
		$arrStatusStats 	= array();
		$YEAR 				= (!empty($Year)?$Year:date("Y"));
		$WhereCondition                 = array();
		$WhereCondition['states.id']    = $state;
		if (isset($arrFilter['discom']) && !empty($arrFilter['discom'])) {
			$WhereCondition['ApplyOnlines.discom'] = intval($arrFilter['discom']);
		}
		$ApplyOnlines = $this->ApplyOnlines->find();
		$ApplyOnlines->hydrate(false);
		$WhereCondition['apply_online_approvals.stage IN'] = array($this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$ApplyOnlines->select([ 'Apply_Month' => 'MONTH(apply_online_approvals.created)','total_sum' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
		->group('Apply_Month')
		->join(	[
					['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
					['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id']
				]);
		$ApplyOnlines->where(array_merge($WhereCondition,array(function ($exp,$q) use ($YEAR) {
			$StartTime  = $YEAR."-01-01 00:00:00";
			$EndTime    = $YEAR."-12-31 23:59:59";
			return $exp->between('apply_online_approvals.created', $StartTime, $EndTime);
		})));
		$resultArray = $ApplyOnlines->toList();
		if (!empty($resultArray)) {
			foreach ($resultArray as $resultRow) {
				$arrStatusStats[$resultRow['Apply_Month']] = $resultRow['total_sum'];
			}
		}
		if (date("Y") == $YEAR) {
			$CurrentMonth = date("m",strtotime($this->NOW()));
		} else {
			$CurrentMonth = 12;
		}
		for ($i=1; $i<=$CurrentMonth; $i++) {
			$MonthwiseStats[$i] = isset($arrStatusStats[$i])?$arrStatusStats[$i]:0;
		}
		return $MonthwiseStats;
	}

	public function DaywiseApplicationStatisticsForCapacity($state = 4,$arrFilter=array())
	{
		$MonthwiseStats     			= array();
		$arrStatusStats 				= array();
		$WhereCondition                 = array();
		$WhereCondition['states.id']    = $state;
		if (isset($arrFilter['discom']) && !empty($arrFilter['discom'])) {
			$WhereCondition['ApplyOnlines.discom'] = intval($arrFilter['discom']);
		}
		$ApplyOnlines = $this->ApplyOnlines->find();
		$ApplyOnlines->hydrate(false);
		$WhereCondition['apply_online_approvals.stage IN'] = array($this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$ApplyOnlines->select([ 'Apply_Day' => 'DAY(apply_online_approvals.created)','total_sum' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
		->group('Apply_Day')
		->join(	[
					['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
					['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id']
				]);
		$ApplyOnlines->where(array_merge($WhereCondition,array(function ($exp,$q) use ($arrFilter) {
			$YEAR 		= isset($arrFilter['Year'])?$arrFilter['Year']:date("Y");
			$Month 		= isset($arrFilter['Month'])?$arrFilter['Month']:date("m");
			$LastDay 	= date("t",strtotime($YEAR."-".$Month."-01"));
			$StartTime  = $YEAR."-$Month-01 00:00:00";
			$EndTime    = $YEAR."-$Month-$LastDay 23:59:59";
			return $exp->between('apply_online_approvals.created', $StartTime, $EndTime);
		})));
		$resultArray = $ApplyOnlines->toList();
		if (!empty($resultArray)) {
			foreach ($resultArray as $resultRow) {
				$arrStatusStats[$resultRow['Apply_Day']] = $resultRow['total_sum'];
			}
		}
		$YEAR 		= isset($arrFilter['Year'])?$arrFilter['Year']:date("Y");
		$Month 		= isset($arrFilter['Month'])?$arrFilter['Month']:date("m");
		$LastDay 	= date("t",strtotime($YEAR."-".$Month."-01"));
		$StartTime  = $YEAR."-$Month-01 00:00:00";
		$EndTime    = $YEAR."-$Month-$LastDay 23:59:59";
		for ($i=1; $i<=$LastDay; $i++) {
			$MonthwiseStats[$i] = isset($arrStatusStats[$i])?$arrStatusStats[$i]:0;
		}
		return $MonthwiseStats;
	}

	public function DaywiseApplicationStatisticsForApplication($state = 4,$arrFilter=array())
	{
		$MonthwiseStats     = array();
		$arrStatusStats 	= array();
		$YEAR 				= (!empty($Year)?$Year:date("Y"));
		$WhereCondition                 = array();
		$WhereCondition['states.id']    = $state;
		if (isset($arrFilter['discom']) && !empty($arrFilter['discom'])) {
			$WhereCondition['ApplyOnlines.discom'] = intval($arrFilter['discom']);
		}
		$ApplyOnlines = $this->ApplyOnlines->find();
		$ApplyOnlines->hydrate(false);
		$WhereCondition['apply_online_approvals.stage IN'] = array($this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$ApplyOnlines->select([ 'Apply_Day' => 'DAY(apply_online_approvals.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])
		->group('Apply_Day')
		->join(	[
					['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
					['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id']
				]);
		$ApplyOnlines->where(array_merge($WhereCondition,array(function ($exp,$q) use ($arrFilter) {
			$YEAR 		= isset($arrFilter['Year'])?$arrFilter['Year']:date("Y");
			$Month 		= isset($arrFilter['Month'])?$arrFilter['Month']:date("m");
			$LastDay 	= date("t",strtotime($YEAR."-".$Month."-01"));
			$StartTime  = $YEAR."-$Month-01 00:00:00";
			$EndTime    = $YEAR."-$Month-$LastDay 23:59:59";
			return $exp->between('apply_online_approvals.created', $StartTime, $EndTime);
		})));
		$resultArray = $ApplyOnlines->toList();
		if (!empty($resultArray)) {
			foreach ($resultArray as $resultRow) {
				$arrStatusStats[$resultRow['Apply_Day']] = $resultRow['count'];
			}
		}
		$YEAR 		= isset($arrFilter['Year'])?$arrFilter['Year']:date("Y");
		$Month 		= isset($arrFilter['Month'])?$arrFilter['Month']:date("m");
		$LastDay 	= date("t",strtotime($YEAR."-".$Month."-01"));
		$StartTime  = $YEAR."-$Month-01 00:00:00";
		$EndTime    = $YEAR."-$Month-$LastDay 23:59:59";
		for ($i=1; $i<=$LastDay; $i++) {
			$MonthwiseStats[$i] = isset($arrStatusStats[$i])?$arrStatusStats[$i]:0;
		}
		return $MonthwiseStats;
	}
}
?>