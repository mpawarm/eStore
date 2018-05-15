<?php
class Product_model extends CI_Model {

	function getAll()
	{
		$query = $this->db->get('products');
		return $query->result('Product');
	}

	function get($id)
	{
		$query = $this->db->get_where('products',array('id' => $id));

		return $query->row(0,'Product');
	}

	function delete($id) {
		return $this->db->delete("products",array('id' => $id ));
	}

	function insert($products) {
		return $this->db->insert("products", array('name' => $products->name,
				                                  'description' => $products->description,
											      'price' => $products->price,
												  'photo_url' => $products->photo_url));
	}

	function update($products) {
		$this->db->where('id', $products->id);
		return $this->db->update("products", array('name' => $products->name,
				                                  'description' => $products->description,
											      'price' => $products->price));
	}

}

?>