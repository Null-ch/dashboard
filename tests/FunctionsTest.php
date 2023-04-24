<?php

use PHPUnit\Framework\TestCase;

require_once 'src/functions.php';

class FunctionsTest extends TestCase
{
    public function testGetManageCount()
    {
        $arr = [
            ['Менеджер' => 'Игорь Викторович'],
            ['Менеджер' => 'Корнилов Владимир'],
            ['Менеджер' => 'Корнилов Владимир'],
            ['Менеджер' => 'Корнилов Владимир'],
            ['Менеджер' => 'Иванов Иван'],
        ];
        $manageCount = [
            'Игорь Викторович' => 1,
            'Корнилов Владимир' => 3,
            'Иванов Иван' => 1
        ];
        $result = getManageCount($arr);
        $this->assertEquals($manageCount, $result);
    }

    public function testGetProfitPerClient()
    {
        $arr = [
            ['ПрибыльРейс' => 329687.95, 'Контрагент' => 'ГОРОД АО'],
            ['ПрибыльРейс' => 329687.95, 'Контрагент' => 'ДСК МАГИСТРАЛЬ ООО'],
            ['ПрибыльРейс' => 329687.95, 'Контрагент' => 'АЛЬЯНС-ХОЛДИНГ ООО'],
        ];
        $profitPerClient = [
            'ГОРОД АО' => 329687.95,
            'ДСК МАГИСТРАЛЬ ООО' => 329687.95,
            'АЛЬЯНС-ХОЛДИНГ ООО' => 329687.95
        ];
        $result = getProfitPerClient($arr);
        $this->assertEquals($profitPerClient, $result);
    }
}
