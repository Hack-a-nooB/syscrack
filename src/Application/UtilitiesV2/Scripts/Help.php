<?php
/**
 * Created by PhpStorm.
 * User: lewis
 * Date: 05/08/2018
 * Time: 14:23
 */

namespace Framework\Application\UtilitiesV2\Scripts;


use Framework\Application\UtilitiesV2\Container;
use Framework\Application\UtilitiesV2\Debug;
use Framework\Application\UtilitiesV2\Scripts;

class Help extends Base
{

    /**
     * @var array
     */

    protected $strings;

    /**
     * Help constructor.
     */

    public function __construct()
    {

        $this->strings = [
            "How to use the frameworks command line interface" => 0,
            " The syntax to execute a command is as follows" => 0,
            "php -f cmd/execute.php [script] [arguments]\n" => 0,
            " Scripts take arguments in the form of an associative array" => 0,
            "php -f cmd/execute.php [script] arg1=value arg2=value arg3=value (blank/null values can be set using key= followed by no value )\n" => 0,
            " You can view all the available scripts, by executing the index script, The pretty argument is optional." => 0,
            "php -f cmd/execute.php commands pretty=true\n" => 0,
            " You can also view specific help on a script by using a special case" => 0,
            "php -f cmd/execute.php help autotest" => 0,
            " The help script takes the second argument as a script, as well as the conventional way, with arguments.\n" => 0,
            " You can create an instance if you plan on using CLI under an environment, this is done by invoking the following" => 0,
            "php -f cmd/execute.php instance" => 0,
            " Then, you can simply type out your various commands inside the terminal instance." => 0,
            " \nExamples of some commands" => 0,
            "  help" => 0,
            "  help autotest                    : Special case. This is the same as script=autotest" => 0,
            "  help deploy" => 0,
            "  commands" => 0,
            "  commands pretty                  : Special case. This is the same as pretty=true" => 0,
            "  sysinfo" => 0,
            "  sysinfo detailed                 : Special case. This is the same as detailed=true" => 0,
            "  debug 1                          : Special case. Takes verbrosity. 1 = Messages, 2 = Messages + Timers (default/noarg is 1)" => 0,
            "  resources action=pack|unpack" => 0,
            "  refresh                          : Refreshes the instance. If you have added new script files and still have the instance active they will be added and executable. ! DOES NOT REFRESH CODE CHANGES, MUST QUIT OUT !" => 0,
            "  autotest" => 0,
            "  resources" => 0,
            " The syntax for arguments is eactly the same when in instance mode, minus php -f cmd/execute.php\n" => 0,
        ];
    }

    /**
     * @param $arguments
     * @return bool
     * @throws \RuntimeException
     */

    public function execute($arguments)
    {

        //Cute little hack to allow easier use with help
        $keys = array_keys( $arguments );

        if( empty( $keys ) == false && $keys[0] !== "help" )
            $arguments["script"] = $keys[0];

        if( isset( $arguments["script"] ) && $arguments["script"] != null )
        {

            if( Container::exist("scripts") == false )
                throw new \RuntimeException("Scripts does not exist");

            /** @var Scripts $scripts */

            $scripts = Container::get("scripts");

            if( $scripts->exists( $arguments["script"] ) == false )
                throw new \RuntimeException("Script does not exist: " . $arguments["script"] );

            $help = $scripts->help( $arguments["script"] );

            if( Debug::isCMD() )
                Debug::echo("\nDisplaying help for " . $arguments["script"] );

            if( is_array( $help ) )
            {

                if( isset( $help["arguments"] ) )
                {

                    if( Debug::isCMD() )
                        Debug::echo("The arguments for this script is as follows: ");

                    foreach( $help["arguments"] as $key=>$argument )
                        Debug::echo(" " . "[" . $key . "] " . $argument);
                }

                if( isset( $help["help"] ) )
                {

                    if( Debug::isCMD() )
                        Debug::echo("\nDescription: ");

                    if( Debug::isCMD() )
                    {

                        if( is_array( $help["help"] ) )
                            foreach( $help["help"] as $string )
                                Debug::echo($string );
                        else
                            Debug::echo(" " . $help["help"]);
                    }


                    if( Debug::isCMD() )
                        Debug::echo("");
                }
            }
            else
                throw new \RuntimeException("Invalid help return type is not array");

        }
        else
        {

            if ( Debug::isCMD() )
            {

                foreach( $this->strings as $message=>$tab )
                    Debug::echo( $message, $tab );
            }
        }

        return parent::execute($arguments); // TODO: Change the autogenerated stub
    }

    /**
     * @return array|null|bool
     */

    public function requiredArguments()
    {

        return( false );
    }

    /**
     * @return array
     */

    public function help()
    {

        return([
            "arguments" => $this->requiredArguments(),
            "help" => "Lists help regarding the various scripts available"
        ]);
    }
}