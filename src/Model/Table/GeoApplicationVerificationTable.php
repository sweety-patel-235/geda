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
class GeoApplicationVerificationTable extends AppTable
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
    var $table = 'geo_application_verification';
    
    public function initialize(array $config)
    {
        $this->table($this->table);
    }
    

     
}