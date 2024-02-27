<?php
/* -----------------------------------------------------------------------------------------
   $Id: german.php 15609 2023-11-28 13:40:58Z Tomcraft $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
  
  
  define('PHP_DATE_TIME_FORMAT', 'd.m.Y H:i:s');

  // buttons
  define('BUTTON_BACK', 'Zur&uuml;ck');
  define('BUTTON_SUBMIT', 'Best&auml;tigen');
  define('BUTTON_INSTALL', 'Neu installieren');
  define('BUTTON_UPDATE', 'Update');
  define('BUTTON_SHOP', 'Zum Shop');
  define('BUTTON_UPDATE_CONTINUE', 'Update ausf&uuml;hren');
  define('BUTTON_DOWNLOAD_DIFF', 'Download');
  define('BUTTON_TEMPLATE_UPDATE', 'Anleitung');
  define('BUTTON_REQUEST_UPDATE', 'Angebot');

  define('BUTTON_CONFIGURE', '<i class="fas fa-arrow-alt-circle-right"></i>');
  define('BUTTON_SYSTEM_UPDATES', '<i class="fas fa-arrow-alt-circle-right"></i>');
  define('BUTTON_DB_UPDATE', '<i class="fas fa-arrow-alt-circle-right"></i>');
  define('BUTTON_SQL_UPDATE', '<i class="fas fa-list"></i>');
  define('BUTTON_SQL_MANUELL', '<i class="fas fa-code"></i>');
  define('BUTTON_DB_BACKUP', '<i class="fas fa-database"></i>');
  define('BUTTON_DB_RESTORE', '<i class="fas fa-database"></i>');
  define('BUTTON_DELETE_FILES', '<i class="fas fa-arrow-alt-circle-right"></i>');
  define('BUTTON_PAYMENT_INSTALL', '<i class="fas fa-arrow-alt-circle-right"></i>');
  
  // text
  define('TEXT_MODE_UPDATE', 'Update');
  define('TEXT_MODE_INSTALL', 'Neue Installation');
  define('TEXT_MODE_AUTOUPDATE', 'Autoupdate');

  define('TEXT_START_SHOP_HEADING', 'Zur&uuml;ck zum Shop');
  define('TEXT_START_SHOP_TEXT', 'Das Installationsverzeichnis wird aus Sicherheitsgr&uuml;nden automatisch gel&ouml;scht.');
  define('TEXT_START_UPDATE_HEADING', 'Update');
  define('TEXT_START_UPDATE_TEXT', 'Update eines bestehenden modified Shops auf die aktuelle Version.');
  define('TEXT_START_INSTALL_HEADING', 'Neu installieren');
  define('TEXT_START_INSTALL_TEXT', 'Komplette Neuinstallation des modified Shops. Eine bestehende Datenbank wird &uuml;berschrieben.');

  define('TEXT_SQL_SUCCESS', '%s');
  define('TEXT_INFO_DONATIONS_IMG_ALT','Unterst&uuml;tzen Sie dieses Projekt mit Ihrer Spende');
  define('BUTTON_DONATE','<a href="https://www.modified-shop.org/spenden" target="_blank"><img src="https://www.modified-shop.org/images/content/btn_donateCC_LS.png" alt="' . TEXT_INFO_DONATIONS_IMG_ALT . '" border="0" /></a>');
  define('TEXT_START_TITLE', 'Willkommen zur modified eCommerce Shopsoftware Installation');
  define('TEXT_START', 'Die modified eCommerce Shopsoftware ist eine Open-Source e-commerce L&ouml;sung, die st&auml;ndig vom modified eCommerce Shopsoftware Team und einer grossen Gemeinschaft weiterentwickelt wird.<br /> Seine out-of-the-box Installation erlaubt es dem Shop-Besitzer seinen Online-Shop mit einem Minimum an Aufwand und Kosten zu installieren, zu betreiben und zu verwalten.<br /><br />Die modified eCommerce Shopsoftware ist auf jedem System lauff&auml;hig, welches eine PHP Umgebung (ab PHP '.PHP_VERSION_MIN.') und MySQL (ab MySQL 5.0.0) zur Verf&uuml;gung stellt, wie zum Beispiel Linux, Solaris, BSD, und Microsoft Windows.');
  define('TEXT_DONATE', 'Die modified eCommerce Shopsoftware ist ein OpenSource-Projekt &ndash; wir stecken jede Menge Arbeit und Freizeit in dieses Projekt und w&uuml;rden uns daher &uuml;ber eine <b>Spende</b> als kleine Anerkennung freuen.<br /><br />' . BUTTON_DONATE);
  define('TEXT_COPYRIGHT', '<span class="magenta">mod</span><span class="darkgrey">ified</span> eCommerce Shopsoftware &copy; 2009 - '.date('Y'));
  define('TEXT_AUTOUPDATER_HEADING', 'Aktualisierung');
  define('TEXT_AUTOUPDATER_TEMPLATE_HEADING', 'Template Update');
  define('TEXT_AUTOUPDATER_TEMPLATE_INFO', 'F&uuml;r den vollen Funktionsumfang der neuen Shopversion muss das Template noch aktualisiert werden.');
  define('TEXT_AUTOUPDATER_SUPPORT_HEADING', 'Update anfragen');
  define('TEXT_AUTOUPDATER_SUPPORT_INFO', 'Gerne unterst&uuml;tzen wir Sie beim Update Ihres Templates. Fordern Sie einfach ein unverbindliches Angebot bei uns an.');
  define('TEXT_AUTOUPDATER_SUPPORT_ALTERNATIVE', 'Der Support ist gerade nicht erreichbar. Bitte stellen Sie Ihre Anfrage direkt &uuml;ber das&nbsp;<a href="https://www.modified-shop.org/kontakt" target="_blank">Kontaktformular</a>');
  define('TEXT_UPDATER_HEADING', 'Bitte ausw&auml;hlen');
  define('TEXT_UPDATER', 'Willkommen beim Updater der modified eCommerce Shopsoftware.');
  define('TEXT_UPDATE_CONFIG', 'Konfiguration aktualisieren');
  define('TEXT_UPDATE_SYSTEM', 'System Updates');
  define('TEXT_UPDATE_SYSTEM_SUCCESS', 'System Updates wurden erfolgreich ausgef&uuml;hrt');
  define('TEXT_CHECK_UPDATE', 'Update pr&uuml;fen');
  define('TEXT_DO_UPDATE', 'Update ausf&uuml;hren');
  define('TEXT_DELETE_FILES', 'Alte Dateien l&ouml;schen');
  define('TEXT_SQL_NO_UPDATE_FILES', 'Keine SQL Update Dateien verf&uuml;gbar');
  define('TEXT_DELETE_FILES_SUCCESS', 'Alte Dateien erfolgreich gel&ouml;scht');
  define('TEXT_DELETE_FILES_ERROR', 'Folgende Dateien konnten nicht gel&ouml;scht werden:');
  define('TEXT_DELETE_DIR_ERROR', 'Folgende Verzeichnisse konnten nicht gel&ouml;scht werden:');

  define('TEXT_CONFIGURE', 'Konfigurations-Datei (configure.php) aktualisieren');
  define('TEXT_CONFIGURE_DESC', 'Hier k&ouml;nnen Sie die configure.php Datei aktualisieren um sicher zu gehen, dass sie dem aktuelle Stand entspricht.');
  define('TEXT_CONFIGURE_SUCCESS', 'Die Konfigurations-Datei wurde neu geschrieben');
  define('TEXT_CONFIGURE_ERROR', 'Die Konfigurations-Datei konnte nicht geschrieben werden');
  
  define('TEXT_SQL_UPDATE', 'Datenbank Update');
  define('TEXT_SQL_UPDATE_HEADING', 'Datenbank Update');
  define('TEXT_SQL_UPDATE_DESC', 'Hier werden alle notwendigen Update-Dateien aufgef&uuml;hrt, die f&uuml;r Ihre Shopversion notwendig sind.');
  define('TEXT_EXECUTED_SUCCESS', '<b>Erfolgreich ausgef&uuml;hrt:</b>');
  define('TEXT_EXECUTED_ERROR', '<b>Mit Fehlern ausgef&uuml;hrt:</b>');
  
  define('TEXT_SQL_MANUELL', 'Manuelle SQL-Eingabe');
  define('TEXT_SQL_MANUELL_HEADING', 'SQL Befehl eingeben:');
  define('TEXT_SQL_MANUELL_DESC', 'SQL-Befehle m&uuml;ssen mit einem Semikolon ( ; ) abgeschlossen werden!');

  define('TEXT_DB_RESTORE', 'Datenbank Wiederherstellung');
  define('TEXT_DB_RESTORE_DESC', 'Sie k&ouml;nnen hier Ihre Datenbank aus einem vorhandenen Backup wiederherstellen.');
  define('TEXT_INFO_DO_RESTORE', 'Die Datenbank wird wiederhergestellt!');
  define('TEXT_INFO_DO_RESTORE_OK', 'Die Datenbank wurde erfolgreich wiederhergestellt!');
  
  define('TEXT_UPDATER_ORDER', 'Update Schritte');
  define('TEXT_DB_BACKUP_OPTIONS', 'Backup Optionen');
  define('TEXT_DB_BACKUP_TABLES', 'Datenbank-Tabellen');

  define('TEXT_DB_BACKUP', 'Datenbank-Backup');
  define('TEXT_DB_BACKUP_DESC', 'Sie k&ouml;nnen hier Ihre Datenbank sichern.');
  define('TEXT_DB_COMPRESS', 'Backup komprimieren');
  define('TEXT_DB_REMOVE_COLLATE', 'Ohne Zeichenkodierung \'COLLATE\' und \'DEFAULT CHARSET\'');
  define('TEXT_DB_REMOVE_ENGINE', 'Ohne Speicherengines \'ENGINE\'');
  define('TEXT_DB_COMPLETE_INSERTS', 'Vollst&auml;ndige \'INSERT\'s');
  define('TEXT_DB_UFT8_CONVERT', 'Datenbank auf UTF-8 konvertieren');
  define('TEXT_DB_COMPRESS_GZIP', 'Mit GZIP');
  define('TEXT_DB_COMPRESS_RAW', 'Keine Komprimierung (Raw SQL)');
  define('TEXT_DB_SIZE', 'Gr&ouml;&szlig;e');
  define('TEXT_DB_DATE', 'Datum');
  define('TEXT_DB_BACKUP_ALL', 'Alle Tabellen sichern');
  define('TEXT_DB_BACKUP_CUSTOM', 'Ausgew&auml;hlte Tabellen sichern');
  define('TEXT_DB_SELECT_ALL', 'Alle Tabellen ausw&auml;hlen');
  
  define('TEXT_INFO_DO_UPDATE_OK', 'Die Datenbank wurde erfolgreich aktualisiert!');
  define('TEXT_INFO_DO_UPDATE', 'Die Datenbank wird aktualisiert!');

  define('TEXT_INFO_DO_BACKUP_OK', 'Die Datenbank wurde erfolgreich gesichert!');
  define('TEXT_INFO_DO_BACKUP', 'Die Datenbank wird gesichert!');
  define('TEXT_INFO_WAIT', 'Bitte warten!');
  define('TEXT_INFO_FINISH', 'FERTIG!');
  define('TEXT_INFO_UPDATE', 'Datens&auml;tze aktualisiert: ');
  define('TEXT_INFO_RESTORE', 'Tabellen wiederhergestellt: ');
  define('TEXT_INFO_BACKUP', 'Tabellen gesichert: ');
  define('TEXT_INFO_LAST', 'Zuletzt bearbeitet: ');
  define('TEXT_INFO_CALLS', 'Seitenaufrufe: ');
  define('TEXT_INFO_TIME', 'Scriptlaufzeit: ');
  define('TEXT_INFO_ROWS', 'Anzahl Zeilen: ');
  define('TEXT_INFO_FROM', ' von ');
  define('TEXT_INFO_MAX_RELOADS', 'Maximale Seitenreloads wurden erreicht: ');
  define('TEXT_NO_EXTENSION', 'Keine');
  
  define('TEXT_DB_UPDATE', 'Datenbankstruktur Update');
  define('TEXT_DB_UPDATE_DESC', 'Hier k&ouml;nnen Sie die Datenbank Ihrer Shopinstallation auf den aktuellen Stand bringen.');
  define('TEXT_DB_UPDATE_FINISHED', 'DB Update erfolgreich abgesclossen!');
  define('TEXT_FROM', ' von ');
  define('TEXT_YES', 'Ja');
  define('TEXT_NO', 'Nein');
  define('TEXT_DATABASE', 'Datenbank');
  define('TEXT_FILE', 'Datei');
  //define('TEXT_DB_UPDATE_BEFORE', 'Text davor'); // Not used yet
  //define('TEXT_DB_UPDATE_AFTER', 'Text danach'); // Not used yet
  
  define('TEXT_BACKUP_DIFF', 'Backup Diff');
  define('TEXT_BACKUP_SIZE', 'Gr&ouml;&szlig;e');
  define('TEXT_BACKUP_DATE', 'Datum');
  
  define('TEXT_DB_HEADING', 'Angaben zur Datenbank:');
  define('TEXT_DB_SERVER', 'Server:');
  define('TEXT_DB_USERNAME', 'Benutzername:');
  define('TEXT_DB_PASSWORD', 'Passwort:');
  define('TEXT_DB_DATABASE', 'Datenbank:');
  define('TEXT_DB_MYSQL_TYPE', 'Typ:');
  define('TEXT_DB_CHARSET', 'Zeichensatz:');
  define('TEXT_DB_ENGINE', 'Engine:');
  define('TEXT_DB_PCONNECT', 'Persistent:');
  define('TEXT_DB_EXISTS', 'Datenbank existiert bereits');
  define('TEXT_DB_EXISTS_DESC', 'Wenn Sie "Best&auml;tigen" klicken werden alle Tabellen dieser Datenbank &uuml;berschrieben! Wenn Sie dies nicht m&ouml;chten, dann klicken Sie auf "Zur&uuml;ck" und geben eine andere Datenbank an. Andersfalls klicken Sie auf "Best&auml;tigen".');
  define('TEXT_DB_INSTALL', 'Datenbank Installation (Zwingend erforderlich bei Erstinstallation). Bestehende Tabellen werden dabei geleert!');

  define('TEXT_SERVER_HEADING', 'Angaben zum Shop:');
  define('TEXT_SERVER_HTTP_SERVER', 'HTTP:');
  define('TEXT_SERVER_HTTPS_SERVER', 'HTTPS:');
  define('TEXT_SERVER_USE_SSL', 'SSL:');
  define('TEXT_SERVER_SESSION', 'Session:');

  define('TEXT_ADMIN_DIRECTORY_HEADING','Admin Verzeichnis:');
  define('TEXT_ADMIN_DIRECTORY_DESCRIPTION', 'Bitte &auml;ndern Sie aus Sicherheitsgr&uuml;nden den Namen des Admin Verzeichnisses.');
  define('TEXT_ADMIN_DIRECTORY', 'Ein per Zufallsgenerator erzeugter Vorschlag:');

  define('TEXT_ACCOUNT','Der Installer richtet den Admin-Account ein und schreibt noch diverse Daten in die Datenbank.<br />Die angegebenen Daten f&uuml;r <b>Land</b> und <b>PLZ</b> werden f&uuml;r die Versand- und Steuerberechnungen genutzt.');
  define('TEXT_ACCOUNT_HEADING', 'Angaben zum Account:');
  define('TEXT_ACCOUNT_LOGIN_HEADING', 'Angaben zum Login:');
  define('TEXT_ACCOUNT_FIRSTNAME', 'Vorname:');
  define('TEXT_ACCOUNT_LASTNAME', 'Nachname:');
  define('TEXT_ACCOUNT_COMPANY', 'Firma:');
  define('TEXT_ACCOUNT_STREET', 'Stra&szlig;e/Nr.:');
  define('TEXT_ACCOUNT_CODE', 'PLZ:');
  define('TEXT_ACCOUNT_CITY', 'Stadt:');
  define('TEXT_ACCOUNT_COUNTRY', 'Land:');
  define('TEXT_ACCOUNT_EMAIL', 'E-Mail:');
  define('TEXT_ACCOUNT_CONFIRM_EMAIL', 'E-Mail best&auml;tigen:');
  define('TEXT_ACCOUNT_PASSWORD', 'Passwort:');
  define('TEXT_ACCOUNT_CONFIRMATION', 'Passwort best&auml;tigen:');
  define('TEXT_ACCOUNT_PASSWORD_POLICY', 'Ein sicheres Passwort muss mindestens %s Zeichen lang sein und sollte neben Gro&szlig;- und Kleinbuchstaben auch Zahlen sowie Sonderzeichen (au&szlig;er Backslash "\") enthalten.');

  define('TEXT_FINISHED', 'Hier k&ouml;nnen Sie bereits die beliebten Zahlungsweisen von PayPal installieren.');
  define('TEXT_MODULES_INSTALLED', 'Installiert:');
  define('TEXT_MODULES_UNINSTALLED', 'Nicht installiert:');
  define('TEXT_INFO_DO_INSTALL', 'Die Datenbank wird installiert.');
  
  define('TEXT_ERROR_JAVASCRIPT','In ihrem Browser ist Javascript deaktiviert. Sie m&uuml;ssen Javascript aktivieren, um den Installer ausf&uuml;hren zu k&ouml;nnen.');
  define('TEXT_ERROR_PERMISSION_FILES', 'Die folgenden Dateien ben&ouml;tigen Schreibrechte (CHMOD 777):');
  define('TEXT_ERROR_PERMISSION_FOLDER', 'Die folgenden Ordner ben&ouml;tigen Schreibrechte (CHMOD 777):');
  define('TEXT_ERROR_PERMISSION_RFOLDER', 'Folgende Ordner inklusive aller Dateien und Unterordner ben&ouml;tigen rekursive Schreibrechte (CHMOD 777):');
  define('TEXT_ERROR_REQUIREMENTS', 'Voraussetzungen');
  define('TEXT_ERROR_REQUIREMENTS_NAME', 'Name');
  define('TEXT_ERROR_REQUIREMENTS_VERSION', 'Version');
  define('TEXT_ERROR_REQUIREMENTS_MIN', 'Min');
  define('TEXT_ERROR_REQUIREMENTS_MAX', 'Max');
  define('TEXT_ERROR_FTP', 'Rechte per FTP &auml;ndern:');
  define('TEXT_ERROR_FTP_HOST', 'FTP Host:');
  define('TEXT_ERROR_FTP_PORT', 'FTP Port:');
  define('TEXT_ERROR_FTP_PATH', 'FTP Pfad:');
  define('TEXT_ERROR_FTP_USER', 'FTP Benutzer:');
  define('TEXT_ERROR_FTP_PASS', 'FTP Passwort:');
  define('TEXT_ERROR_UNLINK_FILES', 'Folgende Dateien m&uuml;ssen gel&ouml;scht werden:');
  define('TEXT_ERROR_UNLINK_FOLDER', 'Folgende Ordner m&uuml;ssen gel&ouml;scht werden:');
  define('ERROR_AUTOUPDATE', 'Ein Autoupdate ist Aufgrund der notwendigen Voraussetzungen nicht m&ouml;glich.');
  
  // errors
  define('ERROR_DATABASE_CONNECTION', 'Bitte DB Daten pr&uuml;fen');
  define('ERROR_DATABASE_NOT_EMPTY', '<b>ACHTUNG:</b>: Die angegebene Datenbank enth&auml;lt bereits Tabellen!');
  define('ERROR_MODULES_PAYMENT', 'Leider konnten wir diese Zahlart nicht finden...');
  define('ERROR_SQL_UPDATE_NO_FILE', 'Leider konnten wir keine SQL-Update-Datei finden...');
  define('ERROR_FTP_LOGIN_NOT_POSSIBLE', 'FTP-Zugangsdaten fehlerhaft, Host nicht erreichbar');
  define('ERROR_FTP_CHMOD_WAS_NOT_SUCCESSFUL', '&Auml;ndern der Verzeichnisrechte war nicht erfolgreich');
  define('ERROR_FILE_INTEGRITY', 'Es wurden %s Dateien im Vergleich zur orginalen Installation ver&auml;ndert (z.B. durch Modulupdates oder Bugfixes). Sie k&ouml;nnen diese Dateien als Backup herunterladen und das Update fortf&uuml;hren.<br><br><b>ACHTUNG:</b> Beim Update werden ALLE ge&auml;nderten Dateien &uuml;berschrieben!');
  define('ERROR_CREATE_TMP_DIR', 'Das tempor&auml;re Verzeichnis f&uuml;r das Update konnte nicht erstellt werden');
  define('ERROR_INVALID_UPDATE_DOWNLOAD', 'Das Update Paket konnte nicht heruntergeladen werden');

  // warning
  define('WARNING_INVALID_DOMAIN', 'Ihre Shop Domain konnte nicht validiert werden (M&ouml;gliche Ursachen: Fehler beim Format der Domain oder internationalisierte Domainnamen (internationalized domain name, IDN) - Umlautdomain)');

  define('ENTRY_FIRST_NAME_ERROR', 'Ihr Vorname muss aus mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_LAST_NAME_ERROR', 'Ihr Nachname muss aus mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_EMAIL_ADDRESS_ERROR', 'Ihre E-Mail-Adresse muss aus mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Ihre eingegebene E-Mail-Adresse ist fehlerhaft oder bereits registriert.');
  define('ENTRY_EMAIL_ERROR_NOT_MATCHING', 'Ihre E-Mail-Adressen stimmen nicht &uuml;berein.');
  define('ENTRY_STREET_ADDRESS_ERROR', 'Stra&szlig;e/Nr. muss aus mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_POST_CODE_ERROR', 'Ihre Postleitzahl muss aus mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_CITY_ERROR', 'Ort muss aus mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_PASSWORD_ERROR', 'Ihr Passwort muss aus mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen bestehen.');
  define('ENTRY_PASSWORD_ERROR_MIN_LOWER', 'Ihr Passwort muss mindestens %s Kleinbuchstaben enthalten.');
  define('ENTRY_PASSWORD_ERROR_MIN_UPPER', 'Ihr Passwort muss mindestens %s Grossbuchstaben enthalten.');
  define('ENTRY_PASSWORD_ERROR_MIN_NUM', 'Ihr Passwort muss mindestens %s Zahl enthalten.');
  define('ENTRY_PASSWORD_ERROR_MIN_CHAR', 'Ihr Passwort muss mindestens %s Sonderzeichen enthalten.');
  define('ENTRY_PASSWORD_ERROR_INVALID_CHAR', 'Ihr Passwort enht&auml;lt ung&uuml;ltige Zeichen. Bitte verwenden Sie ein anderes Passwort.');
  define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Ihre Passw&ouml;rter stimmen nicht &uuml;berein.');
  define('ENTRY_PASSWORD_CURRENT_ERROR', 'Ihr aktuelles Passwort darf nicht leer sein.');
  
?>