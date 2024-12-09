<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class Sessions extends Table
{
   public function initialize(array $config)
    {
        $this->table('sessions');
    }

    public static function defaultConnectionName()
    {
        return 'lookup';
    }
}