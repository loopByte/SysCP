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
 * @package Language
 * @version $Id$
 */


/**
 * Global
 */
$lng['panel']['edit'] = 'bearbeiten';
$lng['panel']['delete'] = 'l&ouml;schen';
$lng['panel']['create'] = 'anlegen';
$lng['panel']['save'] = 'Speichern';
$lng['panel']['yes'] = 'Ja';
$lng['panel']['no'] = 'Nein';
$lng['panel']['emptyfornochanges'] = 'leer f&uuml;r keine &Auml;nderung';
$lng['panel']['emptyfordefault'] = 'leer f&uuml;r Standardeinstellung';
$lng['panel']['path'] = 'Pfad';

/**
 * Login
 */
$lng['login']['username'] = 'Benutzername';
$lng['login']['password'] = 'Passwort';
$lng['login']['language'] = 'Sprache';
$lng['login']['login'] = 'Anmelden';
$lng['login']['logout'] = 'Abmelden';

/**
 * Customer
 */
$lng['customer']['login'] = 'Benutzername';
$lng['customer']['documentroot'] = 'Heimverzeichnis';
$lng['customer']['name'] = 'Name';
$lng['customer']['surname'] = 'Vorname';
$lng['customer']['company'] = 'Firma';
$lng['customer']['street'] = 'Strasse';
$lng['customer']['zipcode'] = 'PLZ';
$lng['customer']['city'] = 'Ort';
$lng['customer']['phone'] = 'Telephon';
$lng['customer']['fax'] = 'Fax';
$lng['customer']['email'] = 'eMail';
$lng['customer']['customernumber'] = 'KundenNummer';
$lng['customer']['diskspace'] = 'Webspace (MB)';
$lng['customer']['traffic'] = 'Traffic (GB)';
$lng['customer']['mysqls'] = 'MySQL-Datenbanken';
$lng['customer']['emails'] = 'eMail-Adressen';
$lng['customer']['forwarders'] = 'eMail-Weiterleitungen';
$lng['customer']['ftps'] = 'FTP-Accounts';
$lng['customer']['subdomains'] = 'Sub-Domain(s)';
$lng['customer']['domains'] = 'Domain(s)';
$lng['customer']['unlimited'] = 'unendlich';

/**
 * Customermenue
 */
$lng['menue']['main']['main'] = 'Allgemein';
$lng['menue']['main']['changepassword'] = 'Passwort &auml;ndern';
$lng['menue']['email']['email'] = 'eMail';
$lng['menue']['email']['pop'] = 'POP3-Konten';
$lng['menue']['email']['forwarders'] = 'Weiterleitungen';
$lng['menue']['mysql']['mysql'] = 'MySQL';
$lng['menue']['mysql']['databases'] = 'Datenbanken';
$lng['menue']['mysql']['phpmyadmin'] = 'phpMyAdmin';
$lng['menue']['domains']['domains'] = 'Domains';
$lng['menue']['domains']['settings'] = 'Einstellungen';
$lng['menue']['ftp']['ftp'] = 'FTP';
$lng['menue']['ftp']['accounts'] = 'Accounts';
$lng['menue']['extras']['extras'] = 'Extras';
$lng['menue']['extras']['directoryprotection'] = 'Verzeichnisschutz';
$lng['menue']['extras']['pathoptions'] = 'Pfadoptionen';

/**
 * Index
 */
$lng['index']['customerdetails'] = 'Kundendaten';
$lng['index']['accountdetails'] = 'Accountdaten';

/**
 * Change Password
 */
$lng['changepassword']['old_password'] = 'Altes Passwort';
$lng['changepassword']['new_password'] = 'Neues Passwort';
$lng['changepassword']['new_password_confirm'] = 'Neues Passwort (best&auml;tigen)';
$lng['changepassword']['also_change_ftp'] = ' Auch Passwort vom Haupt-FTP-Zugang &auml;ndern';

/**
 * Domains
 */
$lng['domains']['description'] = 'Hier k&ouml;nnen Sie (Sub-)Domains erstellen und deren Pfade &auml;ndern.<br />Nach jeder &Auml;nderung braucht das System etwas Zeit um die Konfiguration neu einzulesen.';
$lng['domains']['domainsettings'] = 'Domaineinstellungen';
$lng['domains']['domainname'] = 'Domainname';
$lng['domains']['subdomain_add'] = 'Subdomain anlegen';
$lng['domains']['subdomain_edit'] = 'Subdomain bearbeiten';

/**
 * eMails
 */
