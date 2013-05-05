<?php
/**
 * Attachments component
 *
 * @package Attachments
 * @subpackage Attachments_Component
 *
 * @copyright Copyright (C) 2007-2012 Jonathan M. Cameron, All Rights Reserved
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link http://joomlacode.org/gf/project/attachments/frs/
 * @author Jonathan M. Cameron
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/** Define the legacy classes, if necessary */
require_once(JPATH_SITE.'/components/com_attachments/legacy/view.php');


/**
 * View for the help pages
 *
 * The goal is to provide a PHP programmatic interface to create a help page
 * using primarily PHP function calls in the view/template.	 The styling is
 * base on styling of Restructured Text documents rendered into HTML using
 * converion tools such as docutils.
 *
 * @package Attachments
 */
class HelpView extends JViewLegacy
{
	/**
	 * Data about each of the document section headers
	 *
	 * Should be initialized in the view or templage to contain an array of
	 * arrays of information about the sections like this:
	 *
	 * $this->_sections = Array( 1  => Array( 'id' => 'introduction',
	 *						   'code' =>		   'SECTION_TITLE_1',
	 *						   'title' => JText::_('SECTION_TITLE_1'),
	 *						   ...
	 *						   ),
	 *
	 * where the 'SECTION_TITLE_1' is the language token for the title of
	 * section 1.
	 */
	protected $_sections = null;


	/**
	 * Add the information about a section to the $sections data
	 *
	 * @param  int     $sectnum  the section number (constant)
	 * @param  string  $id       the section ID string (unique name to be used as anchor target)
	 * @param  string  $code     the language code for this section title
	 */
	protected function saveSectionInfo($sectnum, $id, $code)
	{
		$this->_sections[$sectnum] = Array('id' => $id, 'code' => $code, 'title' => JText::_($code));
	}


	/**
	 * Add a link for the specified section
	 *
	 * @param int  $sect_num  The section number to link to
	 *
	 * @return string an html link for the specified section
	 */
	protected function sectionLink($sect_num)
	{
		$id = $this->_sections[$sect_num]['id'];
		$title = $this->_sections[$sect_num]['title'];
		return "<a class=\"reference internal\" href=\"#$id\">$title</a>";
	}


	/**
	 * Replace a series of items from the html using an array of replacements
	 *
	 * For example:
	 *	  replace('<span>{TEST}-{NUM}</span>', Array('{TEST}' => 'MyTest', '{NUM}' => '23'))
	 * returns:
	 *	  <span>MyTest-23</span>
	 *
	 * @param  string  $html		 The original HTML string to be modified
	 * @param  array   $replacement	 Array of textual replacments
	 *
	 * @return	string	the original HTML with the replacements performed
	 */
	protected function replace($html, $replacements)
	{
		if ( is_array($replacements) )
		{
			foreach ($replacements as $tag => $replace)
			{
				$html = str_replace($tag, $replace, $html);
			}
		}

		return $html;
	}


	/**
	 * Add a table of contents
	 *
	 * @param  string  $title_code	The language code for the title of the table of contents
	 */
	protected function tableOfContents($title_code, $class = 'contents topic')
	{
		$title = JText::_($title_code);
		$code = $this->textCodeSpan($title_code);

		$html  = "<div class=\"$class\" id=\"contents\">\n";
		$html .= "	 <p class=\"topic-title first\">" . $title . $code . "</p>\n";
		$html .= "	 <ul class=\"$class\">\n";
		foreach ($this->_sections as $sect_num => $sdata)
		{
			$html .= '		' . $this->sectionTOC($sect_num);
		}
		$html .= "	 </ul>\n";
		$html .= "</div> <!-- table of contents -->\n";
		echo $html;
	}


	/**
	 * Construct a section Table-of-contents line for a section
	 *
	 * @param  int	$sect_num The section number to echo (as a list element)
	 *
	 * @return string  the table-of-conents list item
	 */
	protected function sectionTOC($sect_num)
	{
		$sect_data = $this->_sections[$sect_num];
		$sid = $sect_data['id'];
		$stitle = $sect_data['title'];
		return "<li><a class=\"reference internal\" href=\"#$sid\" id=\"id$sect_num\">$stitle</a></li>\n";
	}


	/**
	 * Add the start of a section (using <h1>)
	 *
	 * @param  int	$sect_num The desired section number
	 */
	protected function startSection($sect_num)
	{
		$sect_data = $this->_sections[$sect_num];
		$sid = $sect_data['id'];
		$text_code = $sect_data['code'];
		$stitle = $sect_data['title'];
		$tcid = "<span class=\"text_code\">[$text_code]</span>";
		$hclass = 'class="toc-backref"';
		$html =	 "<div class=\"section\" id=\"$sid\">\n";
		$html .= "<h1><a $hclass href=\"#id$sect_num\">$stitle$tcid</a></h1>\n";
		echo $html;
	}


	/**
	 * Add the end of a section
	 *
	 * @param  int	$sect_num The desired section number
	 */
	protected function endSection($sect_num)
	{
		echo "</div><?-- end of section $sect_num -->\n";
	}


