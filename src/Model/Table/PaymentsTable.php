<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class PaymentsTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('payments');
        $this->belongsTo('PartnerRequests', [
            'foreignKey' => 'partner_request_id',
            'joinType' => 'INNER',
        ]);
    }

    public static function defaultConnectionName()
    {
        return 'yugtia_payment';
    }
}