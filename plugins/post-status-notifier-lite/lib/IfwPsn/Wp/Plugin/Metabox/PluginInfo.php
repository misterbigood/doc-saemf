<?php
/**
 * Plugin info metabox
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: PluginInfo.php 1248505 2015-09-18 13:49:54Z worschtebrot $
 * @package  IfwPsn_Wp
 */
require_once dirname(__FILE__) . '/Ajax.php';

class IfwPsn_Wp_Plugin_Metabox_PluginInfo extends IfwPsn_Wp_Plugin_Metabox_Ajax
{

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @param null $ajaxRequest
     */
    public function __construct(IfwPsn_Wp_Plugin_Manager $pm, $ajaxRequest = null)
    {
        $ajaxRequest = new IfwPsn_Wp_Plugin_Metabox_PluginInfoAjax($pm);

        parent::__construct($pm, $ajaxRequest);
    }

    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initId()
     */
    protected function _initId()
    {
        return 'plugin_info';
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initTitle()
     */
    protected function _initTitle()
    {
        return __('Plugin Info', 'ifw');
    }
    
    /**
     * (non-PHPdoc)
     * @see IfwPsn_Wp_Plugin_Admin_Menu_Metabox_Abstract::_initPriority()
     */
    protected function _initPriority()
    {
        return 'core';
    }
}
