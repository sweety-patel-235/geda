<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class AtomPaymentsTable extends Table
{

    public static function defaultConnectionName()
    {
        return 'yugtia_payment';
    }
}