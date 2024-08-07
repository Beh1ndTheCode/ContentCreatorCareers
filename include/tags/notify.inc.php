<?php

    class notify extends TagLibrary {

        function dummy(){}

        function get_message($name, $data, $pars) {

            $msg = new Template("message");

            switch ($data) {
                case "success":
                    $msg->setContent("message", "The transaction has been executed!");
                    $msg->setContent("fgcolor", "green");
                    $msg->setContent("bgcolor", "white");

                    break;
                case "error":
                    $msg->setContent("message", "Warning: the transaction had problems!");
                    $msg->setContent("fgcolor", "red");
                    $msg->setContent("bgcolor", "white");
                    break;

            }

            return $msg->get();
            
        }

    }
