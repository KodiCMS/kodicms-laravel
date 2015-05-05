<?php namespace KodiCMS\Installer;

class DatabaseManager extends \Illuminate\Database\DatabaseManager
{
	/**
	 * Get a database connection instance.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Database\Connection
	 */
	public function connection($name = null)
	{
		list($name, $type) = $this->parseConnectionName($name);

		// If we haven't created this connection, we'll create it based on the config
		// provided in the application. Once we've created the connections we will
		// set the "fetch mode" for PDO which determines the query return types.
		if ( ! isset($this->connections[$name]))
		{
			$connection = $this->makeConnection($name);

			$this->setPdoForType($connection, $type);

			$this->connections[$name] = $this->prepare($connection);
		}

		return $this->connections[$name];
	}
}