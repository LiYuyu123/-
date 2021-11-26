<?php

    /**
     * Autoload Aws related classes.
     */
    spl_autoload_register(function ($className) {
        $className = basename($className);

        if ( substr($className,0,4) == "MAws" || substr($className,0,4) == "IAws" )
        {

            $classFile = substr($className,1) . ".php";
            
            if ( $className == "IAws" )
                $classFile = "AwsInterface.php";

            require __DIR__.'/' . $classFile ;
        }
    });

?>