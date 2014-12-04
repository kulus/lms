<?php

/*
 * LMS version Expanded
 *
 *  (C) Copyright 2012 LMS-EX Developers
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

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'documents','sale'))) 
    $DB->Execute("ALTER TABLE documents ADD sale tinyint(1) DEFAULT 1 NOT NULL;");


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = ? AND TABLE_NAME = ? AND INDEX_NAME = ? ;",array($DB->_dbname,'documents','sale'))) 
    $DB->Execute("CREATE INDEX sale ON documents (sale);");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012120700', 'dbvex'));

$DB->CommitTrans();

?>