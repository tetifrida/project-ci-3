<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class task_categories extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        // Users from a data store e.g. database
        
        $id_categories = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id_categories === NULL)
        {
            $name = $this->db->get("task_categories")->result_array();
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($name)
            {
                // Set the response and exit
                $this->response($name, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.
        else {
            $id_categories = (int) $id_categories;

            // Validate the id.
            if ($id_categories <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the user from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $this->db->where(array("id"=>$id_categories));
            $name = $this->db->get("task_categories")->row_array();

            $this->response($name,REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        // $this->some_model->update_user( ... );
        $data_categories = [
            'name' => $this->post('name')
        ];

        $this->db->insert("task_categories",$data_categories);

        $this->set_response($data_categories, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function index_delete()
    {
        $id_categories = $this->delete('id');

        // $this->some_model->delete_something($id);
        $where = [
            'id' => $id_categories
        ];

        $this->db->delete("task_categories",$where);
        $message=array("status"=>"Data Berhasil dihapus");

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

    public function index_put()
    {
        $where = array(
            "id"=>$this->put("id")
        );

        $data_categories = array(
            "name" => $this->put("name")
        );

        $this->db->update("task_categories",$data_categories,$where);

        $this->set_response($data_categories, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

}