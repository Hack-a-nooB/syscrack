<?php
namespace Framework\Syscrack\Game;

/**
 * Lewis Lancaster 2017
 *
 * Class Computer
 *
 * @package Framework\Syscrack\Game
 */

use Framework\Application\Settings;
use Framework\Database\Tables\Computers;
use Framework\Exceptions\SyscrackException;

class Computer
{

    /**
     * @var Computers
     */

    protected $database;

    /**
     * Computer constructor.
     */

    public function __construct()
    {

        $this->database = new Computers();
    }

    /**
     * Returns true if the user has computers
     *
     * @param $userid
     *
     * @return bool
     */

    public function userHasComputers( $userid )
    {

        if( $this->database->getComputersByUser( $userid ) == null )
        {

            return false;
        }

        return true;
    }

    /**
     * Changes a computers IP address
     *
     * @param $computerid
     *
     * @param $address
     */

    public function changeIPAddress( $computerid, $address )
    {

        $array = array(
            'ipaddress' => $address
        );

        $this->database->updateComputer( $computerid, $array );
    }

    /**
     * Formats a computer to the default software
     *
     * @param $computerid
     */

    public function format( $computerid )
    {

        $array = array(
            'softwares' => json_encode( Settings::getSetting('syscrack_default_software') )
        );

        $this->database->updateComputer( $computerid, $array );
    }

    /**
     * Resets the hardware of a computer to the default hardware
     *
     * @param $computerid
     */

    public function resetHardware( $computerid )
    {

        $array = array(
            'hardware' => json_encode( Settings::getSetting('syscrack_default_hardware') )
        );

        $this->database->updateComputer( $computerid, $array );
    }

    /**
     * Gets the computer at the id
     *
     * @param $computerid
     *
     * @return mixed||\stdClass
     */

    public function getComputer( $computerid )
    {

        return $this->database->getComputer( $computerid );
    }

    /**
     * Gets the current list of software in the system
     *
     * @param $computerid
     *
     * @return array
     */

    public function getComputerSoftware( $computerid )
    {

        return json_decode( $this->database->getComputer( $computerid )->software, true );
    }

    /**
     * Adds a software to the computers software list
     *
     * @param $computerid
     *
     * @param array $array
     */

    public function addSoftware( $computerid, array $array )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->software, true );

        if( empty( $softwares ) )
        {

            throw new SyscrackException();
        }

        $softwares[] = $array;

        $this->database->updateComputer( $computerid, json_encode( $softwares ) );
    }

    /**
     * Returns true if the software is installed
     *
     * @param $computerid
     *
     * @param $softwareid
     *
     * @return bool
     */

    public function inInstalled( $computerid, $softwareid )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->software, true );

        if( empty( $softwares ) )
        {

            throw new SyscrackException();
        }

        foreach( $softwares as $key=>$software )
        {

            if( $software['softwareid'] == $softwareid )
            {

                return $software['installed'];
            }
        }

        return false;
    }

    /**
     * removes a software from the computers list
     *
     * @param $computerid
     *
     * @param $softwareid
     */

    public function removeSoftware( $computerid, $softwareid )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->software, true );

        if( empty( $softwares ) )
        {

            throw new SyscrackException();
        }

        foreach( $softwares as $key=>$software )
        {

            if( $software['softwareid'] == $softwareid )
            {

                unset( $softwares[ $key ] );
            }
        }

        $this->database->updateComputer( $computerid, json_encode( $softwares ) );
    }

    /**
     * Gets the computers hardware
     *
     * @param $computerid
     *
     * @return array
     */

    public function getComputerHardware( $computerid )
    {

        return json_decode( $this->database->getComputer( $computerid )->hardware, true );
    }

    /**
     * Returns the main ( first ) computer
     *
     * @param $userid
     *
     * @return mixed|\stdClass
     */

    public function getUserMainComputer( $userid )
    {

        return $this->database->getComputersByUser( $userid )[0];
    }

    /**
     * Gets all the users computers
     *
     * @param $userid
     *
     * @return \Illuminate\Support\Collection|null
     */

    public function getUserComputers( $userid )
    {

        return $this->database->getComputersByUser( $userid );
    }

    /**
     * Gets the computers type
     *
     * @param $computerid
     *
     * @return mixed
     */

    public function getComputerType( $computerid )
    {

        return $this->database->getComputer( $computerid )->type;
    }

    /**
     * Gets all the installed software on a computer
     *
     * @param $computerid
     *
     * @return array
     */

    public function getInstalledSoftware( $computerid )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->softwares, true );

        $result = array();

        foreach( $softwares as $key=>$value )
        {

            if( $value['installed'] == true )
            {

                $result[] = $value['softwareid'];
            }
        }

        return $result;
    }

    /**
     * Gets the install cracker on the machine
     *
     * @param $computerid
     *
     * @return null
     */

    public function getCracker( $computerid )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->softwares, true );

        foreach( $softwares as $software )
        {

            if( $software['type'] == Settings::getSetting('syscrack_cracker_type') )
            {

                if( $software['installed'] == false )
                {

                    continue;
                }

                return $software['softwareid'];
            }
        }

        return null;
    }

    /**
     * Gets the firewall
     *
     * @param $computerid
     *
     * @return null
     */

    public function getFirewall( $computerid )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->softwares, true );

        foreach( $softwares as $software )
        {

            if( $software['type'] == Settings::getSetting('syscrack_hasher_type') )
            {

                if( $software['installed'] == false )
                {

                    continue;
                }

                return $software['softwareid'];
            }
        }

        return null;
    }

    /**
     * Gets the hasher
     *
     * @param $computerid
     *
     * @return null
     */

    public function getHasher( $computerid )
    {

        $softwares = json_decode( $this->database->getComputer( $computerid )->softwares, true );

        foreach( $softwares as $software )
        {

            if( $software['type'] == Settings::getSetting('syscrack_hasher_type') )
            {

                if( $software['installed'] == false )
                {

                    continue;
                }

                return $software['softwareid'];
            }
        }

        return null;
    }

    /**
     * Returns true if the computer is a bank
     *
     * @param $computerid
     *
     * @return bool
     */

    public function isBank( $computerid )
    {

        if( $this->getComputerType( $computerid ) !== Settings::getSetting('syscrack_bank_type') )
        {

            return false;
        }

        return true;
    }

    /**
     * Returns true if the computer is an NPCs
     *
     * @param $computerid
     *
     * @return bool
     */

    public function isNPC( $computerid )
    {

        if( $this->getComputerType( $computerid ) !== Settings::getSetting('syscrack_npc_type') )
        {

            return false;
        }

        return true;
    }

    /**
     * Returns true if the computer is a VPC
     *
     * @param $computerid
     *
     * @return bool
     */

    public function isVPC( $computerid )
    {

        if( $this->getComputerType( $computerid ) !== Settings::getSetting('syscrack_vpc_type') )
        {

            return false;
        }

        return true;
    }
}