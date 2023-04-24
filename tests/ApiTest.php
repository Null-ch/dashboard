<?php

use PHPUnit\Framework\TestCase;

require_once 'src/api.php';

class ApiTest extends TestCase {
    public function testAllData()
    {
        $result = getAllData();
        $count = count($result);
        $this->assertTrue($count > 0);
    }

    public function testGroupData()
    {
        $result = getGroupData();
        $count = count($result);
        $this->assertTrue($count > 0);
    }

}