<?php

    $config['auth'] = "true";


    if (!isset($_SESSION['user'])) {

        $passwd = md5("{$_POST['password']}|".md5($_POST['username']));
        
        $result = $mysqli->query("SELECT username, name, surname, email FROM `user` WHERE username = '{$_POST['username']}' AND password = '$passwd'");

        if (!$result) {
            die("Error!");
        } else {

            if ($result->num_rows == 1) {
                $user = $result->fetch_array();
                $_SESSION['user'] = $user;

                $result = $mysqli->query("
                    SELECT service.script 
                    FROM user_role
                    LEFT JOIN role_service
                    ON role_service.role_id = user_role.role_id
                    LEFT JOIN service
                    ON service.id = role_service.service_id
                    WHERE user_role.username = '{$_POST['username']}'
                ");

                if (!$result) {
                    die("Error!");
                } else {
                    while ($data = $result->fetch_array()) {
                        $service[$data['script']] = true;
                    }

                    $_SESSION['user']['service'] = $service;
                
                }

            } else {

                // utente non esiste

                Header("Location: error.php?error=001");
                exit;
            }


        }
    } 

    /* user exists and passwd is correct */


    $script = basename($_SERVER['SCRIPT_NAME']);
    if (!isset($_SESSION['user']['service'][$script])) {

        // user does not have permission for this script
        
        Header("Location: error.php?error=002");
        exit;

    }

    $result = $mysqli->query("SELECT * FROM service WHERE script = '$script'");

    if (!$result) {
        die("Error 200");
    }

    $data = $result->fetch_assoc();

    if ($data['permission'] == '*') {
                
        $result = $mysqli->query("SELECT * FROM {$data['entity']} WHERE {$data['field']} = {$_REQUEST['key']}");

        if (!result) {
            die("Error 100");
        } 

        if ($result->num_rows == 1) {
            $data = $result->fetch_assoc();

            if ($data['username'] != $_SESSION['user']['username']) {

                Header("Location: error.php?error=1000");
                exit;
            }

        } else {
            Header("Location: error.php?error=1100");
            exit;
        }    

    } 
