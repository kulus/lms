<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2001-2012 LMS Developers
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 */

$DB->BeginTrans();

$DB->Execute("DROP VIEW IF EXISTS customersview;");
$DB->Execute("DROP VIEW IF EXISTS contractorview;");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'customers','origin'))) 
	$DB->Execute("ALTER TABLE customers ADD origin INT (11) NOT NULL DEFAULT 0;");
// add dla rozwiązanego klienta, czyli rozwiązanie umowy, status=4

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'customers','ctying'))) 
	$DB->Execute("ALTER TABLE customers ADD ctying INT (11) NOT NULL DEFAULT 0;"); 	// data rozwiąznia umowy

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'customers','dtying'))) 
	$DB->Execute("ALTER TABLE customers ADD dtying TEXT DEFAULT NULL;");		// przyczyna/opis rozwiązania umowy

$DB->Execute("
CREATE TABLE IF NOT EXISTS customerorigin (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(64) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
    description text COLLATE utf8_polish_ci,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;
");

$DB->Execute("
CREATE OR REPLACE VIEW customersview AS
SELECT c.* FROM customers c
WHERE NOT EXISTS (
SELECT 1 FROM customerassignments a
JOIN excludedgroups e ON (a.customergroupid = e.customergroupid)
WHERE e.userid = lms_current_user() AND a.customerid = c.id) 
AND c.type IN ('0','1');
");

$DB->Execute("
CREATE OR REPLACE VIEW contractorview AS
SELECT c.* FROM customers c
WHERE c.type = '2';
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013021202', 'dbvex'));

$DB->CommitTrans();
?>
