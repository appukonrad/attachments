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

// Set up a few convenience items
$params = $this->params;
$secure = $params->get('secure', false);
$lists = $this->lists;
$list_for_parents = $lists['list_for_parents'];

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th width="1%" class="hidden-phone">
		 <input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
	 </th>
    <th width="1%" class="" width="1%" nowrap="nowrap"><?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder)
?></th>
    <th width="" class=""><?php echo JHtml::_('grid.sort', 'ATTACH_ATTACHMENT_FILENAME_URL', 'a.filename', $listDirn, $listOrder)
?></th>
    <th width="1%" class="n"><?php echo JHtml::_('grid.sort', 'ATTACH_DESCRIPTION', 'a.description', $listDirn, $listOrder)
?></th>
    <th width="1%" class="" width="5%" nowrap="nowrap"><?php echo JHtml::_('grid.sort', 'JFIELD_ACCESS_LABEL', 'a.access', $listDirn, $listOrder)
?></th>
	 <?php if ($params->get('user_field_1_name')): ?>
        <th width="1%" class=""><?php echo JHtml::_('grid.sort', $params->get('user_field_1_name', ''), 'a.user_field_1', $listDirn, $listOrder)
            ?></th>
	 <?php endif; ?>
	 <?php if ($params->get('user_field_2_name')): ?>
        <th width="1%" class=""><?php echo JHtml::_('grid.sort', $params->get('user_field_2_name', ''), 'a.user_field_2', $listDirn, $listOrder)
            ?></th>
	 <?php endif; ?>
	 <?php if ($params->get('user_field_3_name')): ?>
        <th width="1%" class=""><?php echo JHtml::_('grid.sort', $params->get('user_field_3_name', ''), 'a.user_field_3', $listDirn, $listOrder)
            ?></th>
	 <?php endif; ?>
    <th width="1%" class=""><?php echo JHtml::_('grid.sort', 'ATTACH_FILE_TYPE', 'a.file_type', $listDirn, $listOrder)
        ?></th>
    <th width="1%" class=""><?php echo JHtml::_('grid.sort', 'ATTACH_FILE_SIZE_KB', 'a.file_size', $listDirn, $listOrder)
        ?></th>
    <th width="1%" class=""><?php echo JHtml::_('grid.sort', 'ATTACH_CREATOR', 'u1.name', $listDirn, $listOrder)
        ?></th>
    <th width="1%" class=""><?php echo JHtml::_('grid.sort', 'JGLOBAL_CREATED', 'a.created', $listDirn, $listOrder)
        ?></th>
    <th width="1%" class=""><?php echo JHtml::_('grid.sort', 'ATTACH_LAST_MODIFIED', 'a.modified', $listDirn, $listOrder)
        ?></th>
        <?php if ($secure): ?>
        <th width="1%" class=""><?php echo JHtml::_('grid.sort', 'ATTACH_DOWNLOADS', 'a.download_count', $listDirn, $listOrder)
            ?></th>
	 <?php endif; ?>
</tr>


