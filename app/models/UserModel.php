<?php 

// Contiene los modelos para interactuar con la base de datos y manejar la lógica de negocio.

defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel
{
	protected $db, $schema;

	public function __construct()
	{
		$this->db = new Database;
	}

	public function pruebaModel()
	{
		$sql = 'SELECT * FROM sucursales';
        $this->db->query($sql);
        $query = $this->db->result();

        if ($this->db->affectedRows() > 0)
        {
            return $query;
        }
        else
        {
            return false;
        }
	}
}