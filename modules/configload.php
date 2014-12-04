<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
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
 *  $Id$
 */

$SESSION->restore('conls', $section);

function parse_cfg_val($value)
{
	if (is_bool($value))
		return $value ? 'true' : 'false';
	else
		return (string) $value;
}

$DB->BeginTrans();

if(!empty($CONFIG['phpui']) && (!$section || $section == 'phpui'))
foreach($CONFIG['phpui'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('phpui', $key, parse_cfg_val($val))
			);
}

if(isset($CONFIG['userpanel']))
{
	// it's possible that userpanel config is in database yet
	$DB->Execute('DELETE FROM uiconfig WHERE section = \'userpanel\'');

	foreach($CONFIG['userpanel'] as $key => $val)
	{
		$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('userpanel', $key, parse_cfg_val($val))
			);
	}
}

/*
foreach($CONFIG['directories'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('directories', $key, $val)
			);
}
*/

if(!empty($CONFIG['invoices']) && (!$section || $section == 'invoices'))
foreach($CONFIG['invoices'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('invoices', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['notes']) && (!$section || $section == 'notes'))
foreach($CONFIG['notes'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('notes', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['receipts']) && (!$section || $section == 'receipts'))
foreach($CONFIG['receipts'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('receipts', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['finances']) && (!$section || $section == 'finances'))
foreach($CONFIG['finances'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('finances', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['sms']) && (!$section || $section == 'sms'))
foreach($CONFIG['sms'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('sms', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['mail']) && (!$section || $section == 'mail'))
foreach($CONFIG['mail'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('mail', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['zones']) && (!$section || $section == 'zones'))
foreach($CONFIG['zones'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('zones', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['gadugadu']) && (!$section || $section == 'gadugadu'))
foreach($CONFIG['gadugadu'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('gadugadu', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['monit']) && (!$section || $section == 'monit'))
foreach($CONFIG['monit'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('monit', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['hiperus_c5']) && (!$section || $section == 'hiperus_c5'))
foreach($CONFIG['hiperus_c5'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('hiperus_c5', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['autobackup']) && (!$section || $section == 'autobackup'))
foreach($CONFIG['autobackup'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('autobackup', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['homepage']) && (!$section || $section == 'homepage'))
foreach($CONFIG['homepage'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('homepage', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['netdevices']) && (!$section || $section == 'netdevices'))
foreach($CONFIG['netdevices'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('netdevices', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['radius']) && (!$section || $section == 'radius'))
foreach($CONFIG['radius'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('radius', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['voip']) && (!$section || $section == 'voip'))
foreach($CONFIG['voip'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('voip', $key, parse_cfg_val($val))
			);
}

if(!empty($CONFIG['jambox']) && (!$section || $section == 'jambox'))
foreach($CONFIG['jambox'] as $key => $val)
{
	$DB->Execute('INSERT INTO uiconfig(section, var, value) VALUES(?,?,?)',
			array('jambox', $key, parse_cfg_val($val))
			);
}



$DB->CommitTrans();

header('Location: ?m=configlist');

?>
