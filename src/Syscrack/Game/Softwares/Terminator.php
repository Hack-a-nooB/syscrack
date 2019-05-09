<?php
namespace Framework\Syscrack\Game\Softwares;

/**
 * Lewis Lancaster 2017
 *
 * Class Terminator
 *
 * @package Framework\Syscrack\Game\Softwares
 */

use Framework\Syscrack\Game\BaseClasses\BaseSoftware;
use Framework\Syscrack\Game\Tool;

class Terminator extends BaseSoftware
{

    /**
     * The configuration of this Structure
     *
     * @return array
     */

    public function configuration()
    {

        return array(
            'uniquename'        => 'terminator',
            'extension'         => '.lgout',
            'type'              => 'terminator',
            'installable'       => true,
            'executable'        => true,
            'localexecuteonly'  => true,
        );
    }

    /**
     * @param null $userid
     * @param null $sofwareid
     * @param null $computerid
     * @return Tool
     */

    public function tool($userid = null, $sofwareid = null, $computerid = null): Tool
    {

        $tool = new Tool("Disconnect", "danger");
        $tool->setAction('logout');
        $tool->isConnected();
        $tool->icon = "remove-circle";


        return( $tool );
    }
}