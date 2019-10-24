<?php

    namespace jeyofdev\php\blog\Form\generateForm;


    use DateTimeInterface;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Exception\NotAllowedException;


    /**
     * Set the elements of the form
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractBuilderForm implements BuilderFormInterface
    {
        /**
         * The form elements
         *
         * @var array
         */
        protected $form;



        /**
         * The datas sent to the form
         *
         * @var Post|Category
         */
        private $datas;



        /**
          * The forms errors
          *
          * @var array
          */
        private $errors;



        /**
         * The allowed values ​​of the 'method' attribute in the form tag
         */
        const METHOD_ALLOWED = ["get", "post"];



        /**
         * The type of button allowed
         */
        const BUTTON_TYPE_ALLOWED = ["submit", "reset"];



        /**
         * The type of input allowed
         */
        const INPUT_TYPE_ALLOWED = ["text", "password", "checkbox", "radio", "file", "hidden"];



        /**
         * The authorized Boolean attributes
         */
        const ATTRIBUTE_BOOL_ALLOWED = ["autofocus", "disabled", "readonly", "required", "multiple"];



        public function __construct ($datas, array $errors)
        {
            $this->datas = $datas;
            $this->errors = $errors;
        }



        /**
         * {@inheritDoc}
         */
        public function formStart (string $action = "#", string $method = "post", ?string $class = null)
        {
            $method = strtolower($method);
            $this->checkValueIsInArray($method, self::METHOD_ALLOWED, "method");

            $class = !is_null($class) ? ' class="' . $class . '"' : null;

            $start = '<form action="' . $action . '" method="' . $method . '"' . $class . '>';
            $this->form["start"] = $start;

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function formEnd ()
        {
            $this->form["end"] = "</form>";

            return $this;
        }



        /**
         * @return self
         */
        public function input (string $type, string $name, string $label, array $options, array $surround = [], ?string $errorClass = null)
        {
            $type = strtolower($type);
            $this->checkValueIsInArray($type, self::INPUT_TYPE_ALLOWED, "type");

            $value = !is_null($this->getValue($name)) ? 'value="' . $this->getValue($name) . '"' : null;
            $class = array_key_exists("class", $options) ? $this->getClass($name, $options["class"]) : $this->getClass($name);

            $options = $this->getOptions($options);

            $input = '<label for="' . $name . '">' . $label . '</label>';
            $input .= '<input type="' . $type . '" class="' . $class . '" id="' . $name . '" name="' . $name . '" ' . $value . $options . '>';
            $input .= $this->getErrorFeddback($name, $errorClass);

            $input = $this->generateFormElement($input, $surround);

            $this->form["fields"][$name] = $input;

            return $this;
        }



        /**
         * @return self
         */
        public function textarea (string $name, string $label, array $options, array $surround = [], ?string $errorClass = null)
        {
            $value = !is_null($this->getValue($name)) ? $this->getValue($name) : null;
            $class = array_key_exists("class", $options) ? $this->getClass($name, $options["class"]) : $this->getClass($name);

            $options = $this->getOptions($options);

            $textarea = '<label for="' . $name . '">' . $label . '</label>';
            $textarea .= '<textarea class="' . $class . '" id="' . $name . '" name="' . $name . '" ' . $options . '>' . $value . '</textarea>';
            $textarea .= $this->getErrorFeddback($name, $errorClass);

            $textarea = $this->generateFormElement($textarea, $surround);

            $this->form["fields"][$name] = $textarea;

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function select (string $name, string $label, array $options = [], array $optionsSelect, array $surround = [])
        {
            $value = !is_null($this->getValue($name)) ? $this->getValue($name) : null;
            $class = array_key_exists("class", $options) ? $this->getClass($name, $options["class"]) : $this->getClass($name);

            $options = $this->getOptions($options);
            $optionSelect = $this->generateSelectOptions($optionsSelect, $value);

            $select = '<label for="' . $name . '">' . $label . '</label>';
            $select .= '<select class="' . $class . '" id="' . $name . '" name="' . $name . '" ' . $options . '>' . $optionSelect . '</select>';
            $select .= $this->getErrorFeddback($name);

            $select = $this->generateFormElement($select, $surround);

            $this->form["fields"][$name] = $select;

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function submit (string $label, ?string $class = null)
        {
            return $this->button("submit", $label, $class);
        }



        /**
         * {@inheritDoc}
         */
        public function reset (string $label, ?string $class = null)
        {
            return $this->button("reset", $label, $class);
        }



        /**
         * Set the buttons
         *
         * @return self
         */
        private function button (string $type = "submit", string $label, ?string $class = null) : self
        {
            $type = strtolower($type);
            $this->checkValueIsInArray($type, self::BUTTON_TYPE_ALLOWED, "type");

            $class = !is_null($class) ? ' class="' . $class . '"' : null;

            $button = '<button type="' . $type . '"' . $class . '>' . $label . '</button>';
            $this->form["buttons"][$type] = $button;

            return $this;
        }



        /**
         * Generate the form elements with an optional surround
         *
         * @return string
         */
        private function generateFormElement (string $field, array $surround = []) : string
        {
            if (!empty($surround)) {
                $surroundTag = $surround['tag'];

                $item = '<' . $surroundTag . ' class="' . $surround["class"] . '">';
                $item .= $field;
                $item .= '</' . $surroundTag . '>';
            } else {
                $item = $field;
            }

            return $item;
        }



        /**
         * Get the class of a form element
         *
         * @return string|null
         */
        private function getClass (string $key, ?string $class = null) : ?string
        {
            $inputClass = $class;
            if (isset($this->errors[$key])) {
                $inputClass .= !is_null($inputClass) ? " " : null;
                $inputClass .= 'is-invalid';
            }

            return $inputClass;
        }



        /**
         * Get the value of a field
         *
         * @return mixed
         */
        private function getValue (string $key)
        {
            if (is_array($this->datas)) {
                $value = $this->datas[$key] ?? null;
            } else {
                $method = "get" . ucfirst($key);
                $value = $this->datas->$method() ?? null;
            }

            if ($value instanceof DateTimeInterface) {
                return $value->format("Y-m-d H:i:s");
            }

            return ($value !== "") ? $value : null;
        }



        /**
         * Get the optional attributes
         *
         * @return string|null
         */
        private function getOptions (array $options) : ?string
        {
            if (!empty($options)) {
                $items = [];

                foreach ($options as $k => $v) {
                    if (!in_array($k, self::ATTRIBUTE_BOOL_ALLOWED)) {
                        $items[] = "$k = $v";
                    } else {
                        $items[] = $k;
                    }
                    
                }
            }

            return isset($items) ? implode(" ", $items) : null;
        }



        /**
         * Set the options tags of a select field
         * 
         * @param array $options The options of a select field
         * @param array|null $value The values of the selected fields
         * @return string
         */
        private function generateSelectOptions (array $options, ?array $value) : string
        {
            $optionsSelect = [];

            foreach ($options as $k => $v) {
                if (!is_null($value)) {
                    $selected = in_array($k, $value) ? " selected" : null;
                } else {
                    $selected = null;
                }
                $optionsSelect[] = '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
            }

            return implode('', $optionsSelect);
        }



        /**
         * Display the form errors
         *
         * @return string
         */
        protected function getErrorFeddback (string $key, ?string $errorClass = null) : ?string
        {
            $class = !is_null($errorClass) ? ' class="' . $errorClass . '"' : null;

            $invalidFeedback = null;
            
            if (isset($this->errors[$key])) {
                $invalidFeedback .= '<div ' . $class . '>' . implode("<br>", $this->errors[$key]) . '</div>';
            }

            return $invalidFeedback;
        }



        /**
         * Check that the value of an attribute is allowed
         *
         * @return void
         */
        private function checkValueIsInArray (string $value, array $array, string $attributeName) : void
        {
            if (!in_array($value, $array)) {
                throw new NotAllowedException($value, $attributeName);
            }
        }
    }