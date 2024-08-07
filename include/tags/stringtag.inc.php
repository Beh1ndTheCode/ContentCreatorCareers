<?php

    class stringTag extends TagLibrary {

        function dummy(){}


        function format($name, $value, $pars) {
            switch ($pars['mode']) {
                case 'upper':
                    $result = strtoupper($value);
                    break;
                case 'lower':
                    $result = strtolower($value);
                    break;
            }

            if (isset($pars['color'])) {
                $result = "<span style='color: {$pars['color']};'>{$result}</span>";
            }
            return $result;

        }

    }

?>