	/**
	 * Add the start of a subsection (using <h2>)
	 *
	 * @param  array  $sect_data An array of data for the subsection (providing 'id' and 'title')
	 */
	protected function startSubSection($sect_data)
	{
		$sid = $sect_data['id'];
		$stitle = $sect_data['title'];
		$html =	 "<div class=\"section\" id=\"$sid\">\n";
		$html .= "<h2>$stitle</h2>\n";
		echo $html;
	}


	/**
	 * Add the start of a subsection (using <h2>)
	 *
	 * @param  string  $title  The subsection title (should be same as in the start)
	 */
	protected function endSubSection($title)
	{
		echo "</div><?-- end of subsection $title -->\n";
	}


	/**
	 * Construct an admonition (note, warning, hint, etc)
	 *
	 * @param  string  $type		  The type of admonition (hint, note, important, warning)
	 * @param  string  $type_code	  Language token for the name of the admonition (eg, $type)
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs in the admonition)
	 * @param  array   $replacements  Array of replacements to be applied to the body text (see replace functin)
	 * @param  bool	   $terminate	  Whether to terminatate the <div> that contains the note
	 *
	 * @param  string  the HTML for the admonition
	 */
	protected function addAdmonition($type, $type_code, $text_codes, $replacements = null, $terminate = true)
	{
		$title = JText::_($type_code);
		if (!is_array($text_codes))
		{
			$text_codes = Array($text_codes);
		}

		$html  = "<div class=\"$type\">\n";
		$html .= "	 <p class=\"first admonition-title\">$title</p>\n";
		foreach ($text_codes as $text_code)
		{
			$tcid = "<span class=\"text_code\">[$text_code]</span>";
			$text = $this->replace(JText::_($text_code), $replacements);
			$html .= "	 <p class=\"last\">" . $text . $tcid . "</p>\n";
		}
		if ( $terminate )
		{
			$html .= "</div>\n";
		}

		echo $html;
	}


	/**
	 * Add the end the admonition
	 */
	protected function endAdmonition()
	{
		echo "</div>\n";
	}


	/**
	 * Add a 'hint' admonition
	 *
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs in the admonition)
	 * @param  array   $replacements  Array of replacements to be applied to the body text (see replace functin)
	 * @param  bool	   $terminate	  Whether to terminatate the <div> that contains the note
	 */
	protected function addHint($text_codes, $replacements = null, $terminate = true)
	{
		echo $this->addAdmonition('hint', 'ATTACH_HELP_HINT', $text_codes, $replacements, $terminate);
	}


	/**
	 * Add an 'important' admonition
	 *
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs in the admonition)
	 * @param  array   $replacements  Array of replacements to be applied to the body text (see replace functin)
	 * @param  bool	   $terminate	  Whether to terminatate the <div> that contains the note
	 */
	protected function addImportant($text_codes, $replacements = null, $terminate = true)
	{
		echo $this->addAdmonition('important', 'ATTACH_HELP_IMPORTANT', $text_codes, $replacements, $terminate);
	}


	/**
	 * Add a 'note' admonition
	 *
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs in the admonition)
	 * @param  array   $replacements  Array of replacements to be applied to the body text (see replace functin)
	 * @param  bool	   $terminate	  Whether to terminatate the <div> that contains the note
	 */
	protected function addNote($text_codes, $replacements = null, $terminate = true)
	{
		echo $this->addAdmonition('note', 'ATTACH_HELP_NOTE', $text_codes, $replacements, $terminate);
	}


	/**
	 * Add a 'warning' admonition
	 *
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs in the admonition)
	 * @param  array   $replacements  Array of replacements to be applied to the body text (see replace functin)
	 * @param  bool	   $terminate	  Whether to terminatate the <div> that contains the note
	 */
	protected function addWarning($text_codes, $replacements = null, $terminate = true)
	{
		echo $this->addAdmonition('warning', 'ATTACH_HELP_WARNING', $text_codes, $replacements, $terminate);
	}


	/**
	 * Add a paragraph
	 *
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs)
	 * @param  array   $replacements  Array of replacements to be applied to the text (see replace functin)
	 * @param  string  $pclass		  The class for the paragraph HTML <p> element
	 */
	protected function addParagraph($text_codes, $replacements = null, $pclass = null)
	{
		if (!is_array($text_codes))
		{
			$text_codes = Array($text_codes);
		}
		$html = '';
		foreach ($text_codes as $text_code)
		{
			$tcid = "<span class=\"text_code\">[$text_code]</span>";
			$text = $this->replace(JText::_($text_code), $replacements) . $tcid;
			if ($pclass)
			{
				$html .= "<p class=\"$pclass\">" . $text . "</p>\n";
			}
			else
			{
				$html .= '<p>' . $text . "</p>\n";
			}
		}

		echo $html;
	}


