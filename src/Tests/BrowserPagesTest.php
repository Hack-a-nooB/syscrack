<?php
/**
 * Created by PhpStorm.
 * User: newsy
 * Date: 06/05/2019
 * Time: 00:33
 */

namespace Framework\Tests;

use Framework\Syscrack\Game\BrowserPages;

class BrowserPagesTest extends BaseTestCase
{

    /**
     * @var BrowserPages
     */

    protected static $browserpages;

    public static function setUpBeforeClass(): void
    {

        if( isset( self::$browserpages ) == false )
            self::$browserpages = new BrowserPages();

        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    public function testGenerate()
    {

        $this->assertNotEmpty( self::$browserpages->get() );
    }
}