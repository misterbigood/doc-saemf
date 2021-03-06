<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: Role.php 911603 2014-05-10 10:58:23Z worschtebrot $
 * @package   
 */ 
class IfwPsn_Wp_Proxy_Role 
{

    public static function getEditable()
    {
        return get_editable_roles();
    }

    public static function getEditableNames()
    {
        $result = array();
        $roles = self::getEditable();

        foreach ($roles as $k => $v) {
            $result[$k] = _x($v['name'], 'User role');
        }

        return $result;
    }

    public static function getAll()
    {
        global $wp_roles;

        return $wp_roles->roles;
    }

    public static function getAllNames()
    {
        $result = array();
        $roles = self::getAll();

        foreach ($roles as $k => $v) {
            $result[$k] = _x($v['name'], 'User role');
        }

        return $result;
    }
}
