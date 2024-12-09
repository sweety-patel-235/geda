<?php
/**
 * Extends the PaginatorHelper
 */
 
//App::import('Helper', 'Paginator');
namespace Cake\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
 /**
  * 
  * Short description for file
  * This file is use to display pagination on required page
  *
  * @author 
  *
  * @category   Class File
  * @Desc       Method in this class is use to display pagination with all the common pagination option such as first,next,privious,last etc. along with sorting functioanlity
  * @author     Employee Code : 203
  * @version    IG 4.0
  * @since      File available since IG 4.0 Release
  */
class ExPaginatorHelper extends Helper {
 
	/**
	 * Adds and 'asc' or 'desc' class to the sort links
	 * @see /cake/libs/view/helpers/PaginatorHelper#sort($title, $key, $options)
	 */
	/**
	 * 
	 * package_name
	 *
	 * Behaviour : Public
	 *
	 * @param : $title   : Title which is to be sorted
	 * @param : $key   : Key is use to match with sorting key and sort accordingly
	 * @param : $options : Option is array contain all the options like class(css)
	 * @return :  Return sorted data
	 * @defination : MEthod is use to sort data base on passed argument
	 *
	 */
	public function sort($title, $key = null, $options = array()) {
 
		// get current sort key & direction
		$sortKey = $this->sortKey();
		$sortDir = $this->sortDir();
 
		// add $sortDir class if current column is sort column
		if ($sortKey===$key && $key !== null)
		{
			if(isset($options['class']) && $options['class']!='')
			{
				$options['class'] =$options['class']." ". $sortDir;
			}
			else
			{
				$options['class'] =$sortDir;
			}
		}
		return parent::sort($key, $title, $options);
	}
	
	
	/**
	 * Adds and 'asc' or 'desc' class to the sort links
	 * @see /cake/libs/view/helpers/PaginatorHelper#sort($title, $key, $options)
	 */
	/**
	 * 
	 * package_name
	 *
	 * Behaviour : Public
	 *
	 * @param : $title   : Title which is to be sorted
	 * @param : $key   : Key is use to match with sorting key and sort accordingly
	 * @param : $options : Option is array contain all the options like class(css)
	 * @return :  Return sorted data
	 * @defination : MEthod is use to sort data base on passed argument
	 *
	 */
	public function first($first = '<< First', $options = array()) {
		$options = (array)$options + array(
			'tag' => 'span',
			'after' => null,
			'model' => $this->defaultModel(),
			'separator' => ' | ',
			'ellipsis' => '...',
			'class' => null
		);

		$params = array_merge(array('page' => 1), (array)$this->params($options['model']));
		unset($options['model']);

		if ($params['pageCount'] <= 1) {
			return '';
		}
		extract($options);
		unset($options['tag'], $options['after'], $options['model'], $options['separator'], $options['ellipsis'], $options['class']);

		$out = '';

		if (is_int($first) && $params['page'] >= $first) {
			if ($after === null) {
				$after = $ellipsis;
			}
			for ($i = 1; $i <= $first; $i++) {
				$out .= $this->Html->tag($tag, $this->link($i, array('page' => $i), $options), compact('class'));
				if ($i != $first) {
					$out .= $separator;
				}
			}
			$out .= $after;
		} elseif ($params['page'] > 1 && is_string($first)) {
			$options += array('rel' => 'first');
			$out = $this->Html->tag($tag, $this->link($first, array('page' => 1), $options), compact('class')) . $after;
		}
		return $out;
	}
	public function next($title = 'Pravin >>', $options = array(), $disabledTitle = null, $disabledOptions = array()) {
		$defaults = array(
			'rel' => 'next'
		);
		$options = (array)$options + $defaults;
		return $this->_pagingLink('Next', $title, $options, $disabledTitle, $disabledOptions);
	}
	
 
}
?>