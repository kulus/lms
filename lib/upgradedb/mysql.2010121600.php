<?php

/*
 * LMS version 1.11-git
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

$DB->Execute("DROP VIEW IF EXISTS customersview");
$DB->Execute("DROP VIEW IF EXISTS vnodes");
$DB->Execute("DROP VIEW IF EXISTS vmacs");

$DB->Execute("ALTER TABLE customers ADD post_address varchar(255) DEFAULT NULL");
$DB->Execute("ALTER TABLE customers ADD post_zip varchar(10) DEFAULT NULL");
$DB->Execute("ALTER TABLE customers ADD post_city varchar(32) DEFAULT NULL");
$DB->Execute("ALTER TABLE customers ADD post_countryid integer DEFAULT NULL");
$DB->Execute("ALTER TABLE customers MODIFY countryid int(11) DEFAULT NULL");

$DB->Execute("ALTER TABLE nodes ADD location_address varchar(255) DEFAULT NULL");
$DB->Execute("ALTER TABLE nodes ADD location_zip varchar(10) DEFAULT NULL");
$DB->Execute("ALTER TABLE nodes ADD location_city varchar(32) DEFAULT NULL");

/*
    Here, we'll try to split old address into parts
    We'll handle only one (Polish) format:
    <address>
    <zip> <city>
*/
function parse_address_tmp($addr)
{
    $regexp = '/\n([0-9]{2}-[0-9]{3})\s+(.*)/';
    if (preg_match($regexp, $addr, $matches)) {
        $zip = $matches[1];
        $city = $matches[2];
        $street = trim(preg_replace($regexp, '', $addr));
        $street = trim(array_shift(explode("\n", $street)));

        if ($street)
            return array($zip, $city, $street);
    }
    else {
        // first line only
        $addr = trim(array_shift(explode("\n", $addr)));
        return array(NULL, NULL, $addr);
    }
    return NULL;
}

$data = $DB->GetAll("SELECT id, serviceaddr FROM customers WHERE serviceaddr <> ''");
if (is_array($data)) {
    foreach ($data as $row) {
        $addr = parse_address_tmp($row['serviceaddr']);
        if (!empty($addr)) {
            $DB->Execute('UPDATE customers SET post_address=?, post_zip=?, post_city=?
                    WHERE id=?', array($addr[2], $addr[0], $addr[1], $row['id']));
        }
    }
}

$data = $DB->GetAll("SELECT id, location FROM nodes WHERE location <> ''");
if (is_array($data)) {
    foreach ($data as $row) {
        $addr = parse_address_tmp($row['location']);
        if (!empty($addr)) {
            $DB->Execute('UPDATE nodes SET location_address=?, location_zip=?, location_city=?
                WHERE id=?', array($addr[2], $addr[0], $addr[1], $row['id']));
        }
    }
}

$DB->Execute("ALTER TABLE customers DROP serviceaddr");
$DB->Execute("ALTER TABLE nodes DROP location");

$DB->Execute("CREATE OR REPLACE VIEW customersview AS
    SELECT c.* FROM customers c
    WHERE NOT EXISTS (
        SELECT 1 FROM customerassignments a
        JOIN excludedgroups e ON (a.customergroupid = e.customergroupid)
        WHERE e.userid = lms_current_user() AND a.customerid = c.id)");

$DB->Execute("CREATE OR REPLACE VIEW vnodes AS
	SELECT n.*, m.mac
	FROM nodes n
    LEFT JOIN vnodes_mac m ON (n.id = m.nodeid)");

$DB->Execute("CREATE OR REPLACE VIEW vmacs AS
    SELECT n.*, m.mac, m.id AS macid
    FROM nodes n
    JOIN macs m ON (n.id = m.nodeid)");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2010121600', 'dbversion'));

$DB->CommitTrans();

?>
