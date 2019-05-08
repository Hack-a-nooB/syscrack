<?php
    namespace Framework\Syscrack;

    /**
     * Lewis Lancaster 2017
     *
     * Class BetaKey
     *
     * @package Framework\Syscrack
     */

    use Framework\Application\Settings;
    use Framework\Application\Utilities\FileSystem;

    class BetaKeys
    {

        /**
         * @var null
         */

        private $keys = [];

        /**
         * BetaKey constructor.
         */

        public function __construct()
        {

            $this->keys = $this->getBetakeys();
        }

        /**
         * Adds the beta-key to the array and then saves
         *
         * @param $betakey
         */

        public function addBetaKey( $betakey=null )
        {


            if( is_array( $betakey ) )
            {

                foreach( $betakey as $key=>$value )
                {

                    $this->keys[] = $value;
                }

                $this->saveBetaKeys();
            }
            else
            {

                $this->keys[] = $betakey;

                $this->saveBetaKeys();
            }
        }

        /**
         * Removes a betakey from the list
         *
         * @param $betakey
         */

        public function removeBetaKey( $betakey )
        {

            foreach( $this->keys as $key=>$value )
            {

                if( $value == $betakey )
                {

                    unset( $this->keys[ $key ] );
                }
            }

            $this->saveBetaKeys();
        }

        /**
         * Returns true if this beta-key exists
         *
         * @param $betakey
         *
         * @return bool
         */

        public function hasBetaKey( $betakey )
        {

            if( empty( $this->keys ) )
            {

                return false;
            }

            foreach( $this->keys as $key=>$value )
            {

                if( $value == $betakey )
                {

                    return true;
                }
            }

            return false;
        }

        /**
         * Saves the betakeys
         */

        public function saveBetaKeys()
        {

            FileSystem::writeJson( Settings::setting('syscrack_betakey_location'), $this->keys );
        }

        /**
         * Gets the beta keys
         *
         * @return null
         */

        public function getBetakeys()
        {

            if( FileSystem::fileExists( Settings::setting('syscrack_betakey_location') ) == false )
            {

                return null;
            }

            return FileSystem::readJson( Settings::setting('syscrack_betakey_location') );
        }

        /**
         * Generates a set of Betakeys
         *
         * @param int $count
         *
         * @return array
         */

        public function generateBetaKeys( $count=1 )
        {

            $keys = [];

            for( $i = 0; $i < $count; $i++ )
            {

                $keys[] = $this->createBetakey();
            }

            return $keys;
        }

        /**
         * Generates a new betakey
         *
         * @return string
         */

        private function createBetaKey()
        {

            $key = "";

            for($i = 0; $i < Settings::setting('syscrack_betakey_steps'); $i++ )
            {

                $step = "";

                for($k = 0; $k < Settings::setting('syscrack_betakey_length'); $k++ )
                {

                    $step = $step . rand(0,9 );
                }

                $key = $step . "-" . $key;
            }

            return rtrim( $key, '-');
        }
    }