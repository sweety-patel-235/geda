<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Datasource\ConnectionManager;

class BacklogReportController extends AppController
{
	
	public function initialize()
	{
		parent::initialize();
		$this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('SchemeMaster');
		$this->loadModel('BranchMasters');
		$this->loadModel('DiscomMaster');
		$this->set('pageTitle','Pendency Report');
		/*$currentYear	= date('Y');
		$startYear		= 2019;
		$TotalDays 		= 365 * ($currentYear - $startYear);*/
		
	}
	public function index() {
		$arrOutput 			= array();
		$schemeArr 			= array();
		$branchArr 			= array();
		$result 			= array();
		$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
		$branchData 		= $this->BranchMasters->find('all',array('conditions'=>array('state'=>4)))->toArray();
		$member_id 			= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		$authority_account 	= $this->Session->read('Members.authority_account');
		// || !in_array($member_id, SUBCATEGORY_RIGHTS)
		if(empty($member_id)) {
			return $this->redirect('home');
		}
		$this->layout 		= 'frontend';
		$currentYear		= date('Y');
		$startYear			= 2019;
		$TotalDays 			= 365 * ($currentYear - $startYear);
		$field 				= "area";
		$id 				= $area;
		$fieldEmpty 		= "circle IS NULL";
		if (!empty($section)) {
			$field 		= "section";
			$id 		= $section;
			$fieldEmpty = "";
		} else if (!empty($subdivision)) {
			$field 		= "subdivision";
			$id 		= $subdivision;
			$fieldEmpty = "";
		} else if (!empty($division)) {
			$field 		= "division";
			$id  		= $division;
			$fieldEmpty = "subdivision IS NULL";
		} else if (!empty($circle)) {
			$field  	= "circle";
			$id 		= $circle;
			$fieldEmpty = "division IS NULL";
		}
		$main_branch_id 	= array("field"=>$field,"id"=>$id);
		$fetchIdField 		= 'discom_id';
		$fetchTitleField 	= 'short_title';
		if(!empty($area)) {
			$result 		= $this->ApplyOnlines->getDiscomWiseBacklog($area,$circle,$division,$subdivision);
		}
		if(!empty($schemeData)) {
			foreach($schemeData as $schemeDetails) {
				$schemeArr[$schemeDetails->id]	= $schemeDetails->scheme_name;
			}
		}
		if(empty($area)) {
			if(!empty($schemeData)) {
				foreach($schemeData as $schemeDetails) {
					//$schemeArr[$schemeDetails->id]	= $schemeDetails->scheme_name;
					if(!empty($branchData))	 {
						foreach($branchData as $branchDetails) {
							$branchArr[$branchDetails->$fetchIdField] 														= $branchDetails->$fetchTitleField;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['query_agency_side']['0-4']		= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['query_agency_side']['5-15']		= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['query_agency_side']['16-45']		= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['query_agency_side']['46-90']		= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['query_agency_side']['90-'.$TotalDays]= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['doc_pending']['0-4']				= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['doc_pending']['5-15']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['doc_pending']['16-45']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['doc_pending']['46-90']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['doc_pending']['90-'.$TotalDays]	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['feasibility_pending']['0-4']		= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['feasibility_pending']['5-15']	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['feasibility_pending']['16-45']	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['feasibility_pending']['46-90']	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['feasibility_pending']['90-'.$TotalDays]= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['intimation_approval_pending']['0-4']		= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['intimation_approval_pending']['5-15']	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['intimation_approval_pending']['16-45']	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['intimation_approval_pending']['46-90']	= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['intimation_approval_pending']['90-'.$TotalDays]= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['meter_pending']['0-4']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['meter_pending']['5-15']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['meter_pending']['16-45']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['meter_pending']['46-90']			= 0;
							$arrOutput[$schemeDetails->id][$branchDetails->$fetchIdField]['meter_pending']['90-'.$TotalDays]= 0;
						}
					}
				}
			}

			$connection 			= ConnectionManager::get('default');
			
			$backlogPendingQuery 	= $connection->execute("CALL backlog_doc_pending_query('0', '4', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPendingQuery)) {
				foreach($backlogPendingQuery as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['query_agency_side']['0-4'] 	= $queryData['numApplication'];
				}
			}
			$backlogPendingQuery 	= $connection->execute("CALL backlog_doc_pending_query('5', '15', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPendingQuery)) {
				foreach($backlogPendingQuery as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['query_agency_side']['5-15'] 	= $queryData['numApplication'];
				}
			}
			$backlogPendingQuery 	= $connection->execute("CALL backlog_doc_pending_query('16', '45', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPendingQuery)) {
				foreach($backlogPendingQuery as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['query_agency_side']['16-45'] = $queryData['numApplication'];
				}
			}
			$backlogPendingQuery 	= $connection->execute("CALL backlog_doc_pending_query('46', '90', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPendingQuery)) {
				foreach($backlogPendingQuery as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['query_agency_side']['46-90'] = $queryData['numApplication'];
				}
			}
			$backlogPendingQuery 	= $connection->execute("CALL backlog_doc_pending_query('90', $TotalDays, @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPendingQuery)) {
				foreach($backlogPendingQuery as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['query_agency_side']['90-'.$TotalDays] = $queryData['numApplication'];
				}
			}


			$backlogPending 	= $connection->execute("CALL backlog_doc_pending('0', '4', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPending)) {
				foreach($backlogPending as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['doc_pending']['0-4'] 	= $queryData['numApplication'];
				}
			}
			$backlogPending 	= $connection->execute("CALL backlog_doc_pending('5', '15', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPending)) {
				foreach($backlogPending as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['doc_pending']['5-15'] 	= $queryData['numApplication'];
				}
			}
			$backlogPending 	= $connection->execute("CALL backlog_doc_pending('16', '45', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPending)) {
				foreach($backlogPending as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['doc_pending']['16-45'] 	= $queryData['numApplication'];
				}
			}
			$backlogPending 	= $connection->execute("CALL backlog_doc_pending('46', '90', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPending)) {
				foreach($backlogPending as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['doc_pending']['46-90'] 	= $queryData['numApplication'];
				}
			}
			$backlogPending 	= $connection->execute("CALL backlog_doc_pending('90', $TotalDays, @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogPending)) {
				foreach($backlogPending as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['doc_pending']['90-'.$TotalDays] 	= $queryData['numApplication'];
				}
			}
/*
			$backlogFesibility 	= $connection->execute("CALL backlog_feasibility_pending('0', '4', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogFesibility)) {
				foreach($backlogFesibility as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['feasibility_pending']['0-4']	= $queryData['numApplication'];
				}
			}
			$backlogFesibility 	= $connection->execute("CALL backlog_feasibility_pending('5', '15', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogFesibility)) {
				foreach($backlogFesibility as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['feasibility_pending']['5-15']	= $queryData['numApplication'];
				}
			}
			$backlogFesibility 	= $connection->execute("CALL backlog_feasibility_pending('16', '45', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogFesibility)) {
				foreach($backlogFesibility as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['feasibility_pending']['16-45']	= $queryData['numApplication'];
				}
			}
			$backlogFesibility 	= $connection->execute("CALL backlog_feasibility_pending('46', '90', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogFesibility)) {
				foreach($backlogFesibility as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['feasibility_pending']['46-90']	= $queryData['numApplication'];
				}
			}
			
			$backlogFesibility 	= $connection->execute("CALL backlog_feasibility_pending('90', $TotalDays, @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogFesibility)) {
				foreach($backlogFesibility as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['feasibility_pending']['90-'.$TotalDays]	= $queryData['numApplication'];
				}
			}
*/


/*
			$backlogIntimationApprovalPending 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '4', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogIntimationApprovalPending)) {
				foreach($backlogIntimationApprovalPending as $queryData) {
					if(!empty($queryData['schemeId'])) {
						$arrOutput[$queryData['schemeId']][$queryData['outArea']]['intimation_approval_pending']['0-4']	= $queryData['numApplication'];
					}
				}
			}
			$backlogIntimationApprovalPending 	= $connection->execute("CALL backlog_intimation_approval_pending('5', '15', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogIntimationApprovalPending)) {
				foreach($backlogIntimationApprovalPending as $queryData) {
					if(!empty($queryData['schemeId'])) {
						$arrOutput[$queryData['schemeId']][$queryData['outArea']]['intimation_approval_pending']['5-15']	= $queryData['numApplication'];
					}
				}
			}
			$backlogIntimationApprovalPending 	= $connection->execute("CALL backlog_intimation_approval_pending('16', '45', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogIntimationApprovalPending)) {
				foreach($backlogIntimationApprovalPending as $queryData) {
					if(!empty($queryData['schemeId'])) {
						$arrOutput[$queryData['schemeId']][$queryData['outArea']]['intimation_approval_pending']['16-45']	= $queryData['numApplication'];
					}
				}
			}
			$backlogIntimationApprovalPending 	= $connection->execute("CALL backlog_intimation_approval_pending('46', '90', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogIntimationApprovalPending)) {
				foreach($backlogIntimationApprovalPending as $queryData) {
					if(!empty($queryData['schemeId'])) {
						$arrOutput[$queryData['schemeId']][$queryData['outArea']]['intimation_approval_pending']['46-90']	= $queryData['numApplication'];
					}
				}
			}
			$backlogIntimationApprovalPending 	= $connection->execute("CALL backlog_intimation_approval_pending('90', $TotalDays, @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogIntimationApprovalPending)) {
				foreach($backlogIntimationApprovalPending as $queryData) {
					if(!empty($queryData['schemeId'])) {
						$arrOutput[$queryData['schemeId']][$queryData['outArea']]['intimation_approval_pending']['90-'.$TotalDays]	= $queryData['numApplication'];
					}
				}
			}*/

			/*$backlogMeterInstallation 	= $connection->execute("CALL backlog_meter_installation('0', '4', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogMeterInstallation)) {
				foreach($backlogMeterInstallation as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['meter_pending']['0-4']	= $queryData['numApplication'];
				}
			}
			$backlogMeterInstallation 	= $connection->execute("CALL backlog_meter_installation('5', '15', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogMeterInstallation)) {
				foreach($backlogMeterInstallation as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['meter_pending']['5-15']	= $queryData['numApplication'];
				}
			}
			$backlogMeterInstallation 	= $connection->execute("CALL backlog_meter_installation('16', '45', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogMeterInstallation)) {
				foreach($backlogMeterInstallation as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['meter_pending']['16-45']	= $queryData['numApplication'];
				}
			}
			$backlogMeterInstallation 	= $connection->execute("CALL backlog_meter_installation('46', '90', @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogMeterInstallation)) {
				foreach($backlogMeterInstallation as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['meter_pending']['46-90']	= $queryData['numApplication'];
				}
			}
			$backlogMeterInstallation 	= $connection->execute("CALL backlog_meter_installation('90', $TotalDays, @numApplication, @schemeId, @outArea,'','')")->fetchAll('assoc');
			if(!empty($backlogMeterInstallation)) {
				foreach($backlogMeterInstallation as $queryData) {
					$arrOutput[$queryData['schemeId']][$queryData['outArea']]['meter_pending']['90-'.$TotalDays]	= $queryData['numApplication'];
				}
			}*/
			$result['arrOutput'] 	= $arrOutput;
			$result['branchArr'] 	= $branchArr;
		}

		//$this->set("arrOutput",$arrOutput);
		$this->set("schemeArr",$schemeArr);
		$this->set("result",$result);
		//$this->set("branchArr",$branchArr);
	}
	public function download($queryParams=''){
		$curdate 	= date("YmdHis");
		$filename 	= "pendency_$curdate.csv";
		$delimiter 	= ",";
		$arrayOut 	= array();
		$member_id 			= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		$authority_account 	= $this->Session->read('Members.authority_account');
		$field 				= "area";
		$id 				= $area;
		$fieldEmpty 		= "circle IS NULL";
		$groupField 		= "circle";
		if (!empty($section)) {
			$field 		= "section";
			$id 		= $section;
			$fieldEmpty = "";
			$groupField = "";
		} else if (!empty($subdivision)) {
			$field 		= "subdivision";
			$id 		= $subdivision;
			$fieldEmpty = "";
			$groupField = "subdivision";
		} else if (!empty($division)) {
			$field 		= "division";
			$id  		= $division;
			$fieldEmpty = "subdivision IS NULL";
			$groupField = "subdivision";
		} else if (!empty($circle)) {
			$field  	= "circle";
			$id 		= $circle;
			$fieldEmpty = "division IS NULL";
			$groupField = "division";
		}
		$main_branch_id 	= array("field"=>$field,"id"=>$id);
		$fetchIdField 		= 'discom_id';
		$fetchTitleField 	= 'short_title';
		$daysWithAlpha 		= [	'0-4'			=> 'A',
								'5-15'			=> 'B',
								'16-45'			=> 'C',
								'46-90'			=> 'D',
								'90-'.$TotalDays=> 'E'
							];
		$typePendancy 		= [	'd'		=> 'Backlog in Doc. Verification',
								'q'		=> 'Backlog in Doc. Verification for Rooftop Solar Application under Query Agency Side',
								'f'		=> 'Backlog in Issue of F.Q.(Estimate) after Documents are Verified',
								'iap'	=> 'Backlog in Verification of System installed',
								'm'		=> 'Backlog in providing Bi-Directional Meter after intimation Approved'
							];
		if(!empty($queryParams)) {
			$arrData 		= explode("_",$queryParams);
			$connection 	= ConnectionManager::get('default');
			$arrDays[0] 	= 0;
			$arrDays[1] 	= 7;
			$currentYear	= date('Y');
			$startYear		= 2019;
			$TotalDays 		= 365 * ($currentYear - $startYear);
			if(isset($arrData[3])) {
				$arrDays 	= explode("-",$arrData[3]);
			}
			$daysWithAlpha 		= [	'0-4'			=> 'A',
								'5-15'			=> 'B',
								'16-45'			=> 'C',
								'46-90'			=> 'D',
								'90-'.$TotalDays=> 'E'
							];
			$dayStr 		= $arrDays[0]."-".$arrDays[1];
			$pendancyType 	= '';
			switch ($arrData[0]) {
				case 'd':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['d'];
					break;
				case 'dt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['d'];
					break;
				case 'schemedt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['d'];
					break;
				case 'q':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_query('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_query_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['q'];
					break;
				case 'qt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_query('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_query_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['q'];
					break;
				case 'schemeqt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_query('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_doc_pending_query_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc'); //".$main_branch_id['id']."
					}
					$pendancyType 		= $typePendancy['q'];
					break;
				
				case 'f':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_feasibility_pending('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_feasibility_pending_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['f'];
					break;
				case 'ft':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_feasibility_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_feasibility_pending_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['f'];
					break;
				case 'schemeft':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_feasibility_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_feasibility_pending_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['f'];
					break;
				
				case 'iap':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_intimation_approval_pending('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['iap'];
					break;
				case 'iapt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['iap'];
					break;
				case 'schemeiapt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['iap'];
					break;
				
				case 'm':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_meter_installation('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_meter_installation_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['m'];
					break;
				case 'mt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_meter_installation('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_meter_installation_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['m'];
					break;
				case 'schememt':
					if(empty($area)) {
						$backlogData 	= $connection->execute("CALL backlog_meter_installation('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
					} else {
						$backlogData 	= $connection->execute("CALL backlog_meter_installation_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					$pendancyType 		= $typePendancy['m'];
					break;
				case 'colDiscom':
					if(empty($area)) {
						$dt 	= $connection->execute("CALL backlog_doc_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
						$qt 	= $connection->execute("CALL backlog_doc_pending_query('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
						$ft 	= $connection->execute("CALL backlog_feasibility_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
						$iapt 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
						$mt 	= $connection->execute("CALL backlog_meter_installation('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','".$arrData[2]."')")->fetchAll('assoc');
					} else {

						$dt 	= $connection->execute("CALL backlog_doc_pending_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$qt 	= $connection->execute("CALL backlog_doc_pending_query_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$ft 	= $connection->execute("CALL backlog_feasibility_pending_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$iapt 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$mt 	= $connection->execute("CALL backlog_meter_installation_discom('0', '".$TotalDays."', '".$arrData[1]."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}
					if(!empty($dt)) {
						foreach($dt as $k=>$output) {
							$dt[$k]['pendancyType'] = $typePendancy['d'];
						}
					}
					if(!empty($qt)) {
						foreach($qt as $k=>$output) {
							$qt[$k]['pendancyType'] = $typePendancy['q'];
						}
					}
					if(!empty($ft)) {
						foreach($ft as $k=>$output) {
							$ft[$k]['pendancyType'] = $typePendancy['f'];
						}
					}
					if(!empty($iapt)) {
						foreach($iapt as $k=>$output) {
							$iapt[$k]['pendancyType'] = $typePendancy['iap'];
						}
					}
					if(!empty($mt)) {
						foreach($mt as $k=>$output) {
							$mt[$k]['pendancyType'] = $typePendancy['m'];
						}
					}
					$backlogData = (array_merge($dt,$qt,$ft,$iapt,$mt));
					break;
				case 'colDiscomSchemet':
					if(empty($area)) {
						$dt 	= $connection->execute("CALL backlog_doc_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
						$qt 	= $connection->execute("CALL backlog_doc_pending_query('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
						$ft 	= $connection->execute("CALL backlog_feasibility_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
						$iapt 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
						$mt 	= $connection->execute("CALL backlog_meter_installation('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$arrData[1]."','')")->fetchAll('assoc');
					} else {
						$dt 	= $connection->execute("CALL backlog_doc_pending_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$qt 	= $connection->execute("CALL backlog_doc_pending_query_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$ft 	= $connection->execute("CALL backlog_feasibility_pending_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$iapt 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						$mt 	= $connection->execute("CALL backlog_meter_installation_discom('0', '".$TotalDays."', '".$arrData[1]."', '', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
					}			 
					if(!empty($dt)) {
						foreach($dt as $k=>$output) {
							$dt[$k]['pendancyType'] = $typePendancy['d'];
						}
					}
					if(!empty($qt)) {
						foreach($qt as $k=>$output) {
							$qt[$k]['pendancyType'] = $typePendancy['q'];
						}
					}
					if(!empty($ft)) {
						foreach($ft as $k=>$output) {
							$ft[$k]['pendancyType'] = $typePendancy['f'];
						}
					}
					if(!empty($iapt)) {
						foreach($iapt as $k=>$output) {
							$iapt[$k]['pendancyType'] = $typePendancy['iap'];
						}
					}
					if(!empty($mt)) {
						foreach($mt as $k=>$output) {
							$mt[$k]['pendancyType'] = $typePendancy['m'];
						}
					}
					$backlogData = (array_merge($dt,$qt,$ft,$iapt,$mt));
					break;
				case 'dschemeTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						
					}					
					$pendancyType 		= $typePendancy['d'];
					break;
				case 'dschemetTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						
					}					
					$pendancyType 		= $typePendancy['d'];
					break;
				case 'qschemeTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_query('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_query_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['q'];
					break;
				case 'qschemetTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_query('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_query_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['q'];
					break;
				case 'fschemeTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_feasibility_pending('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_feasibility_pending_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['f'];
					break;
				case 'fschemetTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_feasibility_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_feasibility_pending_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['f'];
					break;
				case 'iapschemeTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_intimation_approval_pending('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['iap'];
					break;
				case 'iapschemetTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['iap'];
					break;
				case 'mschemeTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_meter_installation('".$arrDays[0]."', '".$arrDays[1]."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_meter_installation_discom('".$arrDays[0]."', '".$arrDays[1]."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['m'];
					break;
				case 'mschemetTotal':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_meter_installation('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_meter_installation_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
					}						
					$pendancyType 		= $typePendancy['m'];
					break;
				case 'grandt':
					$schemeData 		= $this->SchemeMaster->find('all',array('order'=>array('scheme_code'=>'desc')))->toArray();
					$backlogData 		= array();
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						if(!empty($backlogData)) {
							foreach($backlogData as $k=>$output) {
								$backlogData[$k]['pendancyType'] = $typePendancy['d'];
							}
						}
					}					
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_query('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_doc_pending_query_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						if(!empty($backlogData)) {
							foreach($backlogData as $k=>$output) {
								$backlogData[$k]['pendancyType'] = $typePendancy['q'];
							}
						}
					}						
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_feasibility_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_feasibility_pending_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						if(!empty($backlogData)) {
							foreach($backlogData as $k=>$output) {
								$backlogData[$k]['pendancyType'] = $typePendancy['f'];
							}
						}
					}		
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_intimation_approval_pending('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_intimation_approval_pending_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						if(!empty($backlogData)) {
							foreach($backlogData as $k=>$output) {
								$backlogData[$k]['pendancyType'] = $typePendancy['iap'];
							}
						}
					}	
					foreach($schemeData as $schemeDetails) {
						if(empty($area)) {
							$grandTotal 	= $connection->execute("CALL backlog_meter_installation('0', '".$TotalDays."', @numApplication, @schemeId, @outArea,'".$schemeDetails->id."','".$arrData[2]."')")->fetchAll('assoc');
						} else {
							$grandTotal 	= $connection->execute("CALL backlog_meter_installation_discom('0', '".$TotalDays."', '".$schemeDetails->id."','".$arrData[2]."', '".$main_branch_id['id']."', '".$groupField."','".$field."')")->fetchAll('assoc');
						}
						$backlogData = (array_merge($backlogData,$grandTotal));
						if(!empty($backlogData)) {
							foreach($backlogData as $k=>$output) {
								$backlogData[$k]['pendancyType'] = $typePendancy['m'];
							}
						}
					}			
				break;
				default:
					# code...
					break;
			}
			
			if(!empty($backlogData)) {
				foreach($backlogData as $queryData) {
					if(empty($area)) {
						$queryData['consumer_name'] = str_replace(array(","), array(" -"), $queryData['consumer_name']);
					} else {
						$queryData['name_of_consumer_applicant'] = str_replace(array(","), array(" -"), ($queryData['name_of_consumer_applicant']." ".$queryData['last_name']." ".$queryData['third_name']));
						//unset($queryData['name_of_consumer_applicant']);
						unset($queryData['last_name']);
						unset($queryData['third_name']);
					}
					if($arrData[0]!='colDiscomSchemet' &&  $arrData[0]!='colDiscom' && $arrData[0]!='grandt') {
						$queryData['pendancyType']	= $pendancyType;
					}
					
					$queryData['slab']			= isset($daysWithAlpha[$dayStr]) ? $daysWithAlpha[$dayStr] : 'Total';
					if($queryData['slab'] == 'Total') {

						foreach($daysWithAlpha as $keyDays=>$daysText) {
							$arrKeyData		= explode("-",$keyDays);
							if($queryData['fallDays']>=$arrKeyData[0] && $queryData['fallDays']<=$arrKeyData[1])
							{
								$queryData['slab'] = $daysText;
								break;
							}
						}
					}
					$arrayOut[] 				= $queryData;
				}
			}
		}

		$array_header = ['Application ID','Consumer Name','Application No.','Scheme','Consumer No.','Discom Name','Circle','Division/Zone','Sub-division','PV Capacity','Type of pendency','Slab'];
		//$array[0] = array(1111,4444);
		//$array[1] = array(2222,5555);
		$keys = array_keys($array_header);
    
		$data 	= array();
		array_push($data, implode($delimiter, $array_header));
		// working with items
		foreach ($arrayOut as $item) {
			$values = array_values((array) $item);
			
			array_push($data, implode($delimiter, $values)); 
		}
		
		// flush buffer
		ob_flush();
		// mixing items
		$csvData = join("\n", $data);
		//setup headers to download the file
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		//setup utf8 encoding
		header('Content-Type: application/csv; charset=UTF-8');
		// showing the results
		die($csvData);
	}
}
