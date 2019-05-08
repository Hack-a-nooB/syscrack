<?php
    namespace Framework\Syscrack\Game\Softwares;

/**
 * Lewis Lancaster 2017
 *
 * Class VWarez
 *
 * @package Framework\Syscrack\Game\Softwares
 */

use Framework\Syscrack\Game\BaseClasses\BaseSoftware;


class VWarez extends BaseSoftware
{

    /**
     * The configuration of this Structure
     *
     * @return array
     */

    public function configuration()
    {

        return array(
            'uniquename'    => 'vwarez',
            'extension'     => '.vwarez',
            'type'          => 'virus',
            'installable'   => true,
            'uninstallable' => true,
            'executable'    => false,
            'removable'     => false
        );
    }

    public function onExecuted( $softwareid, $userid, $computerid )
    {


    }

    public function onInstalled( $softwareid, $userid, $computerid )
    {


    }

    public function onUninstalled($softwareid, $userid, $computerid)
    {
        // TODO: Implement onUninstalled() method.
    }

    public function onCollect( $softwareid, $userid, $computerid, $timeran )
    {


    }

    public function getExecuteCompletionTime($softwareid, $computerid)
    {

        return null;
    }

    /**
     * Default size of 10.0
     *
     * @return float
     */

    public function getDefaultSize()
    {

        return 14.0;
    }

    /**
     * Default level of 1.0
     *
     * @return float
     */

    public function getDefaultLevel()
    {

        return 1.2;
    }
}