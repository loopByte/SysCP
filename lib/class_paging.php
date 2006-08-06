<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 06, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package Functions
 * @version $Id$
 */

	/**
	 * Class to manage paging system
	 * @package Functions
	 */
	class paging
	{
		/**
		 * Userinfo
		 * @var array
		 */
		var $userinfo = array();
		/**
		 * Database handler
		 * @var db
		 */
		var $db = false;
		/**
		 * MySQL-Table
		 * @var string
		 */
		var $table = '';
		/**
		 * Fields with description which should be selectable
		 * @var array
		 */
		var $fields = array();
		/**
		 * Entries per page
		 * @var int
		 */
		var $entriesperpage = 0;
		/**
		 * Number of entries of table
		 * @var int
		 */
		var $entries = 0;
		/**
		 * Sortorder, asc or desc
		 * @var string
		 */
		var $sortorder = 'asc';
		/**
		 * Sortfield
		 * @var string
		 */
		var $sortfield = '';
		/**
		 * Searchfield
		 * @var string
		 */
		var $searchfield = '';
		/**
		 * Searchtext
		 * @var string
		 */
		var $searchtext = '';
		/**
		 * Pagenumber
		 * @var int
		 */
		var $pageno = 0;

		/**
		 * Class constructor. Loads settings from request or from userdata and saves them to session.
		 *
		 * @param array userinfo
		 * @param string Name of Table
		 * @param array Fields, in format array( 'fieldname_in_mysql' => 'field_caption' )
		 * @param int entries per page
		 */
		function paging( $userinfo, $db, $table, $fields, $entriesperpage )
		{
			$this->userinfo = $userinfo;
			if( !is_array( $this->userinfo['lastpaging'] ) )
			{
				$this->userinfo['lastpaging'] = unserialize( $this->userinfo['lastpaging'] );
			}

			$this->db = $db;
			$this->table = $table;
			$this->fields = $fields;
			$this->entriesperpage = $entriesperpage;

			$checklastpaging = ( isset( $this->userinfo['lastpaging']['table'] ) && $this->userinfo['lastpaging']['table'] == $this->table );
			$this->userinfo['lastpaging']['table'] = $this->table;

			if( isset( $_REQUEST['sortorder'] ) && ( strtolower( $_REQUEST['sortorder'] ) == 'desc' || strtolower( $_REQUEST['sortorder'] ) == 'asc' ) )
			{
				$this->sortorder = strtolower( $_REQUEST['sortorder'] );
			}
			else
			{
				if( $checklastpaging && isset( $this->userinfo['lastpaging']['sortorder'] ) && ( strtolower( $this->userinfo['lastpaging']['sortorder'] ) == 'desc' || strtolower( $this->userinfo['lastpaging']['sortorder'] ) == 'asc' ) )
				{
					$this->sortorder = strtolower( $this->userinfo['lastpaging']['sortorder'] );
				}
				else
				{
					$this->sortorder = 'asc';
				}
			}
			$this->userinfo['lastpaging']['sortorder'] = $this->sortorder;

			if( isset( $_REQUEST['sortfield'] ) && isset( $fields[$_REQUEST['sortfield']] ) )
			{
				$this->sortfield = $_REQUEST['sortfield'];
			}
			else
			{
				if( $checklastpaging && isset( $this->userinfo['lastpaging']['sortfield'] ) && isset( $fields[$this->userinfo['lastpaging']['sortfield']] ) )
				{
					$this->sortfield = $this->userinfo['lastpaging']['sortfield'];
				}
				else
				{
					$fieldnames = array_keys( $fields );
					$this->sortfield = $fieldnames[0];
				}
			}
			$this->userinfo['lastpaging']['sortfield'] = $this->sortfield;

			if( isset( $_REQUEST['searchfield'] ) && isset( $fields[$_REQUEST['searchfield']] ) )
			{
				$this->searchfield = $_REQUEST['searchfield'];
			}
			else
			{
				if( $checklastpaging && isset( $this->userinfo['lastpaging']['searchfield'] ) && isset( $fields[$this->userinfo['lastpaging']['searchfield']] ) )
				{
					$this->searchfield = $this->userinfo['lastpaging']['searchfield'];
				}
				else
				{
					$fieldnames = array_keys( $fields );
					$this->searchfield = $fieldnames[0];
				}
			}
			$this->userinfo['lastpaging']['searchfield'] = $this->searchfield;

			if( isset( $_REQUEST['searchtext'] ) && ( preg_match( "/^[0-9a-zA-Z\*\.]+$/", $_REQUEST['searchtext'] ) || $_REQUEST['searchtext'] === '' ) )
			{
				$this->searchtext = $_REQUEST['searchtext'];
			}
			else
			{
				if( $checklastpaging && isset( $this->userinfo['lastpaging']['searchtext'] ) && preg_match( "/^[0-9a-zA-Z\*\.]+$/", $this->userinfo['lastpaging']['searchtext'] ) )
				{
					$this->searchtext = $this->userinfo['lastpaging']['searchtext'];
				}
				else
				{
					$this->searchtext = '';
				}
			}
			$this->userinfo['lastpaging']['searchtext'] = $this->searchtext;

			if( isset( $_REQUEST['pageno'] ) && intval( $_REQUEST['pageno'] ) != 0 )
			{
				$this->pageno = intval( $_REQUEST['pageno'] );
			}
			else
			{
				if( $checklastpaging && isset( $this->userinfo['lastpaging']['pageno'] ) && intval( $this->userinfo['lastpaging']['pageno'] ) != 0 )
				{
					$this->pageno = intval( $this->userinfo['lastpaging']['pageno'] );
				}
				else
				{
					$this->pageno = 1;
				}
			}
			$this->userinfo['lastpaging']['pageno'] = $this->pageno;

			$query = 'UPDATE `'.TABLE_PANEL_SESSIONS.'` ' .
					 'SET `lastpaging`="' . addslashes( serialize( $this->userinfo['lastpaging'] ) ) . '" ' .
					 'WHERE `hash`="' . $userinfo['hash'] . '" ' .
					 '  AND `userid` = "' . $userinfo['userid'] . '" ' .
					 '  AND `ipaddress` = "' . $userinfo['ipaddress'] . '" ' .
					 '  AND `useragent` = "' . $userinfo['useragent'] . '" ' .
					 '  AND `adminsession` = "' . $userinfo['adminsession'] . '" ';
			$this->db->query($query);
		}

		/**
		 * Sets number of entries and adjusts pageno if the number of entries doesn't correspond to the pageno.
		 *
		 * @param int entries
		 */
		function setEntries( $entries )
		{
			$this->entries = $entries;
			if( ($this->pageno - 1 ) * $this->entriesperpage > $this->entries )
			{
				$this->pageno = 1;
			}
			return true;
		}

		/**
		 * Checks if a row should be displayed or not, used in loops
		 *
		 * @param int number of row
		 * @return bool to display or not to display, that's the question
		 */
		function checkDisplay( $count )
		{
			$begin = ( intval( $this->pageno ) - 1 ) * intval( $this->entriesperpage );
			$end = ( intval( $this->pageno ) * intval( $this->entriesperpage ) ) ;
			return ( ( $count >= $begin && $count < $end ) || $this->entriesperpage == 0 );
		}

		/**
		 * Returns condition code for sql query
		 *
		 * @param bool should returned condition code start with WHERE (false) or AND (true)?
		 * @return string the condition code
		 */
		function getSqlWhere( $append = false )
		{
			if( $this->searchtext != '' )
			{
				if( $append == true )
				{
					$condition = ' AND ';
				}
				else
				{
					$condition = ' WHERE ';
				}

				$searchfield = explode( '.', $this->searchfield );
				foreach( $searchfield as $id => $field )
				{
					if( substr( $field, -1, 1 ) != '`' )
					{
						$field .= '`';
					}
					if( $field{0} != '`' )
					{
						$field = '`' . $field;
					}
					$searchfield[$id] = $field;
				}
				$searchfield = implode( '.', $searchfield );

				$searchtext = str_replace( '*', '%', $this->searchtext );

				$condition .= $searchfield . ' LIKE "' . $searchtext . '" ';
			}
			else
			{
				$condition = '';
			}

			return $condition;
		}

		/**
		 * Returns "order by"-code for sql query
		 *
		 * @return string the "order by"-code
		 */
		function getSqlOrderBy()
		{
			$sortfield = explode( '.', $this->sortfield );
			foreach( $sortfield as $id => $field )
			{
				if( substr( $field, -1, 1 ) != '`' )
				{
					$field .= '`';
				}
				if( $field{0} != '`' )
				{
					$field = '`' . $field;
				}
				$sortfield[$id] = $field;
			}
			$sortfield = implode( '.', $sortfield );

			$sortorder = strtoupper( $this->sortorder );

			return 'ORDER BY ' . $sortfield . ' ' . $sortorder;
		}

		/**
		 * Currently not used
		 *
		 * @return string always empty
		 */
		function getSqlLimit()
		{
			/**
			 * currently not in use
			 */
			return '';
		}

		/**
		 * Returns html code for sorting field
		 *
		 * @param array Language array
		 * @return string the html sortcode
		 */
		function getHtmlSortCode( $lng, $break = false )
		{
			$sortcode = '<select class="dropdown_noborder" name="sortfield">';
			foreach( $this->fields as $fieldname => $fieldcaption )
			{
				$sortcode .= makeoption( $fieldcaption, $fieldname, $this->sortfield );
			}
			$sortcode .= '</select>' . ( $break ? '<br />' : '&nbsp;' ) . '<select class="dropdown_noborder" name="sortorder">';
			foreach( array( 'asc' => $lng['panel']['ascending'], 'desc' => $lng['panel']['decending'] ) as $sortordertype => $sortorderdescription )
			{
				$sortcode .= makeoption( $sortorderdescription, $sortordertype, $this->sortorder );
			}
			$sortcode .= '</select>&nbsp;<input type="submit" name="Go" value="Go" />';
			return $sortcode;
		}

		/**
		 * Returns html code for sorting arrows
		 *
		 * @param string URL to use as base for links
		 * @param string If set, only this field will be returned
		 * @return mixed An array or a string (if field is set) of html code of arrows
		 */
		function getHtmlArrowCode( $baseurl, $field = '' )
		{
			if( $field != '' && isset( $this->fields[$field] ) )
			{
				$arrowcode = '<a href="' . $baseurl . '&amp;sortfield=' . $field . '&amp;sortorder=desc"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="' . $baseurl . '&amp;sortfield=' . $field . '&amp;sortorder=asc"><img src="images/order_asc.gif" border="0" alt="" /></a>';
			}
			else
			{
				$arrowcode = array();
				foreach( $this->fields as $fieldname => $fieldcaption )
				{
					$arrowcode[$fieldname] = '<a href="' . $baseurl . '&amp;sortfield=' . $fieldname . '&amp;sortorder=desc"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="' . $baseurl . '&amp;sortfield=' . $fieldname . '&amp;sortorder=asc"><img src="images/order_asc.gif" border="0" alt="" /></a>';
				}
			}
			return $arrowcode;
		}

		/**
		 * Returns html code for searching field
		 *
		 * @param array Language array
		 * @return string the html searchcode
		 */
		function getHtmlSearchCode( $lng )
		{
			$sortcode = $lng['panel']['search'] . ': <select class="dropdown_noborder" name="searchfield">';
			foreach( $this->fields as $fieldname => $fieldcaption )
			{
				$sortcode .= makeoption( $fieldcaption, $fieldname, $this->searchfield );
			}
			$sortcode .= '</select>&nbsp;<input type="text" name="searchtext" value="' . $this->searchtext . '" />&nbsp;<input type="submit" name="Go" value="Go" />';
			return $sortcode;
		}

		/**
		 * Returns html code for paging
		 *
		 * @param string URL to use as base for links
		 * @return string the html pagingcode
		 */
		function getHtmlPagingCode( $baseurl )
		{
			$pages = intval( $this->entries / $this->entriesperpage );
			if( $this->entries % $this->entriesperpage != 0 )
			{
				$pages++;
			}

			if( $pages > 1 )
			{
				$start = $this->pageno - 4;
				if( $start < 1 )
				{
					$start = 1;
				}
				$stop = $this->pageno + 4;
				if( $stop > $pages )
				{
					$stop = $pages;
				}

				$pagingcode = '<a href="' . $baseurl . '&amp;pageno=1">&laquo;</a> <a href="' . $baseurl . '&amp;pageno=' . ( ( intval( $this->pageno ) - 1 ) == 0 ? '1' : intval( $this->pageno ) - 1 ) . '">&lt;</a> ';

				for( $i = $start; $i <= $stop; $i++ )
				{
					if( $i != $this->pageno )
					{
						$pagingcode .= ' <a href="' . $baseurl . '&amp;pageno=' . $i . '">' . $i . '</a> ';
					}
					else
					{
						$pagingcode .= ' <b>' . $i . '</b> ';
					}
				}

				$pagingcode .= ' <a href="' . $baseurl . '&amp;pageno=' . ( ( intval( $this->pageno ) + 1 ) > $pages ? $pages : intval( $this->pageno ) + 1 ) . '">&gt;</a> <a href="' . $baseurl . '&amp;pageno=' . $pages . '">&raquo;</a>';
			}
			else
			{
				$pagingcode = '';
			}

			return $pagingcode;
		}

	}

?>
