<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Adam Daniel <adaniel1@eesus.jnj.com>                        |
// |          Bertrand Mansion <bmansion@mamasam.com>                     |
// +----------------------------------------------------------------------+
//
// $Id$

require_once('HTML/QuickForm/Elements/element.php');

/**
 * Class to dynamically create an HTML SELECT
 *
 * @author       Adam Daniel <adaniel1@eesus.jnj.com>
 * @author       Bertrand Mansion <bmansion@mamasam.com>
 * @version      1.0
 * @since        PHP4.04pl1
 * @access       public
 */
class HTML_QuickForm_select extends HTML_QuickForm_element {
    
    /**
     * Contains the select options
     *
     * @var       array
     * @since     1.0
     * @access    private
     */
    var $_options = array();
    
    /**
     * Default values of the SELECT
     * 
     * @var       string
     * @since     1.0
     * @access    private
     */
    var $_values = array();
        
    /**
     * Class constructor
     * 
     * @param     string    $elementName    (optional)Input field name attribute
     * @param     string    $options        (optional)Input field value
     * @param     mixed     $attributes     (optional)Either a typical HTML attribute string 
     *                                      or an associative array
     * @since     1.0
     * @access    public
     * @return    void
     * @throws    
     */
    function HTML_QuickForm_select ($elementName=null, $options=null, $attributes=null)
    {
        HTML_QuickForm_element::HTML_QuickForm_element('select', $elementName, null, $attributes);
        if ($this->getAttribute('multiple') AND strpos($elementName,']')<2)
            $elementName = $elementName.'[]';
        if (isset($options)) {
            $this->load($options);
        }
    } //end constructor
    
    /**
     * Returns the current API version 
     * 
     * @since     1.0
     * @access    public
     * @return    double
     * @throws    
     */
    function apiVersion()
    {
        return 1.0;
    } //end func apiVersion

    /**
     * Sets the default values of the select box
     * 
     * @param     mixed    $values  Array or comma delimited string of selected values
     * @since     1.0
     * @access    public
     * @return    void
     * @throws    
     */
    function setSelected($values)
    {
        if (is_string($values)) {
            $values = split("[ ]?,[ ]?", $values);
        }
        $this->_values = $values;  
    } //end func setSelected
    
    /**
     * Returns an array of the selected values
     * 
     * @since     1.0
     * @access    public
     * @return    array of selected values
     * @throws    
     */
    function getSelected()
    {
        return $this->_values;
    } // end func getSelected

    /**
     * Sets the input field name
     * 
     * @param     string    $name   Input field name attribute
     * @since     1.0
     * @access    public
     * @return    void
     * @throws    
     */
    function setName($name)
    {
        $this->updateAttributes(array('name'=>$name));
    } //end func setName
    
    /**
     * Returns the element name
     * 
     * @since     1.0
     * @access    public
     * @return    string
     * @throws    
     */
    function getName()
    {
        return $this->getAttribute('name');
    } //end func getName

    /**
     * Sets the value of the form element
     *
     * @param     mixed    $values  Array or comma delimited string of selected values
     * @since     1.0
     * @access    public
     * @return    void
     * @throws    
     */
    function setValue($value)
    {
        $this->setSelected($value);
    } // end func setValue

    /**
     * Returns an array of the selected values
     * 
     * @since     1.0
     * @access    public
     * @return    array of selected values
     * @throws    
     */
    function getValue()
    {
        return $this->_values;
    } // end func getValue

    /**
     * Sets the select field size, only applies
     * 
     * @param     int    $size  Size of select  field
     * @since     1.0
     * @access    public
     * @return    void
     * @throws    
     */
    function setSize($size)
    {
        $this->updateAttributes(array('size'=>$size));
    } //end func setSize
    
    /**
     * Returns the select field size
     * 
     * @since     1.0
     * @access    public
     * @return    string
     * @throws    
     */
    function getSize()
    {
        return $this->getAttribute('size');
    } //end func getSize

