<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
/**
 * Short description for file
 * This Model use for Proposal table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Proposal information
 * @author    Pravin Sanghani
 * @version   RR
 * @since     File available since RR 1.0
 */
class GeoCoordinateOfflineApprovedTable extends Table {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */

	// public $Name 	= 'GeoCoordinateOfflineApproved';
    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */

	public $useTable = 'geo_coordinate_offline_approved';
	
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
    
}
?>