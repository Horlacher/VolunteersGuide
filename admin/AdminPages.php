<?php

namespace VolunteersGuide;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    VolunteersGuide
 * @subpackage VolunteersGuide/admin
 * @author     Fabian Horlacher
 */
class AdminPages
{
	public function pageOverview()
	{
		include Infos::getPluginDir() . 'admin/partials/admin-page.php';
	}
}