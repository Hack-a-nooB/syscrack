<?php
    namespace Framework\Syscrack\Game\Softwares;

    /**
     * Lewis Lancaster 2017
     *
     * Class Hasher
     *
     * @package Framework\Syscrack\Game\Softwares
     */

    use Framework\Syscrack\Game\BaseClasses\BaseSoftware;


    class Hasher extends BaseSoftware
    {

        /**
         * The configuration of this Structure
         *
         * @return array
         */

        public function configuration()
        {

            return array(
                'uniquename'    => 'hasher',
                'extension'     => '.hash',
                'type'          => 'hasher',
                'icon'          => 'glyphicon-lock',
                'installable'   => true,
                'executable'    => false,
            );
        }

        public function onExecuted( $softwareid, $userid, $computerid )
        {

            return null;
        }

        public function onInstalled( $softwareid, $userid, $computerid )
        {

            return null;
        }

        public function onUninstalled($softwareid, $userid, $computerid)
        {

            return null;
        }

        public function onCollect( $softwareid, $userid, $computerid, $timeran )
        {

            return null;
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

            return 10.0;
        }

        /**
         * Default level of 1.0
         *
         * @return float
         */

        public function getDefaultLevel()
        {

            return 1.0;
        }
    }