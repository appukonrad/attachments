<?php
/**
 * Attachments component attachments view
 *
 * @package Attachments
 * @subpackage Attachments_Component
 *
 * @copyright Copyright (C) 2007-2016 Jonathan M. Cameron, All Rights Reserved
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link http://joomlacode.org/gf/project/attachments/frs/
 * @author Jonathan M. Cameron
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$lists = $this->lists;
?>
<div class="js-stools clearfix">
    <div class="clearfix">
        <div class="js-stools-container-bar">
            <label for="filter_search" class="element-invisible" aria-invalid="false"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <div class="btn-wrapper input-append">
	<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                       onchange="this.form.submit();" />    
                <button class="btn" onclick="this.form.submit();"><span class="icon-search"></span><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            </div>
            <div class="btn-wrapper">
                <button class="btn" onclick="document.id('filter_search').value = '';this.form.submit();">
                    <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
            </div>
            <div class="btn-wrapper">
                <button class="btn" id="reset_order" onclick="Joomla.tableOrdering('', 'asc', '');">
                    <?php echo JText::_('ATTACH_RESET_ORDER'); ?></button>
            </div>
        </div>
        <div class="js-stools-container-list hidden-phone hidden-tablet shown" style="">
            <div class="ordering-select hidden-phone">
	<?php echo JText::_('ATTACH_LIST_ATTACHMENTS_FOR_COLON') ?>
                <div class="js-stools-field-list">
                    <?php echo $lists['filter_parent_state_menu'] ?>
  </div>
                <div class="js-stools-field-list">
                    <?php echo $lists['filter_entity_menu'] ?>
                </div>
            </div>
        </div>
    </div>
</div>
