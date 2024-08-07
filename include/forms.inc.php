<?php

const POST = "POST";
const GET = "GET";

    class Form {

        var 
            $elements = array(),
            $method = POST,
            $title = "";

        function __construct($title, $entity = null) {

            $this->title = $title;

            return $this;
        }

        function add($element) {
            $this->elements[$element->name] = $element;

            return $this;
        }
        
        function emit() {

            $form = new Template("form/form");
            $form->setContent("method", $this->method);
            $form->setContent("title", $this->title);

            $result = "";
            foreach($this->elements as $key => $element) {
                $result .= $element->emit();
            }
            $form->setContent("body", $result);

            return $form->get();
        }
    }

    abstract class Element {
        var 
            $name, $label;

        function __construct($name, $label = null) {
            $this->name = $name;
            $this->label = $label;

            return $this;
        }

        function emit() {

            $element = new Template ("form/".strtolower(get_class($this)));

            foreach($this as $key => $field) {
                $element->setContent($key, $field);
            }

            return $element->get();
        }
    }

    class Text extends Element {

        function __construct($name, $label) {
            parent::__construct($name,$label);

            return $this;
        }

    }

    class Textarea extends Element {

        function __construct($name, $label) {
            parent::__construct($name,$label);

            return $this;
        }

    }

    class Editor extends Element {

        function __construct($name, $label) {
            parent::__construct($name, $label);

            return $this;
        }

    }

    class Submit extends Element {

        function __construct($label) {
            parent::__construct("id_".uniqid(time()),$label);

            return $this;
        }

    }

    class Reset extends Element {

        function __construct($label) {
            global $config;

            parent::__construct("id_".uniqid(time()),$label);

            return $this;
        }

    }
