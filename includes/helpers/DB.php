<?php

namespace Plato;

/**
 * The DB plugin class.
 * Handles DB access (by default table 'plato_signatures') and en-/decryption
 *
 * @since      1.0.0
 * @package    Plato
 * @subpackage Plato/includes/helpers
 * @author     Fabian Horlacher
 */
class DB
{
	const TABLE_SIGN = 1;
	const TABLE_MAIL = 2;
	private static $tableNameSignatures = 'plato_signatures';
	private static $tableNameMails = 'plato_mails';
	private static $fieldNameEncrypted = 'is_encrypted';
	/**
	 * Before adding field here, check if it is set with self::update(). If yes, $isEncrypted has to be passed
	 * @var array
	 */
	private static $encryptFields = [
		'first_name',
		'last_name',
		'birth_date',
		'mail',
		'phone',
		'street',
		'street_no',
		'zip',
		'city',
		'gde_no',
		'gde_zip',
		'gde_name',
		'gde_canton',
		'ip_address',
	];

	/**
	 * Select one row from plato table
	 *
	 * @param array $select Fields to select
	 * @param string|null $where SQL where statement
	 * @param null|int $table
	 * @param string|null $sqlAppend Append SQL statements
	 * @return object|null Database query results
	 */
	public static function getRow($select, $where = null, $table = null, $sqlAppend = null)
	{
		global $wpdb;

		$sql = self::prepareSelect($select, $table);
		if ($where) {
			$sql .= " WHERE " . $where;
		}
		if ($sqlAppend) {
			$sql .= ' ' . $sqlAppend;
		}
		$row = $wpdb->get_row($sql);

		return $row;
	}

	/**
	 * Select multiple rows from plato table
	 *
	 * @param array $select Fields to select
	 * @param string|null $where SQL where statement
	 * @param null|int $table
	 * @param string|null $sqlAppend Append SQL statements
	 * @return array|object|null Database query results
	 */
	public static function getResults($select, $where = null, $table = null, $sqlAppend = null)
	{
		global $wpdb;
		$sql = self::prepareSelect($select, $table);
		if ($where) {
			$sql .= ' WHERE ' . $where;
		}
		if ($sqlAppend) {
			$sql .= ' ' . $sqlAppend;
		}
		$results = $wpdb->get_results($sql);
		return $results;
	}

	/**
	 * Count results for a where statement
	 *
	 * @param null|string $where
	 * @param null|int $table
	 * @return int
	 */
	public static function count($where = null, $table = null)
	{
		global $wpdb;
		$tableName = self::getTableName($table);
		if ($where !== null) {
			$where = 'WHERE ' . $where;
		}
		$count = $wpdb->get_var('SELECT COUNT(ID) as count FROM `' . $tableName . '`' . $where);
		return intval($count);
	}

	/**
	 * Delete entries for a where statement
	 *
	 * @param null|array $where
	 * @param null|int $table
	 * @return int|false The number of rows updated, or false on error.
	 */
	public static function delete($where = null, $table = null)
	{
		global $wpdb;
		$tableName = self::getTableName($table);
		if ($tableName === self::$tableNameSignatures) {
			return self::updateStatus(['is_deleted' => 1], $where, $table);
		} else {
			return $wpdb->delete($tableName, $where);
		}
	}

	/**
	 * @param $data
	 * @param null|int $table
	 * @return false|int
	 */
	public static function insert($data, $table = null)
	{
		global $wpdb;
		return $wpdb->insert(
			self::getTableName($table),
			$data
		);
	}

	/**
	 * @param array $data
	 * @param array $where
	 * @param false|int $isEncrypted
	 * @param null|int $table
	 * @return false|int
	 */
	public static function update($data, $where, $isEncrypted, $table = null)
	{
		global $wpdb;
		return $wpdb->update(
			self::getTableName($table),
			$data,
			$where
		);
	}

	/**
	 * Update status fields which don't require encryption
	 * @param array $data
	 * @param array $where
	 * @param null|int $table
	 * @return false|int
	 */
	public static function updateStatus($data, $where, $table = null)
	{
		return self::update($data, $where, false, $table);
	}

	/**
	 * @return int
	 */
	public static function getInsertId()
	{
		global $wpdb;
		return $wpdb->insert_id;
	}

	/**
	 * @return string
	 */
	public static function getError()
	{
		global $wpdb;
		return $wpdb->last_error;
	}

	/**
	 * @param string $tableDefinition Has to be exactly in the undocumented wordpress internal format or it will most likely fail in a random way
	 *        Never quote field names
	 *        "It is always safe to ensure that all keyword are separated by one space and between each commas there shouldn't be any spacing"
	 *        https://www.hungred.com/how-to/wordpress-dbdelta-function/
	 * @param null|int $table
	 * @return array
	 */
	static function createUpdateTable($tableDefinition, $table = null)
	{
		global $wpdb;
		$charsetCollate = $wpdb->get_charset_collate();
		$sql = 'CREATE TABLE ' . self::getTableName($table)
			. ' (' . $tableDefinition . ') '
			. $charsetCollate . ';';

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$dbResult = dbDelta($sql);
		return $dbResult;
	}

	/**
	 * @return bool|false|int
	 */
	public static function dropTables()
	{
		global $wpdb;

		$tableName = self::getTableName();
		$drop = $wpdb->query("DROP TABLE IF EXISTS `{$tableName}`");

		$tableNameMail = self::getTableName(self::TABLE_MAIL);
		$dropMail = $wpdb->query("DROP TABLE IF EXISTS `{$tableNameMail}`");

		return $drop && $dropMail;
	}

	/**
	 * @param null|int $table
	 * @return string
	 */
	public static function getTableName($table = null)
	{
		global $wpdb;
		if ($table === self::TABLE_MAIL) {
			$name = self::$tableNameMails;
		} else {
			$name = self::$tableNameSignatures;
		}
		return $wpdb->prefix . $name;
	}

	/**
	 * add "is_encrypted" if required and convert to string
	 *
	 * @param array $select
	 * @param null|int $table
	 * @return string
	 */
	protected static function prepareSelect($select, $table = null)
	{
		$tableName = self::getTableName($table);
		$select = implode(', ', $select);
		$sql = "SELECT " . $select . " FROM " . $tableName;

		return $sql;
	}
}