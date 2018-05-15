<?php
class Order_model extends CI_Model {

	function getAll()
	{
		$query = $this->db->get('`orders`');
		return $query->result('Order');
	}

	function get($id)
	{
		$query = $this->db->get_where('`orders`',array('id' => $id));

		return $query->row(0,'Order');
	}

	function delete($id) {
		return $this->db->delete("`orders`",array('id' => $id ));
	}

	function deleteAll() {
		return $this->db->empty_table('`orders`');
	}

	function insert($orders) {
		$this->db->insert("`orders`", array('customer_id' => $orders->customer_id,
				                                 	   'order_date' => $orders->order_date,
				                                 	   'order_time' => $orders->order_time,
				                                 	   'total' => $orders->total,
				                                 	   'creditcard_number' => $orders->creditcard_number,
				                                 	   'creditcard_month' => $orders->creditcard_month,
				                                 	   'creditcard_year' => $orders->creditcard_year));
		return $this->db->insert_id();
	}

}