    /**
     * Adds a new OPTION to the SELECT
     *
     * @param     string    $text       Display text for the OPTION
     * @param     string    $value      Value for the OPTION
     * @param     mixed     $attributes Either a typical HTML attribute string 
     *                                  or an associative array
     * @since     1.0
     * @access    public
     * @return    void
     * @throws    
     */
    function addOption($text, $value, $attributes=null)
    {
        $attributes = $this->_parseAttributes($attributes);
        if ($this->getAttribute('selected') && !in_array($value, $this->_values)) {
            $this->_values[] = $value;
            array_unique($this->_values);
        }
        $attr = array('value'=>$value);
        $this->_updateAttrArray($attributes, $attr);
        $this->_options[] = array('text'=>$text, 'attr'=>$attributes);
    } // end func addOption
    
    /**
     * Loads the options from an associative array
     * 
     * @param     array    $arr     Associative array of options
     * @param     mixed    $values  (optional) Array or comma delimited string of selected values
     * @since     1.0
     * @access    public
     * @return    PEAR_Error on error or true
     * @throws    PEAR_Error
     */
    function loadArray($arr, $values=null)
    {
        if (!is_array($arr)) {
            return new PEAR_ERROR("First argument to HTML_Select::loadArray is not a valid array");
        }
        if (isset($values)) {
            $this->setSelected($values);
        }
        while (list($key, $val) = each($arr)) {
            if (is_int($key)) {
                $key = $val;
            }
            $this->addOption($key, $val);
        }
        return true;
    } // end func loadArray

    /**
     * Loads the options from DB_result object
     * 
     * If no column names are specified the first two columns of the result are
     * used as the text and value columns respectively
     * @param     object    $result     DB_result object 
     * @param     string    $textCol    (optional) Name of column to display as the OPTION text 
     * @param     string    $valueCol   (optional) Name of column to use as the OPTION value 
     * @param     mixed     $values     (optional) Array or comma delimited string of selected values
     * @since     1.0
     * @access    public
     * @return    PEAR_Error on error or true
     * @throws    PEAR_Error
     */
    function loadDbResult(&$result, $textCol=null, $valueCol=null, $values=null)
    {
        if (!is_object($result) || (get_class($result) != "db_result" && 
            is_subclass_of($result, "db_result"))) {
            return new PEAR_ERROR("First argument to HTML_Select::loadDbResult is not a valid DB_result");
        }
         if (isset($values)) {
            $this->setValue($values);
        }
        $fetchMode = ($textCol && $valueCol) ? DB_FETCHMODE_ASSOC : DB_FETCHMODE_DEFAULT;
        while ($row = $result->fetchRow($fetchMode)) {
            if (PEAR::isError($row)) {
                return $row;
            } elseif (is_array($row)) {
                if ($fetchMode == DB_FETCHMODE_ASSOC) {
                    $this->addOption($row[$textCol], $row[$valueCol]);
                } else {
                    $this->addOption($row[0], $row[1]);
                }
            } else {
                return new PEAR_ERROR('Invalid return value from DB_result->fetchRow');
            }
        }
        return true;
    } // end func loadDbResult
    
    /**
     * Queries a database and loads the options from the results
     *
     * @param     mixed     $conn       Either an existing DB connection or a valid dsn 
     * @param     string    $sql        SQL query string
     * @param     string    $textCol    (optional) Name of column to display as the OPTION text 
     * @param     string    $valueCol   (optional) Name of column to use as the OPTION value 
     * @param     mixed     $values     (optional) Array or comma delimited string of selected values
     * @since     1.1
     * @access    private
     * @return    void
     * @throws    
     */
    function loadQuery(&$conn, $sql, $textCol=null, $valueCol=null, $values=null)
    {
        if (is_string($conn)) {
            $dbConn = &DB::connect($conn, true);
            if (DB::isError($dbConn)) return $dbConn;
        } elseif (is_subclass_of($conn, 'db_common')) {
            $dbConn = $conn;
        } else {
            return new PEAR_Error('Argument 1 of HTML_Select::loadQuery is not a valid type');
        }
        $result = $dbConn->query($sql);
        if (DB::isError($result)) return $result;
        return $this->loadDbResult($result, $textCol, $valueCol, $values);
    } // end func loadQuery

