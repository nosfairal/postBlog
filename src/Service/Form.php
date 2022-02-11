<?php

namespace Nosfair\Blogpost\Service;

class Form
{
    private $formCode = '';

    

    /**
     * Create a form
     * @return string
     */ 
    public function create()
    {
        return $this->formCode;
    }

    /**
     * Verify the form's compliance
     * @param array $form
     * @param array $fields
     * @return bool
     */
    public static function validate(array $form, array $fields)
    {
        //loop into the fields
        foreach($fields as $field){
            //if field doesn't exist into the form or empty
            if(!isset($form[$field]) || empty($form[$field])){
                return false;
            }
        }
        return true;

    }

    /**
     * @param array $attributes
     * @return string str
     */
    private function addAttributes(array $attributes): string
    {
        //Initialyse a string
        $str ='';
        //Listing of shorts attributes
        $shorts =['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];
        //Loop into thye array of attributes
        foreach($attributes as $attribute => $value){
            if(in_array($attribute, $shorts) && $value == true){
                $str .= " $attribute";
            }else{
                $str .= " $attribute=\"$value\"";
            }
        }
        return $str;
    }

    /**
     * openings tags of the form
     * @param string $method
     * @param string $action
     * @param array $attributes
     * @return Form
     */
    public function startForm(string $method = 'post' , string $action ='#', array $attributes = []): self
    {
        // Create the tags form
        $this->formCode .= "<form action='$action' method='$method' ";
        //If there are attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes).'>' : '>';
        return $this;
    }

    /**
     * endings tags form + token
     * @return Form
     */
    public function endForm(): self
    {
        $token = md5(uniqid());
        $this->formCode .= "<input type='hidden' name='token' value ='$token'>";
        $this->formCode .= ' </form>';
        Session::put("token", $token);
        return $this;
    }

    /**
     * Adding a label
     * @param string $for 
     * @param string $text 
     * @param array $attributes 
     * @return Form 
     */
    public function addLabelFor(string $for, string $text, array $attributes = []):self
    {
        // Openning tag
        $this->formCode .= "<div class='form-group'><label for='$for'";

        //Adding attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        // adding  text
        $this->formCode .= ">$text</label>";

        return $this;
    }

    /**
     * Adding input field
     * @param string $type 
     * @param string $name
     * @param array $attributes 
     * @return Form
     */
    public function addInput(string $type, string $name, array $attributes = []):self
    {
        // Openning tags
        $this->formCode .= "<input type='$type' name='$name'";

        // Adding  attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes).'>' : '>';
        $this->formCode .= "</div>";

        return $this;
    }
    /**
     * Adding input field
     * @param string $type 
     * @param string $name
     * @param array $attributes 
     * @return Form
     */
    public function addLink(string $href, string $name, array $attributes = []):self
    {
        // Openning tags
        $this->formCode .= "<div><a href='$href' name='$name'";

        // Adding  attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes).'>' : '</a>';
        $this->formCode .= "</div>";

        return $this;
    }

    /**
     * Adding a textarea field
     * @param string $name 
     * @param string $value
     * @param array $attributes
     * @return Form 
     */
    public function addTextarea(string $name, string $value = '', array $attributes = []):self
    {
        // Openning tags
        $this->formCode .= "<textarea name='$name'";

        // adding  attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        // Adding text
        $this->formCode .= ">$value</textarea>";

        return $this;
    }

    /**
     * adding a select
     * @param string $name
     * @param array $options 
     * @param array $attributes 
     * @return Form
     */
    public function addSelect(string $name, array $options, array $attributes = []):self
    {
        // Create the select
        $this->formCode .= "<select name='$name' ";

        // Adding attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes).'>' : '>';

        // Adding options
        foreach($options as $value => $text){
            $this->formCode .= "<option value=\"$value\">$text</option>";
        }

        // Closing the select
        $this->formCode .= '</select>';

        return $this;
    }

    /**
     * Adding a button
     * @param string $text 
     * @param array $attributs 
     * @return Form
     */
    public function addButton(string $text, array $attributes = []):self
    {
        // Openning tags
        $this->formCode .= '</br><button ';

        // Adding attributes
        $this->formCode .= $attributes ? $this->addAttributes($attributes) : '';

        // Adding text and closing tag
        $this->formCode .= ">$text</button>";

        return $this;
    }
}