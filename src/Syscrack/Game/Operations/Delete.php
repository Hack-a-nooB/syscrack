<?php
namespace Framework\Syscrack\Game\Operations;

/**
 * Lewis Lancaster 2017
 *
 * Class Logout
 *
 * @package Framework\Syscrack\Game\Operations
 */

use Framework\Application\Settings;
use Framework\Exceptions\SyscrackException;
use Framework\Syscrack\Game\BaseClasses\Operation as BaseClass;
use Framework\Syscrack\Game\Structures\Operation as Structure;
use Framework\Syscrack\Game\Viruses;

class Delete extends BaseClass implements Structure
{

    /**
     * @var Viruses
     */

    protected static $viruses;

    /**
     * Delete constructor.
     */

    public function __construct()
    {

        if( isset( self::$viruses ) == false )
            self::$viruses = new Viruses();


        parent::__construct( true );
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

        if( $this->checkData( $data ) == false )
        {

            return false;
        }

        if( self::$computers->hasSoftware( $this->getComputerId( $data['ipaddress'] ), $data['softwareid'] ) == false )
        {

            return false;
        }

        $software = self::$software->getSoftware( $data['softwareid'] );

        if( self::$software->isInstalled( $software->softwareid, $this->getComputerId( $data['ipaddress'] ) ) )
        {

            if( self::$viruses->isVirus( $software->softwareid ) )
            {

                $this->redirectError('You cannot remove viruses, please use an anti-virus', $this->getRedirect( $data['ipaddress'] ) );
            }


            $this->redirectError('You cannot delete an installed software, uninstall it first', $this->getRedirect( $data['ipaddress'] ) );
        }
        else
        {

            if( self::$software->canRemove( $software->softwareid ) == false )
            {

                if( self::$viruses->isVirus( $software->softwareid ) == false )
                {

                    $this->redirectError('This software cannot be removed, sorry');
                }
            }
        }

        return true;
    }

    /**
     * @param $timecompleted
     * @param $timestarted
     * @param $computerid
     * @param $userid
     * @param $process
     * @param array $data
     */

    public function onCompletion($timecompleted, $timestarted, $computerid, $userid, $process, array $data)
    {

        if( $this->checkData( $data ) == false )
        {

            throw new SyscrackException();
        }

        if( self::$internet->ipExists( $data['ipaddress'] ) == false )
        {

            $this->redirectError('Sorry, this ip address does not exist anymore', $this->getRedirect() );
        }

        if( self::$software->softwareExists( $data['softwareid'] ) == false )
        {

            $this->redirectError('Sorry, it looks like this software might have been deleted', $this->getRedirect( $data['ipaddress'] ) );
        }

        $software = self::$software->getSoftware( $data['softwareid'] );

        self::$software->deleteSoftware( $software->softwareid );

        self::$computers->removeSoftware( $this->getComputerId( $data['ipaddress'] ), $software->softwareid );

        $this->logDelete( $software->softwarename, $this->getComputerId( $data['ipaddress'] ), self::$computers->getComputer( $computerid )->ipaddress );

        $this->logLocal( $software->softwarename, $data['ipaddress'] );

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
     * Returns the completion time for this action
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

        return $this->calculateProcessingTime( $computerid, Settings::getSetting('syscrack_hardware_cpu_type'), 5.5, $softwareid );
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
     * @param $softwarename
     * @param $computerid
     * @param $ipaddress
     */

    private function logDelete( $softwarename, $computerid, $ipaddress )
    {

        if( self::$computers->getCurrentUserComputer() == $computerid )
        {

            return;
        }

        $this->logToComputer('Deleted file <' . $softwarename . '> on root', $computerid, $ipaddress );
    }

    /**
     * Logs to the local log
     *
     * @param $softwarename
     *
     * @param $ipaddress
     */

    private function logLocal( $softwarename, $ipaddress )
    {

        $this->logToComputer('Deleted file <' . $softwarename . '> on ' . $ipaddress, self::$computers->getComputer( self::$computers->getCurrentUserComputer() )->computerid, 'localhost' );
    }
}