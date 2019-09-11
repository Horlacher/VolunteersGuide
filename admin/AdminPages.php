<?php

namespace Plato;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plato
 * @subpackage Plato/admin
 * @author     Fabian Horlacher
 */
class AdminPages
{
	public function pageOverview()
	{
		include Infos::getPluginDir() . 'admin/partials/admin-page.php';
	}
}