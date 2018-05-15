<?php
// starting the session for login functionality
session_start();

class Store extends CI_Controller {


    function __construct() {
    		// Call the Controller constructor
	    	parent::__construct();


	    	$config['upload_path'] = './images/product/';
	    	$config['allowed_types'] = 'gif|jpg|png';
/*	    	$config['max_size'] = '100';
	    	$config['max_width'] = '1024';
	    	$config['max_height'] = '768';
*/
	    	$this->load->library('upload', $config);
	    	$this->load->library('session');
    }


    function index() {
    		$this->load->model('product_model');
    		$products = $this->product_model->getAll();
    		$data['products']=$products;

    		// Would be used to create a pop-up if a customer just signed up
    		$data['signedUp'] = False;

    		if(!$this->session->userdata('cart')) {
    			$this->session->set_userdata('cart', array());
    		}

		if($this->session->userdata('loggedIn')) {
			if(strcmp($this->session->userdata('userId'), 0) == 0)
				$this->load->view('product/homePage_admin.php', $data);
			else
				$this->load->view('product/homePage.php', $data);
		}
		else {
			$this->load->view('product/homePage.php', $data);
		}
    }


    function newForm() {
	    	$this->load->view('product/newForm.php');
    }


	function create() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required|is_unique[products.name]');
		$this->form_validation->set_rules('description','Description','required');
		$this->form_validation->set_rules('price','Price','required');

		$fileUploadSuccess = $this->upload->do_upload();

		if ($this->form_validation->run() == true && $fileUploadSuccess) {
			$this->load->model('product_model');

			$product = new Product();
			$product->name = $this->input->get_post('name');
			$product->description = $this->input->get_post('description');
			$product->price = $this->input->get_post('price');

			$data = $this->upload->data();
			$product->photo_url = $data['file_name'];

			$this->product_model->insert($product);

			//Then we redirect to the index page again
			redirect('store/index', 'refresh');
		}
		else {
			if ( !$fileUploadSuccess) {
				$data['fileerror'] = $this->upload->display_errors();
				$this->load->view('product/newForm.php',$data);
				return;
			}

			$this->load->view('product/newForm.php');
		}
	}


	function signUp() {
		// $this->load->model('product_model');
		$data['signUpError'] = NULL;
		$data['validationErrors'] = array();
		// $this->load->view('login_system/signUp.php', $data);
		$this->load->view('login_system/signUpPage.php', $data);
	}


	function read($id) {
		$this->load->model('product_model');
		$product = $this->product_model->get($id);
		$data['product']=$product;
		$this->load->view('product/read.php',$data);
	}


	function editForm($id) {
		$this->load->model('product_model');
		$product = $this->product_model->get($id);
		$data['product']=$product;
		$this->load->view('product/editForm.php',$data);
	}


	function update($id) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('description','Description','required');
		$this->form_validation->set_rules('price','Price','required');

		if ($this->form_validation->run() == true) {
			$product = new Product();
			$product->id = $id;
			$product->name = $this->input->get_post('name');
			$product->description = $this->input->get_post('description');
			$product->price = $this->input->get_post('price');

			$this->load->model('product_model');
			$this->product_model->update($product);
			//Then we redirect to the index page again
			redirect('store/index', 'refresh');
		}
		else {
			$product = new Product();
			$product->id = $id;
			$product->name = set_value('name');
			$product->description = set_value('description');
			$product->price = set_value('price');
			$data['product']=$product;
			$this->load->view('product/editForm.php',$data);
		}
	}


	function delete($id) {
		$this->load->model('product_model');

		if (isset($id))
			$this->product_model->delete($id);

		//Then we redirect to the index page again
		redirect('store/index', 'refresh');
	}


	// // Function to log in a customer/admin
	function logIn() {
		$this->load->model('customer_model');
		$this->load->model('customer');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');

		if ($this->form_validation->run() == true) {
			$customer = new Customer();
			$customer->login = $this->input->get_post('username');
			$customer->password = $this->input->get_post('password');

			if(strcmp($customer->login, 'admin') == 0 && strcmp($customer->password, 'admin') == 0) {
				$this->session->set_userdata('loggedIn', True);
				$this->session->set_userdata('username', 'Admin');
				$this->session->set_userdata('userId', 0);

				redirect('store/index', 'refresh');

			}
			else {
				// echo $customer->login;
				// echo $customer->password;
				$loggedIn_customer = $this->customer_model->getLogin($customer->login, $customer->password);
				// $loggedIn = $loggedIn_customer->result();

				// Checking if the login was successful
				if($loggedIn_customer) {

					$this->session->set_userdata('loggedIn', True);
					$this->session->set_userdata('username', $loggedIn_customer->first);
					$this->session->set_userdata('userId', $loggedIn_customer->id);

					redirect('store/index', 'refresh');
				}
				else {
					$alert = "<script> alert('Error: Incorrect username or password!'); </script>";
					echo "$alert";

					redirect('store/index', 'refresh');
				}

			}
		}
		else {
			redirect('store/index', 'refresh');
		}
	}

	// // Function to logout a logged in user
	function logOut() {
		$this->session->sess_destroy();
		redirect('store/index', 'refresh');
	}

	// Function to add items to the cart
	function addItem($id) {

		if(array_key_exists($id, $this->session->userdata('cart'))) {
			$data = $this->session->userdata('cart');
			$data[$id]['qty'] = $data[$id]['qty'] + 1;
			$this->session->set_userdata('cart', $data);
		}
		else {
			$this->load->model('product_model');
			$product = $this->product_model->get($id);
			// $_SESSION['cart'][$id] = array('name' => $product->name, 'price' => $product->price, 'qty' => 1);
			$data = $this->session->userdata('cart');
			$data[$id] = array('name' => $product->name, 'price' => $product->price, 'qty' => 1);
			$this->session->set_userdata('cart', $data);
		}
		//Then we redirect to the index page again
		redirect('store/index', 'refresh');

	}

	// // Function to remove items from the cart
	function removeItem($id) {
		if(array_key_exists($id, $this->session->userdata('cart'))) {
			$data = $this->session->userdata('cart');
			$data[$id]['qty'] = $data[$id]['qty'] - 1;
			if($data[$id]['qty'] == 0) {
				unset($data[$id]);
			}
			$this->session->set_userdata('cart', $data);
		}

		//Then we redirect to the index page again
		redirect('store/index', 'refresh');

	}

}