$lng['emails']['description'] = 'Hier k&ouml;nnen Sie Ihre eMail Adressen einrichten.<br />Ein POP-Konto ist wie Ihr Briefkasten vor der Haust&uuml;re. Wenn jemand eine email an Sie schreibt, dann wird diese in dieses POP-Konto gelegt.<br><br>Die Zugangsdaten von Ihrem Mailprogramm sind wie folgt: (Die Angaben in <i>kursiver</i> Schrift sind durch die jeweiligen Eintr&auml;ge zu ersetzen!)<br>Hostname: <b><i>Domainname</i></b><br>Benutzername: <b><i>Kontoname / eMail-Adresse</i></b><br>Passwort: <b><i>das gew&auml;hlte Passwort</i></b>';
$lng['emails']['forwarders_add'] = 'Weiterleitung anlegen';
$lng['emails']['from'] = 'Von';
$lng['emails']['to'] = 'Nach';
$lng['emails']['pop3_add'] = 'POP3-Konto anlegen';
$lng['emails']['emailaddress'] = 'Konto/Adresse';

/**
 * FTP
 */
$lng['ftp']['description'] = 'Hier k&ouml;nnen Sie zus&auml;tzliche FTP-Accounts einrichten.<br />Die &Auml;nderungen sind sofort wirksam und die FTP-Accounts sofort benutzbar.';
$lng['ftp']['account_add'] = 'Account anlegen';

/**
 * MySQL
 */
$lng['mysql']['description'] = 'Hier k&ouml;nnen Sie MySQL-Datenbanken anlegen und l&ouml;schen.<br>Die &Auml;nderungen werden sofort wirksam und die Datenbanken sofort benutzbar.<br>Im Men&uuml; finden Sie einen Link zum phpMyAdmin, mit dem Sie Ihre Datenbankeninhalte einfach bearbeiten k&ouml;nnen.<br><br>Die Zugangsdaten von php-Skripten sind wie folgt: (Die Angaben in <i>kursiver</i> Schrift sind durch die jeweiligen Eintr&auml;ge zu ersetzen!)<br>Hostname: <b>localhost</b><br>Benutzername: <b><i>Datenbankname</i></b><br>Passwort: <b><i>das gew&auml;hlte Passwort</i></b><br>Datenbank: <b><i>Datenbankname';
$lng['mysql']['databasename'] = 'Benutzer-/Datenbankname';
$lng['mysql']['database_create'] = 'Datenbank anlegen';

/**
 * Extras
 */
$lng['extras']['description'] = 'Hier k&ouml;nnen Sie zus&auml;tzliche Extras einrichten, wie zum Beispiel Verzeichnisschutz.<br />Die &Auml;nderungen sind erst nach einer bestimmten Zeit wirksam.';
$lng['extras']['directoryprotection_add'] = 'Verzeichnisschutz anlegen';
$lng['extras']['view_directory'] = 'Verzeichnis anzeigen';
$lng['extras']['pathoptions_add'] = 'Pfadoptionen hinzuf�gen';
$lng['extras']['directory_browsing'] = 'Verzeichnisinhalt anzeigen';
$lng['extras']['pathoptions_edit'] = 'Pfadoptionen bearbeiten';

/**
 * Errors
 */
$lng['error']['error'] = 'Fehlermeldung';
$lng['error']['directorymustexist'] = 'Das Verzeichnis, das Sie eingegeben haben muss existieren. Legen Sie es bitte mit Ihrem FTP-Programm an.';
$lng['error']['domains_cantdeletemaindomain'] = 'Sie k&ouml;nnen keine Domain, die als eMail-Domain verwendet wird l&ouml;schen. ';
$lng['error']['ftp_cantdeletemainaccount'] = 'Sie k&ouml;nnen Ihren Hauptaccount nicht l&ouml;schen.';
$lng['error']['login'] = 'Der angegebene Benuternamen/Passwort ist falsch.';
$lng['error']['login_blocked'] = 'Dieser Account wurde aufgrund zuvieler Fehlversuche vorr�bergehend geschlossen. <br />Bitte versuchen Sie es in '.$settings['login']['deactivatetime'].' Sekunden erneut.';
$lng['error']['notallreqfieldsorerrors'] = 'Sie haben nicht alle Felder oder ein Feld mit fehlerhaften Angaben ausgef&uuml;llt.';
$lng['error']['oldpasswordnotcorrect'] = 'Das alte Passwort ist nicht korrekt.';
$lng['error']['youcantallocatemorethanyouhave'] = 'Sie k&ouml;nnen nicht mehr Ressource verteilen als Sie noch frei haben.';
$lng['error']['youcantdeletechangemainadmin'] = 'Aus Sicherheitsgr&uuml;nden k&ouml;nnen Sie den Hauptadmin nicht l&ouml;schen oder bearbeiten.';