    /**
     * Loads options from different types of data sources
     *
     * This method is a simulated overloaded method.  The arguments, other than the
     * first are optional and only mean something depending on the type of the first argument.
     * If the first argument is an array then all arguments are passed in order to loadArray.
     * If the first argument is a db_result then all arguments are passed in order to loadDbResult.
     * If the first argument is a string or a DB connection then all arguments are 
     * passed in order to loadQuery.
     * @param     mixed     $options     Options source currently supports assoc array or DB_result
     * @param     mixed     $param1     (optional) See function detail
     * @param     mixed     $param2     (optional) See function detail
     * @param     mixed     $param3     (optional) See function detail
     * @param     mixed     $param4     (optional) See function detail
     * @since     1.1
     * @access    public
     * @return    PEAR_Error on error or true
     * @throws    PEAR_Error
     */
    function load(&$options, $param1=null, $param2=null, $param3=null, $param4=null)
    {
        switch (true) {
            case is_array($options):
                return $this->loadArray($options, $param1);
                break;
            case (get_class($options) == 'db_result' || is_subclass_of($options, 'db_result')):
                return $this->loadDbResult($options, $param1, $param2, $param3);
                break;
            case (is_string($options) || is_subclass_of($options, 'db_common')):
                return $this->loadQuery($options, $param1, $param2, $param3, $param4);
                break;
        }
    } // end func load
    
    /**
     * Returns the SELECT in HTML
     *
     * @since     1.0
     * @access    public
     * @return    string
     * @throws    
     */
    function toHtml()
    {
        if ($this->_flagFrozen) {
            $strHtml = $this->getFrozenHtml();
        } else {
            $tabs = $this->_getTabs();
            $name = isset($this->_attributes['name']) ? $this->_attributes['name'] : '' ;
            $strHtml = '';
            if ($this->_comment) {
                $strHtml .= $tabs . "<!-- $this->_comment -->\n";
            }
            $strHtml .= 
                $tabs . "<select" . $this->_getAttrString($this->_attributes) . ">\n";
            foreach ($this->_options as $option) {
                if (in_array($option['attr']['value'], $this->_values)) {
                 $option['attr']['selected'] = 'selected';
                }
                $attrString = $this->_getAttrString($option['attr']);
                $strHtml .=
                    '<option' . $attrString . '>' .
                    htmlspecialchars($option['text']) . '</option>';
            }
            $strHtml .= $tabs . '</select>';
        }
        return $strHtml;
    } //end func toHtml
    
    /**
     * Returns the value of field without HTML tags
     * 
     * @since     1.0
     * @access    public
     * @return    string
     * @throws    
     */
    function getFrozenHtml()
    {
        if (is_array($this->_values)) {
            while (list($key,$val) = each($this->_values)) {
                for ($i=0;$i<count($this->_options);$i++) {
                    $optionTxt = $this->_options[$i]["text"];
                    $optionVal = $this->_options[$i]["attr"]["value"];
                    if ($val == $optionVal) {
                        $html = $optionTxt;
                        return $html;
                    }
                }
            }
        } else {
            for ($i=0;$i<count($this->_options);$i++) {
                $optionTxt = $this->_options[$i]["text"];
                $optionVal = $this->_options[$i]["attr"]["value"];
                if ($this->_values == $optionVal) {
                    $html = $optionTxt;
                    return $html;
                }
            }
        }
        
    } //end func getFrozenHtml
    
    /**
     * Returns the values of SELECT as hidden fields
     * 
     * @access    public
     * @return    string
     * @throws    
     */
    function getHidden($value)
    {
        $name = $this->getName();
        $value = $this->getValue();
        $html = "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
        return $html;
    } //end func getHidden

} //end class HTML_QuickForm_select
?>