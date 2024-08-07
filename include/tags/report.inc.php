<?php

    class report extends TagLibrary {

        function dummy() {}

        function get_report($name, $data, $pars) {
            global 
                $mysqli,
                $main;

            $report = new Template("report-raw");


            $result = $mysqli->query("SELECT {$data['key']}, {$data['fields']} FROM {$data['table']}");

            if (!$result) {
                $main->setMessage("notify", "error");
            }

            
            $fieldnames = explode(", ", $data['fields']);
            foreach($fieldnames as $name) {
                $report->setContent("fieldname", $name);
            }

            while ($row = $result->fetch_assoc()){
                foreach($row as $key => $value) {
                    if ($key == $data['key']) {
                        $report->setContent('key', $value);
                    } else {
                        $report->setContent('field', $value);
                    }
                } 
            }
            

            return $report->get();
        }



    }