/**
 * Questions
 */
$lng['question']['question'] = 'Sicherheitsfrage';
$lng['question']['admin_customer_reallydelete'] = 'Wollen Sie diesen Kunden wirklich l&ouml;schen?<br />ACHTUNG! Alle Daten gehen unwiederruflich verloren! Nach dem Vorgang m&uuml;ssen Sie die Daten aus dem Dateisystem noch manuell entfernen.';
$lng['question']['admin_domain_reallydelete'] = 'Wollen Sie diese Domain wirklich l&ouml;schen?';
$lng['question']['admin_domain_reallydisablesecuritysetting'] = 'Wollen Sie diese wichtigen Sicherheitseinstellungen (OpenBasedir und/oder SafeMode) wirklich deaktivieren?';
$lng['question']['admin_admin_reallydelete'] = 'Wollen Sie diesen Admin wirklich l&ouml;schen? Alle Kunden und Domains werden dem Hauptadmin zugeteilt.';
$lng['question']['domains_reallydelete'] = 'Wollen Sie die Domain wirklich l&ouml;schen?';
$lng['question']['email_reallydelete_forwarders'] = 'Wollen Sie die Weiterleitung wirklich l&ouml;schen?';
$lng['question']['email_reallydelete_pop'] = 'Wollen Sie die eMail-Adresse wirklich l&ouml;schen?';
$lng['question']['extras_reallydelete'] = 'Wollen Sie den Verzeichnisschutz wirklich l&ouml;schen?';
$lng['question']['extras_reallydelete_pathoptions'] = 'Wollen Sie die Optionen f�r diesen Pfad wirklich l�schen?';
$lng['question']['ftp_reallydelete'] = 'Wollen Sie den FTP-Account wirklich l&ouml;schen?';
$lng['question']['mysql_reallydelete'] = 'Wollen Sie diese Datenbank wirklich l&ouml;schen? ACHTUNG! Alle Daten gehen unwiederruflich verloren!';

/**
 * Mails
 */
$lng['mails']['pop_success']['mailbody'] = 'Hallo,\n\nihr POP3-Konto $email\nwurde erfolgreich eingerichtet.\n\nDies ist eine automatisch generierte\neMail, bitte antworten Sie nicht auf\ndiese Mitteilung.\n\nIhr SysCP-Team';
$lng['mails']['pop_success']['subject'] = 'POP3-Konto erfolgreich eingerichtet';
$lng['mails']['createcustomer']['mailbody'] = 'Hallo $surname $name,\n\nhier ihre Accountinformationen:\n\nBenutzername: $loginname\nPassword: $password\n\nVielen Dank,\nIhr SysCP-Team';
$lng['mails']['createcustomer']['subject'] = 'Accountinformationen';

/**
 * Admin
 */
$lng['admin']['overview'] = '&Uuml;bersicht';
$lng['admin']['ressourcedetails'] = 'Verbrauchte Ressourcen';
$lng['admin']['systemdetails'] = 'Systemdetails';
$lng['admin']['syscpdetails'] = 'SysCP-Details';
$lng['admin']['installedversion'] = 'Installierte Version';
$lng['admin']['latestversion'] = 'Neueste Version';
$lng['admin']['lookfornewversion']['clickhere'] = 'per Webservice abfragen';
$lng['admin']['lookfornewversion']['error'] = 'Fehler beim Auslesen';
$lng['admin']['customer'] = 'Kunde';
$lng['admin']['customers'] = 'Kunden';
$lng['admin']['customer_add'] = 'Kunden anlegen';
$lng['admin']['customer_edit'] = 'Kunden bearbeiten';
$lng['admin']['domains'] = 'Domains';
$lng['admin']['domain_add'] = 'Domain anlegen';
$lng['admin']['domain_edit'] = 'Domain bearbeiten';
$lng['admin']['admin'] = 'Admin';
$lng['admin']['admins'] = 'Admins';
$lng['admin']['admin_add'] = 'Admin anlegen';
$lng['admin']['admin_edit'] = 'Admin bearbeiten';
$lng['admin']['customers_see_all'] = 'Kann alle Kunden sehen?';
$lng['admin']['domains_see_all'] = 'Kann alle Domains sehen?';
$lng['admin']['change_serversettings'] = 'Kann Servereinstellungen bearbeiten?';
$lng['admin']['serversettings'] = 'Servereinstellungen';
$lng['admin']['stdsubdomain'] = 'Standardsubdomain';
$lng['admin']['stdsubdomain_add'] = 'Standardsubdomain anlegen';
$lng['admin']['deactivated'] = 'Gesperrt';
$lng['admin']['deactivated_user'] = 'Benutzer sperren';
$lng['admin']['sendpassword'] = 'Passwort zusenden';
$lng['admin']['ownvhostsettings'] = 'Eigene vHost-Einstellungen';

