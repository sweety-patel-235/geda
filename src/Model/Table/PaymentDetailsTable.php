<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class PaymentDetailsTable extends Table
{
    /*public function initialize(array $config)
    {
        $this->table('payments');
    }*/

    public static function defaultConnectionName()
    {
        return 'yugtia_payment';
    }
}