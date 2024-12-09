<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class SiteSurveysImagesTable extends Table {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
    public $Name    = 'SiteSurveysImages';

    public $INVERTER_IMG_LIMIT          = 4;
    public $BATTERY_IMG_LIMIT           = 4;
    public $ACDB_IMG_LIMIT              = 4;
    public $METERING_IMG_LIMIT          = 4;
    public $TAKE_PHOTOGRAPH_IMG_LIMIT   = 7;
	public $ELECTRICITY_BILL_IMG_LIMIT 	= 12;

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'project_survey_photos';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
}
?>