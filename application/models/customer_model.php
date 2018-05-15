<?php
class Customer_model extends CI_Model {

	function getAll()
	{
		$query = $this->db->get('customers');
		return $query->result('Customer');
	}

	function get($id)
	{
		$query = $this->db->get_where('customers',array('id' => $id));

		return $query->row(0,'Customer');
	}

	function delete($id) {
		return $this->db->delete("customers",array('id' => $id ));
	}

	function deleteAll() {
		return $this->db->empty_table('customers');
	}

	function insert($customers) {
		return $this->db->insert("customers", array('first' => $customers->first,
				                                 	        'last' => $customers->last,
							        'login' => $customers->login,
							        'password' => $customers->password,
							        'email' => $customers->email));
	}

	// To see if a particular customers exists to be able to log em in
	function getLogin($username, $password) {
		$query = $this->db->get_where('customers', array('login' => $username,
								        'password' => $password));
		return $query->row(0, 'Customer');
	}

	// This function would never be used by a customers query.
	function update($customers) {
		$this->db->where('id', $customers->id);
		return $this->db->update("customers", array('first' => $customers->first,
				                                 	        'last' => $customers->last,
							        'login' => $customers->login,
							        'password' => $customers->password,
							        'email' => $customers->email));
	}


}