/**
 * Serversettings
 */
$lng['serversettings']['session_timeout']['title'] = 'Session Timeout';
$lng['serversettings']['session_timeout']['description'] = 'Wie lange muss ein Benutzer inaktiv sein, damit die Session ung&uuml;tig wird? (Sekunden)';
$lng['serversettings']['catachallkeyword']['title'] = 'Catchall-Keyword';
$lng['serversettings']['catachallkeyword']['description'] = 'Welche eMail-adresse soll automatisch zum Catch-all werden?';
$lng['serversettings']['accountprefix']['title'] = 'Kundenprefix';
$lng['serversettings']['accountprefix']['description'] = 'Welchen Prefix sollen die Kundenaccounts haben?';
$lng['serversettings']['mysqlprefix']['title'] = 'SQL-Prefix';
$lng['serversettings']['mysqlprefix']['description'] = 'Welchen Prefix sollen die MySQL-Accounts haben?';
$lng['serversettings']['ftpprefix']['title'] = 'FTP-Prefix';
$lng['serversettings']['ftpprefix']['description'] = 'Welchen Prefix sollen die FTP-Accounts haben?';
$lng['serversettings']['documentroot_prefix']['title'] = 'Documentdirectory';
$lng['serversettings']['documentroot_prefix']['description'] = 'Wo sollen alle Kunden liegen?';
$lng['serversettings']['logfiles_directory']['title'] = 'Logfilesdirectory';
$lng['serversettings']['logfiles_directory']['description'] = 'Wo sollen alle Logfiles liegen?';
$lng['serversettings']['ipaddress']['title'] = 'IP-Adresse';
$lng['serversettings']['ipaddress']['description'] = 'Welche IP-Adresse hat der Server?';
$lng['serversettings']['hostname']['title'] = 'Hostname';
$lng['serversettings']['hostname']['description'] = 'Welchen Hostname hat der Server?';
$lng['serversettings']['apacheconf_directory']['title'] = 'Apache-Config-Directory';
$lng['serversettings']['apacheconf_directory']['description'] = 'Wo liegen die Apache-Konfigdateien?';
$lng['serversettings']['apachereload_command']['title'] = 'Apache-Reload-Command';
$lng['serversettings']['apachereload_command']['description'] = 'Wie heisst das Skript zum reloaden des Apache?';
$lng['serversettings']['bindconf_directory']['title'] = 'Bind-Config-Directory';
$lng['serversettings']['bindconf_directory']['description'] = 'Wo liegen die Bind-Konfigdateien?';
$lng['serversettings']['bindreload_command']['title'] = 'Bind-Reload-Command';
$lng['serversettings']['bindreload_command']['description'] = 'Wie heisst das Skript zum reloaden des Bind?';
$lng['serversettings']['binddefaultzone']['title'] = 'Bind-Default-Zone';
$lng['serversettings']['binddefaultzone']['description'] = 'Wie hei&szlig;t die Default-Zone f&uuml;r alle Domains?';
$lng['serversettings']['vmail_uid']['title'] = 'Mails-Uid';
$lng['serversettings']['vmail_uid']['description'] = 'Welche UID sollen die Mails haben?';
$lng['serversettings']['vmail_gid']['title'] = 'Mails-Gid';
$lng['serversettings']['vmail_gid']['description'] = 'Welche GID sollen die Mails haben?';
$lng['serversettings']['vmail_homedir']['title'] = 'Mails-Homedir';
$lng['serversettings']['vmail_homedir']['description'] = 'Wo sollen die Mails liegen?';
$lng['serversettings']['phpmyadmin_url']['title'] = 'phpMyAdmin-URL';
$lng['serversettings']['phpmyadmin_url']['description'] = 'Wo liegt der phpMyAdmin?';
$lng['serversettings']['adminmail']['title'] = 'Absenderadresse';
$lng['serversettings']['adminmail']['description'] = 'Wie ist die Absenderadresse f&uuml;r eMails aus dem Panel?';

?>