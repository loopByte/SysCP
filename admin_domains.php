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
 * @package Panel
 * @version $Id$
 */

	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");

	if(isset($_POST['id']))
	{
		$id=intval($_POST['id']);
	}
	elseif(isset($_GET['id']))
	{
		$id=intval($_GET['id']);
	}

	if($page == 'domains' || $page == 'overview')
	{
		if($action=='')
		{
			$fields = array(
								'd.domain' => $lng['domains']['domainname'],
								'ip.ip' => $lng['admin']['ipsandports']['ip'],
								'ip.port' => $lng['admin']['ipsandports']['port'],
								'c.name' => $lng['customer']['name'],
								'c.firstname' => $lng['customer']['firstname'],
								'c.company' => $lng['customer']['company'],
								'c.loginname' => $lng['login']['username']
							);
			$paging = new paging( $userinfo, $db, TABLE_PANEL_DOMAINS, $fields, $settings['panel']['paging'] );

			$domains='';
			$result=$db->query(
				"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`isemaildomain`, `d`.`parentdomainid`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`company`, `c`.`standardsubdomain`, `ad`.`id` AS `aliasdomainid`, `ad`.`domain` AS `aliasdomain`, `da`.`id` AS `domainaliasid`, `da`.`domain` AS `domainalias`, `ip`.`id` AS `ipid`, `ip`.`ip`, `ip`.`port` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
				"LEFT JOIN `".TABLE_PANEL_DOMAINS."` `ad` ON `d`.`aliasdomain`=`ad`.`id` " .
				"LEFT JOIN `".TABLE_PANEL_DOMAINS."` `da` ON `da`.`aliasdomain`=`d`.`id` " .
				"LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) " .
				"WHERE `d`.`parentdomainid`='0' ".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")." ".
				$paging->getSqlWhere( true )." ".$paging->getSqlOrderBy()." ".$paging->getSqlLimit()
			);
			$paging->setEntries( $db->num_rows($result) );

			$sortcode = $paging->getHtmlSortCode( $lng );
			$arrowcode = $paging->getHtmlArrowCode( $filename . '?page=' . $page . '&amp;s=' . $s );
			$searchcode = $paging->getHtmlSearchCode( $lng );
			$pagingcode = $paging->getHtmlPagingCode( $filename . '?page=' . $page . '&amp;s=' . $s );

			$domain_array=array();
			while($row=$db->fetch_array($result))
			{
				$row['domain'] = $idna_convert->decode($row['domain']);
				$row['aliasdomain'] = $idna_convert->decode($row['aliasdomain']);
				$row['domainalias'] = $idna_convert->decode($row['domainalias']);
				$domain_array[$row['domain']] = $row;
			}

			/**
			 * We need ksort/krsort here to make sure idna-domains are also sorted correctly
			 */
			if( $paging->sortfield == 'd.domain' && $paging->sortorder == 'asc' )
			{
				ksort( $domain_array );
			}
			elseif( $paging->sortfield == 'd.domain' && $paging->sortorder == 'desc' )
			{
				krsort( $domain_array );
			}

			$i = 0;
			$count = 0;
			foreach($domain_array as $row)
			{
				if( $paging->checkDisplay( $i ) )
				{
					$row = htmlentities_array( $row );
					eval("\$domains.=\"".getTemplate("domains/domains_domain")."\";");
					$count++;
				}
				$i++;
			}
			eval("echo \"".getTemplate("domains/domains")."\";");
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`zonefile` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`id`='$id' AND `d`.`id` <> `c`.`standardsubdomain`".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' "));
			$alias_check=$db->query_first('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$id.'\'');
			if($result['domain']!='' && $alias_check['count'] == 0)
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$query =
						'SELECT `id` ' .
						'FROM `'.TABLE_PANEL_DOMAINS.'` ' .
						'WHERE (`id`="'.$id.'" OR `parentdomainid`="'.$id.'") ' .
						'  AND  `isemaildomain`="1"';
					$subResult = $db->query($query);
					$idString = array();
					while ( $subRow = $db->fetch_array($subResult) )
					{
						$idString[] = '`domainid` = "'.$subRow['id'].'"';
					}
					$idString = implode(' OR ', $idString);
					if ( $idString != '' )
					{
						$query = 
							'DELETE FROM `'.TABLE_MAIL_USERS.'` ' .
							'WHERE '.$idString;
						$db->query($query);
						$query = 
							'DELETE FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
							'WHERE '.$idString;
						$db->query($query);
					}
					$db->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `id`='$id' OR `parentdomainid`='".$result['id']."'");
					$deleted_domains = $db->affected_rows();
					$db->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used` = `subdomains_used` - 0".($deleted_domains - 1)." WHERE `customerid` = '{$result['customerid']}'");
					$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` - 1 WHERE `adminid` = '{$userinfo['adminid']}'");
					updateCounters () ;

					inserttask('1');
					inserttask('4');

					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					ask_yesno('admin_domain_reallydelete', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action ), $idna_convert->decode($result['domain']));
				}
			}
		}

		elseif($action=='add')
		{
			if($userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$domain = $idna_convert->encode(preg_replace(Array('/\:(\d)+$/','/^https?\:\/\//'),'',addslashes($_POST['domain'])));
					$customerid = intval($_POST['customerid']);
					$subcanemaildomain = intval($_POST['subcanemaildomain']);
					$isemaildomain = intval($_POST['isemaildomain']);
					$aliasdomain = intval($_POST['alias']);
					$customer = $db->query_first("SELECT `documentroot` FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='$customerid'");
					$documentroot = $customer['documentroot'];
					if($userinfo['change_serversettings'] == '1')
					{
						$isbinddomain = $_POST['isbinddomain'];
						$caneditdomain = intval($_POST['caneditdomain']);
						$zonefile = addslashes($_POST['zonefile']);
						$openbasedir = intval($_POST['openbasedir']);
						$safemode = intval($_POST['safemode']);
						$speciallogfile = intval($_POST['speciallogfile']);
						$specialsettings = str_replace("\r\n", "\n", $_POST['specialsettings']);

						$ipandport = intval($_POST['ipandport']);
						$ipandport_check = $db->query_first( "SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id` = '".$ipandport."' " );
						if( !isset( $ipandport_check['id'] ) || $ipandport_check['id'] == '0' )
						{
							$ipandport = $settings['system']['defaultip'];
						}

						if(isset($_POST['documentroot']) && $_POST['documentroot'] != '')
						{
							if ( substr($_POST['documentroot'],0,1) != '/' )
							{
								$documentroot .= '/'.addslashes($_POST['documentroot']);
							}
							else 
							{
								$documentroot = addslashes($_POST['documentroot']);
							}
						}
					}
					else
					{
						$isbinddomain = '1';
						$caneditdomain = '1';
						$zonefile = '';
						$openbasedir = '1';
						$safemode = '1';
						$speciallogfile = '1';
						$specialsettings = '';
						$ipandport = $settings['system']['defaultip'];
					}
					if(!preg_match('/^https?\:\/\//', $documentroot))
					{
						$documentroot = makeCorrectDir($documentroot);
					}

					$domain_check = $db->query_first("SELECT `id`, `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `domain` = '$domain'");
					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$customerid.'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$customerid.'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}

					if($openbasedir != '1')
					{
						$openbasedir = '0';
					}
					if($safemode != '1')
					{
						$safemode = '0';
					}
					if($speciallogfile != '1')
					{
						$speciallogfile = '0';
					}
					if($isbinddomain != '1')
					{
						$isbinddomain = '0';
					}
					if($isemaildomain != '1')
					{
						$isemaildomain = '0';
					}
					if($subcanemaildomain != '1')
					{
						$subcanemaildomain = '0';
					}
					if($caneditdomain != '1')
					{
						$caneditdomain = '0';
					}


					if($domain=='')
					{
						standard_error(array('stringisempty','mydomain'));
					}
					elseif(!check_domain($domain))
					{
						standard_error(array('stringiswrong','mydomain'));
					}
					elseif($documentroot=='')
					{
						standard_error(array('stringisempty','mydocumentroot'));
					}
					elseif($customerid==0)
					{
						standard_error('adduserfirst');
					}
					elseif($domain_check['domain'] == $domain)
					{
						standard_error('domainalreadyexists',$domain);
					}
					elseif($aliasdomain_check['id']!=$aliasdomain)
					{
						standard_error('domainisaliasorothercustomer');
					}

					else
					{
						if( ($openbasedir == '0' || $safemode == '0') 
						    && (!isset($_POST['reallydoit']) 
						       || $_POST['reallydoit'] != 'reallydoit') )
						{
							ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, array( 'page' => $page, 'action' => $action, 'domain' => $domain, 'documentroot' => $documentroot, 'customerid' => $customerid, 'alias' => $aliasdomain, 'isbinddomain' => $isbinddomain, 'isemaildomain' => $isemaildomain, 'subcanemaildomain' => $subcanemaildomain, 'caneditdomain' => $caneditdomain, 'zonefile' => $zonefile, 'speciallogfile' => $speciallogfile, 'openbasedir' => $openbasedir, 'ipandport' => $ipandport, 'safemode' => $safemode, 'specialsettings' => $specialsettings, 'reallydoit' => 'reallydoit' ));
							exit;
						}
						$pattern = sprintf('^%s', $customer['documentroot']);
						if( !ereg($pattern,$documentroot) 
						    && ( !isset($_POST['reallydocroot'] ) 
						       || $_POST['reallydocroot'] != 'reallydocroot') 
						    && !preg_match('/^https?\:\/\//', $documentroot) )
						{
							$params = array( 'page' => $page, 'action' => $action, 'domain' => $domain, 'documentroot' => $documentroot, 'customerid' => $customerid, 'alias' => $aliasdomain, 'isbinddomain' => $isbinddomain, 'isemaildomain' => $isemaildomain, 'subcanemaildomain' => $subcanemaildomain, 'caneditdomain' => $caneditdomain, 'zonefile' => $zonefile, 'speciallogfile' => $speciallogfile, 'openbasedir' => $openbasedir, 'ipandport' => $ipandport, 'safemode' => $safemode, 'specialsettings' => $specialsettings, 'reallydocroot' => 'reallydocroot' );
							if ( isset($_POST['reallydoit']) )
							{
								$params['reallydoit'] = 'reallydoit';
							}
							ask_yesno('admin_domain_reallydocrootoutofcustomerroot', $filename, $params );
							exit;
						}

						$specialsettings = addslashes($specialsettings);
						$db->query("INSERT INTO `".TABLE_PANEL_DOMAINS."` (`domain`, `customerid`, `adminid`, `documentroot`, `ipandport`, `aliasdomain`, `zonefile`, `isbinddomain`, `isemaildomain`, `subcanemaildomain`, `caneditdomain`, `openbasedir`, `safemode`, `speciallogfile`, `specialsettings`) VALUES ('$domain', '$customerid', '{$userinfo['adminid']}', '$documentroot', '$ipandport', ".(($aliasdomain!=0) ? '\''.$aliasdomain.'\'' : 'NULL').", '$zonefile', '$isbinddomain', '$isemaildomain', '$subcanemaildomain', '$caneditdomain', '$openbasedir', '$safemode', '$speciallogfile', '$specialsettings')");
						$domainid=$db->insert_id();
						$db->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` + 1 WHERE `adminid` = '{$userinfo['adminid']}'");

						inserttask('1');
						inserttask('4');

    					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					$customers='';
					$result_customers=$db->query("SELECT `customerid`, `loginname`, `name`, `firstname`, `company` FROM `".TABLE_PANEL_CUSTOMERS."` ".( $userinfo['customers_see_all'] ? '' : " WHERE `adminid` = '{$userinfo['adminid']}' ")." ORDER BY `name` ASC");
					while($row_customer=$db->fetch_array($result_customers))
					{
						if ($row_customer['company'] == '')
						{
							$customers.=makeoption($row_customer['name'].', '.$row_customer['firstname'].' ('.$row_customer['loginname'].')',$row_customer['customerid']);
						}
						else
						{
							if($row_customer['name'] != '' && $row_customer['firstname'] != '')
							{
								$customers.=makeoption($row_customer['name'].', '.$row_customer['firstname'].' | '. $row_customer['company'] .' ('.$row_customer['loginname'].')',$row_customer['customerid']);
							}
							else
							{
								$customers.=makeoption($row_customer['company'] .' ('.$row_customer['loginname'].')',$row_customer['customerid']);
							}
						}
					}
					$ipsandports='';
					$result_ipsandports=$db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");
					while($row_ipandport=$db->fetch_array($result_ipsandports))
					{
						$ipsandports.=makeoption($row_ipandport['ip'].':'.$row_ipandport['port'],$row_ipandport['id'],$settings['system']['defaultip']);
					}
					$standardsubdomains=array();
					$result_standardsubdomains=$db->query('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`, `'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`id`=`c`.`standardsubdomain`');
					while($row_standardsubdomain=$db->fetch_array($result_standardsubdomains))
					{
						$standardsubdomains[]=$row_standardsubdomain['id'];
					}
					if(count($standardsubdomains)>0)
					{
						$standardsubdomains='AND `d`.`id` NOT IN ('.join(',',$standardsubdomains).') ';
					}
					else
					{
						$standardsubdomains='';
					}
					$domains=makeoption($lng['domains']['noaliasdomain'],0);
					$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `c`.`loginname` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 ".$standardsubdomains.( $userinfo['customers_see_all'] ? '' : "AND `d`.`adminid` = '{$userinfo['adminid']}' ")."AND `d`.`customerid`=`c`.`customerid` ORDER BY `loginname`, `domain` ASC");
					while($row_domain=$db->fetch_array($result_domains))
					{
						$domains.=makeoption($idna_convert->decode($row_domain['domain']).' ('.$row_domain['loginname'].')',$row_domain['id']);
					}
					$isbinddomain=makeyesno('isbinddomain', '1', '0', '1');
					$isemaildomain=makeyesno('isemaildomain', '1', '0', '1');
					$subcanemaildomain=makeyesno('subcanemaildomain', '1', '0', '0');
					$caneditdomain=makeyesno('caneditdomain', '1', '0', '1');
					$openbasedir=makeyesno('openbasedir', '1', '0', '1');
					$safemode=makeyesno('safemode', '1', '0', '1');
					$speciallogfile=makeyesno('speciallogfile', '1', '0', '0');

					eval("echo \"".getTemplate("domains/domains_add")."\";");
				}
			}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first(
				"SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`ipandport`, `d`.`aliasdomain`, `d`.`isbinddomain`, `d`.`isemaildomain`, `d`.`subcanemaildomain`, `d`.`caneditdomain`, `d`.`zonefile`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `c`.`loginname`, `c`.`name`, `c`.`firstname`, `c`.`company` " .
				"FROM `".TABLE_PANEL_DOMAINS."` `d` " .
				"LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) " .
				"WHERE `d`.`parentdomainid`='0' AND `d`.`id`='$id'".( $userinfo['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$userinfo['adminid']}' ")
			);
			$alias_check=$db->query_first('SELECT COUNT(`id`) AS count FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$result['id'].'\'');
			$alias_check=$alias_check['count'];
			if($result['domain']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$customer=$db->query_first("SELECT `documentroot` FROM ".TABLE_PANEL_CUSTOMERS." WHERE `customerid`='".$result['customerid']."'");

					$aliasdomain = intval($_POST['alias']);
					$isemaildomain = intval($_POST['isemaildomain']);
					$subcanemaildomain = intval($_POST['subcanemaildomain']);
					$caneditdomain = intval($_POST['caneditdomain']);
					if($userinfo['change_serversettings'] == '1')
					{
						$isbinddomain = $_POST['isbinddomain'];
						$zonefile = addslashes($_POST['zonefile']);
						$openbasedir = intval($_POST['openbasedir']);
						$safemode = intval($_POST['safemode']);
						$specialsettings = str_replace("\r\n", "\n", $_POST['specialsettings']);

						$ipandport = intval($_POST['ipandport']);
						$ipandport_check = $db->query_first( "SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id` = '".$ipandport."' " );
						if( !isset( $ipandport_check['id'] ) || $ipandport_check['id'] == '0' )
						{
							$ipandport = $settings['system']['defaultip'];
						}

						$documentroot = addslashes($_POST['documentroot']);
						if($documentroot=='')
						{
							$documentroot=$customer['documentroot'];
						}
					}
					else
					{
						$isbinddomain = $result['isbinddomain'];
						$zonefile = $result['zonefile'];
						$openbasedir = $result['openbasedir'];
						$safemode = $result['safemode'];
						$specialsettings = $result['specialsettings'];
						$ipandport = $result['ipandport'];
						$documentroot = $result['documentroot'];
					}
					if(!preg_match('/^https?\:\/\//', $documentroot))
					{
						$documentroot = makeCorrectDir($documentroot);
					}

					if($openbasedir != '1')
					{
						$openbasedir = '0';
					}
					if($safemode != '1')
					{
						$safemode = '0';
					}
					if($isbinddomain != '1')
					{
						$isbinddomain = '0';
					}
					if($isemaildomain != '1')
					{
						$isemaildomain = '0';
					}
					if($subcanemaildomain != '1')
					{
						$subcanemaildomain = '0';
					}
					if($caneditdomain != '1')
					{
						$caneditdomain = '0';
					}
					
					$aliasdomain_check=array('id' => 0);
					if($aliasdomain!=0)
					{
						$aliasdomain_check = $db->query_first('SELECT `id` FROM `'.TABLE_PANEL_DOMAINS.'` `d`,`'.TABLE_PANEL_CUSTOMERS.'` `c` WHERE `d`.`customerid`=\''.$result['customerid'].'\' AND `d`.`aliasdomain` IS NULL AND `d`.`id`<>`c`.`standardsubdomain` AND `c`.`customerid`=\''.$result['customerid'].'\' AND `d`.`id`=\''.$aliasdomain.'\'');
					}
					if($aliasdomain_check['id']!=$aliasdomain)
					{
						standard_error('domainisaliasorothercustomer');
					}
					
					if(($openbasedir == '0' || $safemode == '0') && (!isset($_POST['reallydoit']) || $_POST['reallydoit'] != 'reallydoit') && $userinfo['change_serversettings'] == '1')
					{
						ask_yesno('admin_domain_reallydisablesecuritysetting', $filename, array( 'id' => $id, 'page' => $page, 'action' => $action, 'documentroot' => $documentroot, 'alias' => $aliasdomain, 'isbinddomain' => $isbinddomain, 'isemaildomain' => $isemaildomain, 'subcanemaildomain' => $subcanemaildomain, 'caneditdomain' => $caneditdomain, 'zonefile' => $zonefile, 'openbasedir' => $openbasedir, 'ipandport' => $ipandport, 'safemode' => $safemode, 'specialsettings' => $specialsettings, 'reallydoit' => 'reallydoit' ));
						exit;
					}
					$pattern = sprintf('^%s', $customer['documentroot']);
					if( !ereg($pattern,$documentroot) 
					    && ( !isset($_POST['reallydocroot'] ) 
					       || $_POST['reallydocroot'] != 'reallydocroot') 
					    && !preg_match('/^https?\:\/\//', $documentroot) )
					{
						$params = array( 'id' => $id, 'page' => $page, 'action' => $action, 'documentroot' => $documentroot, 'alias' => $aliasdomain, 'isbinddomain' => $isbinddomain, 'isemaildomain' => $isemaildomain, 'subcanemaildomain' => $subcanemaildomain, 'caneditdomain' => $caneditdomain, 'zonefile' => $zonefile, 'openbasedir' => $openbasedir, 'ipandport' => $ipandport, 'safemode' => $safemode, 'specialsettings' => $specialsettings, 'reallydocroot' => 'reallydocroot' );
						if ( isset($_POST['reallydoit']) )
						{
							$params['reallydoit'] = 'reallydoit';
						}
						ask_yesno('admin_domain_reallydocrootoutofcustomerroot', $filename, $params );
						exit;
					}

					if($documentroot != $result['documentroot'] || $ipandport != $result['ipandport'] || $openbasedir != $result['openbasedir'] || $safemode != $result['safemode'] || $specialsettings != $result['specialsettings'])
					{
						inserttask('1');
					}
					if($isbinddomain != $result['isbinddomain'] || $zonefile != $result['zonefile'] || $ipandport != $result['ipandport'])
					{
						inserttask('4');
					}
					if($isemaildomain == '0' && $result['isemaildomain'] == '1')
					{
						$db->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `domainid`='$id' ");
						$db->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `domainid`='$id' ");
					}

					$specialsettings = addslashes($specialsettings);
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `documentroot`='$documentroot', `ipandport`='$ipandport', `aliasdomain`=".(($aliasdomain!=0 && $alias_check==0) ? '\''.$aliasdomain.'\'' : 'NULL').", `isbinddomain`='$isbinddomain', `isemaildomain`='$isemaildomain', `subcanemaildomain`='$subcanemaildomain', `caneditdomain`='$caneditdomain', `zonefile`='$zonefile', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings' WHERE `id`='$id'");
					$result=$db->query("UPDATE `".TABLE_PANEL_DOMAINS."` SET `ipandport`='$ipandport', `openbasedir`='$openbasedir', `safemode`='$safemode', `specialsettings`='$specialsettings'  WHERE `parentdomainid`='$id'");
	
					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
				else
				{
					$result['domain'] = $idna_convert->decode($result['domain']);
					$domains=makeoption($lng['domains']['noaliasdomain'],0,$result['aliasdomain']);
					$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`aliasdomain` IS NULL AND `d`.`parentdomainid`=0 AND `d`.`id`<>'".$result['id']."' AND `c`.`standardsubdomain`<>`d`.`id` AND `d`.`customerid`='".$result['customerid']."' AND `c`.`customerid`=`d`.`customerid` ORDER BY `d`.`domain` ASC");
					while($row_domain=$db->fetch_array($result_domains))
					{
						$domains.=makeoption($idna_convert->decode($row_domain['domain']),$row_domain['id'],$result['aliasdomain']);
					}
					$ipsandports='';
					$result_ipsandports=$db->query("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");
					while($row_ipandport=$db->fetch_array($result_ipsandports))
					{
						$ipsandports.=makeoption($row_ipandport['ip'].':'.$row_ipandport['port'],$row_ipandport['id'],$result['ipandport']);
					}
					$result['specialsettings'] = stripslashes($result['specialsettings']);
					$isbinddomain=makeyesno('isbinddomain', '1', '0', $result['isbinddomain']);
					$isemaildomain=makeyesno('isemaildomain', '1', '0', $result['isemaildomain']);
					$subcanemaildomain=makeyesno('subcanemaildomain', '1', '0', $result['subcanemaildomain']);
					$caneditdomain=makeyesno('caneditdomain', '1', '0', $result['caneditdomain']);
					$openbasedir=makeyesno('openbasedir', '1', '0', $result['openbasedir']);
					$safemode=makeyesno('safemode', '1', '0', $result['safemode']);
					$speciallogfile=($result['speciallogfile'] == 1 ? $lng['panel']['yes'] : $lng['panel']['no']);

					$result = htmlentities_array( $result );
					eval("echo \"".getTemplate("domains/domains_edit")."\";");
				}
			}
		}
	}

?>
