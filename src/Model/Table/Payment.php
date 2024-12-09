<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class PaymentsTable extends Table
{
    echo "Testing";
	public function initialize(array $config)
    {
        $this->table('paymentas');
    }

    public static function defaultConnectionName()
    {
        return 'lookup';
    }
}