	/**
	 * Add a preformatted block
	 *
	 * @param  string  $text The raw string to print literally
	 * @param  string  $class The class for the HTML <pre> block
	 */
	protected function addPreBlock($text, $class='literal-block')
	{
		$html = "<pre class=\"$class\">\n";
		$html .= $text . "\n";
		$html .= "</pre>\n";
		echo $html;
	}


	/**
	 * Start an a list (unordered by default)
	 *
	 * @param  string  $type The type of list (defaults to unordered <ul>)
	 * @param  string  $class The class for the HTML <ul> or <ol> element
	 */
	protected function startList($type = 'ul', $class='simple')
	{
		echo "<$type class=\"$class\">\n";
	}


	/**
	 * Add a list element
	 *
	 * @param  array   $text_codes	  Array of Language tokens for the body text (as separate paragraphs inside the list element)
	 * @param  array   $replacements  Array of replacements to be applied to the text (see replace functin)
	 * @param  bool	   $terminate	  Whether to terminatate the <li> that contains the text
	 */
	protected function addListElement($text_codes, $replacements = null, $terminate = true)
	{
		if (!is_array($text_codes))
		{
			$text_codes = Array($text_codes);
		}

		$html = '<li>';

		foreach ($text_codes as $text_code)
		{
			$tcid = "<span class=\"text_code\">[$text_code]</span>";
			$text = $this->replace(JText::_($text_code), $replacements);
			$html .= "<p>" . $text . $tcid . "</p>\n";
		}

		if ($terminate)
		{
			$html .= "</li>\n";
		}

		echo $html;
	}


	/**
	 * Add the ending of a list element (use with unterminated list element)
	 */
	protected function endListElement()
	{
		echo "</li>\n";
	}


	/**
	 * Add a link as a list element
	 *
	 * @param  string  $url the URL of the link
	 * @param  string  $text_code the language token for the text of the link
	 */
	protected function addListElementLink($url, $text_code)
	{
		$tcid = "<span class=\"text_code\">[$text_code]</span>";
		$text = $this->replace(JText::_($text_code), Array('{LINK}' => $url));
		echo "<li><a class=\"reference external\" href=\"$url\">$text$tcid</a></li>\n";
	}


	/**
	 * Add a list elment with raw HTML
	 *
	 * @parma  string  $html The HTML to insert into the list element
	 */
	protected function addListElementHtml($html)
	{
		echo "<li>$html</li>\n";
	}


	/**
	 * Add the end of a list
	 *
	 * @param  string  $class The class for the HTML </ul> or </ol> element
	 */
	protected function endList($type = 'ul')
	{
		echo "</$type>\n";
	}


	/**
	 * Add a line break
	 */
	protected function addLineBreak()
	{
		echo "<br/>\n";
	}


	/**
	 * Add a hidden span containing the Language token for the preceeding text
	 *
	 * @param  string  $text_code  The language code to insert in the hidden span (raw)
	 */
	protected function textCodeSpan($text_code)
	{
		return "<span class=\"text_code\">[$text_code]</span>";
	}


	/**
	 * Add a figure: and image with an optional caption
	 *
	 * @param  string  $filename  Filename for the image (full path)
	 * @param  string  $alt_code  Language token for the text to be use for the 'alt' attribute
	 * @parma  string  $caption_code  Language token for the text to use for the caption (OPTIONAL)
	 * @param  string  $dclass	  Class for the figure <div>
	 */
	protected function addFigure($filename, $alt_code, $caption_code = null, $dclass = 'figure')
	{
		$html = "<div class=\"$dclass\">\n";
		$html .= $this->image($filename, JText::_($alt_code)) . "\n";
		if ( $caption_code )
		{
			$html .= '<p class="caption">' . JText::_($caption_code) . "</p>\n";
		}
		$html .= '</div>';
		echo $html;
	}



	/**
	 * Return an image URL if the file is found
	 *
	 * @param  string  $filename  Filename for the image in the media folder (more below)
	 * @param  string  $alt		  Text to be inserted into the alt='text' attribute
	 * @param  array   $attribs	  Attributes to be added to the image URL
	 *
	 * The file location uses the JHtml::image() function call which expects
	 * to find images in the [base]/media/com_attachments/images directory.
	 * This function as the necessary checks to add level of language folders
	 * (like the translation files).  For instance:
	 *
	 *    For a file:
	 *         [base]/media/com_attachments/images/en-GB/test1.png
	 *    Use:
	 *         $this->image('com_attachments/test1.png')
	 *
	 *
	 * @return string image URL (or null if the image was not found)
	 */
	protected function image($filename, $alt, $attribs = Array())
	{
		$lcode = $this->lang->getDefault();

		// First try the current language
		$img = JHtml::image('com_attachments/help/' . $lcode . '/' . $filename, $alt, $attribs, true);

		if ($img)
		{
			return $img;
		}

		// If that fails, return the English/en-GB image
		if ( $lcode != 'en-GB' )
		{
			return JHtml::image('com_attachments/help/en-GB/' . $filename, $alt, $attribs, true);
		}

		// The image was not found for either language so return nothing
		return null;
	}

}
