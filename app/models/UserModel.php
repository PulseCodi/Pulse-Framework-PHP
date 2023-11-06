<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends Database
{
	public function pruebaModel()
	{
		$sql = 'SELECT * FROM sucursales';
        $this->query($sql);
        $query = $this->result();

        if ($this->affectedRows() > 0)
        {
            return $query;
        }
        else
        {
            return false;
        }
	}
}