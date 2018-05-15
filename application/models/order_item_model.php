<?php
class Order_Item_model extends CI_Model {

	function getAll()
	{
		$query = $this->db->get('order_items');
		return $query->result('Order_Item');
	}

	function get($id)
	{
		$query = $this->db->get_where('order_items',array('id' => $id));

		return $query->row(0,'Order_Item');
	}

	function delete($id) {
		return $this->db->delete("order_items",array('id' => $id ));
	}

	function insert($order_items) {
		return $this->db->insert("order_items", array('order_id' => $order_items->order_id,
							           'product_id' => $order_items->product_id,
							           'quantity' => $order_items->quantity));
	}

}
