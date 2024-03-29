<?php
/* --------------------------------------------------------------
   $Id: backup.php 15005 2023-02-16 20:43:46Z Tomcraft $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(backup.php,v 1.21 2002/06/15); www.oscommerce.com 
   (c) 2003	 nextcommerce (backup.php,v 1.4 2003/08/14); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

define('HEADING_TITLE', 'Datenbanksicherung'); 

define('TABLE_HEADING_TITLE', 'Titel');
define('TABLE_HEADING_FILE_DATE', 'Datum');
define('TABLE_HEADING_FILE_SIZE', 'Gr&ouml;&szlig;e');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Neue Sicherung');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Lokal wiederherstellen');
define('TEXT_INFO_NEW_BACKUP', 'Bitte den Sicherungsprozess AUF KEINEN FALL unterbrechen. Dieser kann einige Minuten in Anspruch nehmen.');
define('TEXT_INFO_UNPACK', '<br /><br />(nach dem die Dateien aus dem Archiv extrahiert wurden)');
define('TEXT_INFO_RESTORE', 'Den Wiederherstellungsprozess AUF KEINEN FALL unterbrechen.<br /><br />Je gr&ouml;&szlig;er die Sicherungsdatei - desto l&auml;nger dauert die Wiederherstellung!<br /><br />Bitte wenn m&ouml;glich den mysql client benutzen.<br /><br />Beispiel:<br /><br /><b>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </b> %s');
define('TEXT_INFO_RESTORE_LOCAL', 'Den Wiederherstellungsprozess AUF KEINEN FALL unterbrechen.<br /><br />Je gr&ouml;&szlig;er die Sicherungsdatei - desto l&auml;nger dauert die Wiederherstellung!');
define('TEXT_INFO_RESTORE_LOCAL_RAW_FILE', 'Die Datei, welche hochgeladen wird muss eine sog. raw sql Datei sein (nur Text).');
define('TEXT_INFO_DATE', 'Datum:');
define('TEXT_INFO_SIZE', 'Gr&ouml;&szlig;e:');
define('TEXT_INFO_COMPRESSION', 'Komprimieren:');
define('TEXT_INFO_USE_GZIP', 'Mit GZIP');
define('TEXT_INFO_USE_ZIP', 'Mit ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Keine Komprimierung (Raw SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Nur herunterladen (nicht auf dem Server speichern)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Sichere HTTPS Verbindung verwenden!');
define('TEXT_NO_EXTENSION', 'Keine');
define('TEXT_BACKUP_DIRECTORY', 'Sicherungsverzeichnis:');
define('TEXT_LAST_RESTORATION', 'Letzte Wiederherstellung:');
define('TEXT_FORGET', '(<u>vergessen</u>)');
define('TEXT_DELETE_INTRO', 'Sind Sie sicher, dass Sie diese Sicherung l&ouml;schen m&ouml;chten?');

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Sicherungsverzeichnis ist nicht vorhanden.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Sicherungsverzeichnis ist schreibgesch&uuml;tzt.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Fehler: Download Link nicht akzeptabel.');

define('SUCCESS_LAST_RESTORE_CLEARED', 'Erfolg: Das letzte Wiederherstellungsdatum wurde gel&ouml;scht.');
define('SUCCESS_DATABASE_SAVED', 'Erfolg: Die Datenbank wurde gesichert.');
define('SUCCESS_DATABASE_RESTORED', 'Erfolg: Die Datenbank wurde wiederhergestellt.');
define('SUCCESS_BACKUP_DELETED', 'Erfolg: Die Sicherungsdatei wurde gel&ouml;scht.');
define('SUCCESS_BACKUP_UPLOAD', 'Erfolgreich: Die Backupdatei wurde erfolgreich hochgeladen.');

//TEXT_COMPLETE_INSERTS
define('TEXT_COMPLETE_INSERTS', "<b>Vollst&auml;ndige 'INSERT's</b><br> - Feldnamen werden in jede INSERT-Zeile eingetragen (vergr&ouml;&szlig;ert das Backup)");

define('TEXT_INFO_TABLES_IN_BACKUP', '<br />' . "\n" .'<b>Tabellen in diesem Backup:</b>' . "\n");
define('TEXT_INFO_NO_INFORMATION', 'Kein Informationen vorhanden');
//UTF-8 convert
define('TEXT_CONVERT_TO_UTF', 'Datenbank auf UTF-8 konvertieren');
define('TEXT_IMPORT_UTF', 'UTF-8 Datenbank wiederherstellen');

//TEXT_REMOVE_COLLATE
define('TEXT_REMOVE_COLLATE', "<b>Ohne Zeichenkodierung 'COLLATE' und 'DEFAULT CHARSET'</b><br> - Die Angaben zur Zeichenkodierung werden nicht eingef&uuml;gt. Sinnvoll bei Migration auf eine andere DB-Zeichenkodierung.");

//TEXT_REMOVE_ENGINE
define('TEXT_REMOVE_ENGINE', "<b>Ohne Speicherengines 'ENGINE'</b><br> - Die Angaben zur Speicherengine (MyISAM,InnoDB) werden nicht eingef&uuml;gt.");

define('TEXT_IMPORT_UTF8_NOTICE', '<b>Achtung:</b> die Datenbank wird nach UTF-8 konvertiert.');
define('TEXT_INFO_CHARSET', 'Charset:');

define('TEXT_TABLES_BACKUP_TYPE', '<b>Sicherung</b><br> - Welche Tabellen sollen gesichert werden?');
define('TEXT_BACKUP_ALL', 'Alle Tabellen');
define('TEXT_BACKUP_CUSTOM', 'Ausgew&auml;hlte Tabellen');
define('TEXT_TABLES_TO_BACKUP', '<b>Folgende Tabellen sollen gesichert werden:</b>');
define('TEXT_CHECK_ALL_TABLES', 'Alle Tabellen ausw&auml;hlen');
?>