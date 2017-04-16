<?php
namespace Framework\Syscrack\Game\Operations;

/**
 * Lewis Lancaster 2017
 *
 * Class Hack
 *
 * @package Framework\Syscrack\Game\Operations
 */

use Framework\Application\Container;
use Framework\Application\Settings;
use Framework\Exceptions\SyscrackException;
use Framework\Syscrack\Game\Structures\Operation as Structure;
use Framework\Syscrack\Game\Operation as BaseClass;
use Framework\Syscrack\Game\AddressDatabase;

class Hack extends BaseClass implements Structure
{

    /**
     * @var AddressDatabase;
     */

    protected $addressdatabase;

    /**
     * Hack constructor.
     */

    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Called when this process request is created
     *
     * @param $timecompleted
     *
     * @param $computerid
     *
     * @param $userid
     *
     * @param $process
     *
     * @param array $data
     *
     * @return mixed
     */

    public function onCreation($timecompleted, $computerid, $userid, $process, array $data)
    {

        if( $this->checkData( $data, ['ipaddress'] ) == false )
        {

            return false;
        }

        if( $this->computer->getComputer( $this->computer->getCurrentUserComputer() )->ipaddress == $data['ipaddress'] )
        {

            return false;
        }

        $this->addressdatabase = new AddressDatabase( Container::getObject('session')->getSessionUser() );

        if( $this->addressdatabase->getComputerByIPAddress( $data['ipaddress' ] ) != null )
        {

            return false;
        }

        $usercomputer = $this->computer->getComputer( $this->computer->getCurrentUserComputer() );

        $computer = $this->internet->getComputer( $data['ipaddress'] );

        if( $this->computer->hasType($computer->computerid, Settings::getSetting('syscrack_hasher_type'), true ) == false )
        {

            return true;
        }

        if( $this->computer->hasType( $usercomputer->computerid, Settings::getSetting('syscrack_cracker_type'), true ) == false )
        {

            return false;
        }

        if( $this->softwares->getSoftware( $this->computer->getCracker( $usercomputer->computerid ) )->level
            < $this->softwares->getSoftware( $this->computer->getHasher( $computer->computerid ) )->level )
        {

            return false;
        }

        return true;
    }

    /**
     * Called when this process request is created
     *
     * @param $timecompleted
     *
     * @param $computerid
     *
     * @param $userid
     *
     * @param $process
     *
     * @param array $data
     */

    public function onCompletion($timecompleted, $timestarted, $computerid, $userid, $process, array $data)
    {

        if( $this->checkData( $data, ['ipaddress'] ) == false )
        {

            throw new SyscrackException();
        }

        $this->addressdatabase = new AddressDatabase( Container::getObject('session')->getSessionUser() );

        $this->addressdatabase->addComputer( array(
            'computerid'        => $this->internet->getComputer( $data['ipaddress'] )->computerid,
            'ipaddress'         => $data['ipaddress'],
            'timehacked'        => time()
        ));

        $this->addressdatabase->saveDatabase();

        $this->redirectSuccess( $data['ipaddress'] );
    }

    /**
     * Gets the completion speed of this action
     *
     * @param $computerid
     *
     * @param $process
     *
     * @param null $softwareid
     *
     * @return int
     */

    public function getCompletionSpeed($computerid, $process, $softwareid=null)
    {

        return $this->calculateProcessingTime( $computerid, Settings::getSetting('syscrack_cpu_type'), 5.5, $softwareid );
    }
}