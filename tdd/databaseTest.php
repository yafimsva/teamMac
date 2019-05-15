<?php
/**
 * Created by PhpStorm.
 * User: Brandon
 * Date: 5/14/2019
 * Time: 1:44 PM
 */

use PHPUnit\Framework\TestCase as TestCase;

class DatabaseTest extends TestCase
{
    private $database;

    public function setUp()
    {
        $this->database = new Database();
    }

    public function tearDown()
    {
        unset($this->database);
    }

    public function testDatabaseConnect()
    {

    }
}