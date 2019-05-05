<?php
namespace Framework\Syscrack\Game\Operations;

/**
 * Lewis Lancaster 2017
 *
 * Class Install
 *
 * @package Framework\Syscrack\Game\Operations
 */

use Framework\Application\Settings;
use Framework\Exceptions\SyscrackException;
use Framework\Syscrack\Game\AddressDatabase;
use Framework\Syscrack\Game\BaseClasses\Operation as BaseClass;
use Framework\Syscrack\Game\Statistics;
use Framework\Syscrack\Game\Structures\Operation as Structure;
use Framework\Syscrack\Game\Viruses;

class Install extends BaseClass implements Structure
{

    /**
     * @var Viruses
     */

    protected $viruses;

    /**
     * @var Statistics
     */

    protected $statistics;

    /**
     * Install constructor.
     */

    public function __construct()
    {

        parent::__construct();

        if( isset( $this->viruses ) == false )
        {

            $this->viruses = new Viruses();
        }

        if( isset( $this->statistics ) == false )
        {

            $this->statistics = new Statistics();
        }
    }

    /**
     * Returns the configuration
     *
     * @return array
     */

    public function configuration()
    {

        return array(
            'allowsoftware'    => true,
            'allowlocal'        => true,
            'requiresoftware'  => true,
            'requireloggedin'   => true
        );
    }

    /**
     * Called when a process with the corresponding operation is created
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
     *
     * @return bool
     */

    public function onCreation($timecompleted, $computerid, $userid, $process, array $data)
    {

        if( $this->checkData( $data ) == false )
        {

            return false;
        }

        if( $this->software->canInstall( $data['softwareid'] ) == false )
        {

            return false;
        }

        if( $this->viruses->isVirus( $data['softwareid'] ) )
        {

            $software = $this->software->getSoftware( $data['softwareid'] );


            if( $this->getComputerId( $data['ipaddress'] ) == $computerid )
            {

                $this->redirectError('You cannot install a virus on your self, figures', $this->getRedirect( $data['ipaddress'] ) );
            }

            if( $this->viruses->virusAlreadyInstalled( $software->uniquename, $this->getComputerId( $data['ipaddress'] ) , $userid ) )
            {

                $this->redirectError('You already have a virus of this type installed', $this->getRedirect( $data['ipaddress'] ) );
            }
        }

        return true;
    }

    /**
     * Called when the process is completed
     *
     * @param $timecompleted
     *
     * @param $timestarted
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

        if( $this->checkData( $data ) == false )
        {

            throw new SyscrackException();
        }

        if( $this->internet->ipExists( $data['ipaddress'] ) == false )
        {

            $this->redirectError('Sorry, this ip address does not exist anymore', $this->getRedirect() );
        }

        if( $this->software->softwareExists( $data['softwareid'] ) == false )
        {

            $this->redirectError('Sorry, it looks like this software might have been deleted', $this->getRedirect( $data['ipaddress'] ) );
        }

        if( $this->software->isInstalled( $data['softwareid'], $this->getComputerId( $data['ipaddress'] ) ) )
        {

            $this->redirectError('Sorry, it looks like this software got installed already', $this->getRedirect( $data['ipaddress'] ) );
        }

        $this->software->installSoftware( $data['softwareid'], $userid );

        $this->computers->installSoftware( $this->getComputerId( $data['ipaddress'] ), $data['softwareid'] );

        $this->logInstall( $this->getSoftwareName( $data['softwareid' ] ),
            $this->getComputerId( $data['ipaddress'] ),$this->getCurrentComputerAddress() );

        $this->logLocal( $this->getSoftwareName( $data['softwareid' ] ),
            $this->computers->getCurrentUserComputer(), $data['ipaddress']);

        $this->software->executeSoftwareMethod( $this->software->getSoftwareNameFromSoftwareID( $data['softwareid'] ), 'onInstalled', array(
            'softwareid'    => $data['softwareid'],
            'userid'        => $userid,
            'computerid'    => $this->getComputerId( $data['ipaddress'] )
        ));

        if( $this->viruses->isVirus( $data['softwareid'] ) == true )
        {

            if( Settings::getSetting('syscrack_statistics_enabled') == true )
            {

                $this->statistics->addStatistic('virusinstalls');
            }

            $addressdatabase = new AddressDatabase();

            $addressdatabase->addVirus( $data['ipaddress'], $data['softwareid'], $userid );
        }

        if( isset( $data['redirect'] ) )
        {

            $this->redirectSuccess( $data['redirect'] );
        }
        else
        {

            $this->redirectSuccess( $this->getRedirect( $data['ipaddress'] ) );
        }
    }

    /**
     * Gets the completion speed
     *
     * @param $computerid
     *
     * @param $ipaddress
     *
     * @param null $softwareid
     *
     * @return int
     */

    public function getCompletionSpeed($computerid, $ipaddress, $softwareid=null)
    {

        return $this->calculateProcessingTime( $computerid, Settings::getSetting('syscrack_hardware_cpu_type'), 20, $softwareid );
    }

    /**
     * Gets the custom data for this operation
     *
     * @param $ipaddress
     *
     * @param $userid
     *
     * @return array
     */

    public function getCustomData($ipaddress, $userid)
    {

        return array();
    }

    /**
     * Called upon a post request to this operation
     *
     * @param $data
     *
     * @param $ipaddress
     *
     * @param $userid
     *
     * @return bool
     */

    public function onPost($data, $ipaddress, $userid)
    {

        return true;
    }

    /**
     * Logs a login action to the computers log
     *
     * @param $computerid
     *
     * @param $ipaddress
     */

    private function logInstall( $softwarename, $computerid, $ipaddress )
    {

        if( $this->computers->getCurrentUserComputer() == $computerid )
        {

            return;
        }

        $this->logToComputer('Installed file (' . $softwarename . ') on root', $computerid, $ipaddress );
    }

    /**
     * Logs to the computer
     *
     * @param $computerid
     *
     * @param $ipaddress
     */

    private function logLocal( $softwarename, $computerid, $ipaddress )
    {

        $this->logToComputer('Installed file (' . $softwarename . ') on <' . $ipaddress . '>', $computerid, 'localhost' );
    }
}