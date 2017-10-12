<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $user = $this->session->userdata('user');
        if (empty($user->id)) {
            $this->session->set_userdata(array("msg" => "login required", "type" => "error"));
            redirect($this->config->base_url() . "login");
        }
        if (!empty($_POST)) {

            if (!empty($_FILES['shop_image']['name'])) {
                $newPath = BASEPATH . "../shop_image";
                if (sizeof($_FILES['shop_image']['name']) > 1) {
                    $_POST['images'] = '';
                    for ($i = 0; $i < (sizeof($_FILES['shop_image']['name']) - 1); $i++) {
                        $fileInfo = pathinfo($_FILES['shop_image']['name'][$i]);
                        $ext = $fileInfo['extension'];
                        $rand = 'SHOP_' . rand(1, 500) . rand(500, 1000) . rand(1, 500);
                        $newfilename = $rand . '.' . $ext;
                        if (move_uploaded_file($_FILES['shop_image']['tmp_name'][$i], $newPath . "/" . $newfilename)) {
                            $img['path'] = "shop_image/" . $rand . '.' . $ext;
                            $img['name'] = $rand . '.' . $ext;
                            $this->db->insert('images', $img);
                            $_POST['images'] .= $this->db->insert_id() . ',';
                        } else {
                            $this->session->set_userdata(array("msg" => "Error Occur: News Images not uploaded", "type" => "error"));
                        }
                    }
                    $_POST['images'] = rtrim($_POST['images'], ',');
                }

                $tmp = explode('-', $_POST['cat_id']);
                $_POST['cat_id'] = end($tmp);
                unset($_POST['shop_image']);
                $sess = $this->session->userdata("user");
                $_POST['user_id'] = $sess->id;

                $address = $_POST['address'];
                unset($_POST['address']);
                $dup = $this->db->get_where("shop", array("user_id" => $sess->id))->row();
                if (!empty($dup->id)) {
                    $this->db->where("user_id", $sess->id);
                    $this->db->update("shop", $_POST);
                    $add_data['shop_id'] = $_POST['id'];
                } else {
                    unset($_POST['id']);
                    $this->db->insert("shop", $_POST);
                    $add_data['shop_id'] = $this->db->insert_id();
                }

                if (!empty($address)) {
                    foreach ($address as $add) {
                        if (!empty($add)) {
                            $add_data['address'] = $add;
                            $cord = $this->getCoordinates($add);
                            $cord = explode(',', $cord);
                            $add_data['lat'] = $cord[0];
                            $add_data['lng'] = $cord[1];
                            $this->db->insert("shop_address", $add_data);
                        }
                    }
                }


                $this->session->set_userdata(array("msg" => "data successfully inserted", "type" => "success"));
                redirect($this->config->base_url() . "shop");
            }
        } else {
            $res = $this->db->get("categories")->result_array();
            $sess = $this->session->userdata("user");
            $shop = $this->db->get_where("shop", array("user_id" => $sess->id))->row();

            if (!empty($shop->images)) {
                $query = $this->db->query("select * from images where id in(" . rtrim($shop->images, ',') . ")");
                $data['images'] = $query->result_array();
            }

            if (!empty($shop->id)) {
                $query = $this->db->query("select * from shop_address where shop_id = $shop->id");
                $data['address'] = $query->result_array();
            }

            if (!empty($shop->cat_id)) {
                $res2 = $this->db->get_where("categories", array("id" => $shop->cat_id))->row();
                $data['sel_cat'] = $res2->cat_name . '-' . $res2->id;
            } else {
                $res['sel_cat'] = '';
            }
            $cat = array();
            foreach ($res as $r) {
                $cat[] = $r['cat_name'] . '-' . $r['id'];
            }
            $data['cat'] = json_encode($cat);
            $data['shop'] = $shop;

            $this->load->view('layout/header');
            $this->load->view('layout/sidebar');
            $this->load->view('admin/shop_detail', $data);
            $this->load->view('layout/footer');
        }
    }

    function getCoordinates($address) {

        $address = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern

        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";

        $response = file_get_contents($url);

        $json = json_decode($response, TRUE); //generate array object from the response from the web

        return ($json['results'][0]['geometry']['location']['lat'] . "," . $json['results'][0]['geometry']['location']['lng']);
        die;
    }

    public function login() {
        if (!empty($_POST)) {
            $error = false;
            empty($_POST['email']) ? $error = true : "";
            empty($_POST['password']) ? $error = true : "";

            if ($error == true) {
                echo 'please fill all the mandatory fields';
                die;
            }

            $res = $this->db->get_where("users", array("email" => $_POST['email'], "password" => $_POST['password']))->row();
            if (!empty($res->email)) {
                unset($res->password);
                $this->session->set_userdata("user", $res);
                echo 'true';
            } else {
                echo 'fail';
            }
        } else {
            $this->load->view("admin/login");
        }
    }

    public function signup() {

        if (!empty($_POST)) {
            $error = false;
            empty($_POST['first_name']) ? $error = true : "";
            empty($_POST['last_name']) ? $error = true : "";
            empty($_POST['email']) ? $error = true : "";
            // empty($_POST['address']) ? $error = true : "";
            empty($_POST['password']) ? $error = true : "";
            empty($_POST['c_password']) ? $error = true : "";
            empty($_POST['phone']) ? $error = true : "";

            if ($error == true) {
                echo 'please fill all the mandatory fields';
                die;
            }

            if ($_POST['password'] != $_POST['c_password']) {
                echo 'password & confirm password are different';
                die;
            }
            unset($_POST['c_password']);

            $res = $this->db->get_where("users", array("email" => $_POST['email']))->row();

            if (!empty($res->email)) {
                echo 'email already in use.';
                die;
            }
//          $coordinates =   $this->getCoordinates($_POST['address']);
//          $coordinates = explode(",", $coordinates);
//          $_POST['lat'] = $coordinates[0];
//          $_POST['lng'] = $coordinates[1];
            $res = $this->db->insert('users', $_POST);
            $id = $this->db->insert_id();
            if (!empty($id)) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            $this->load->view("admin/register");
        }
    }

    public function stories($action = NULL) {
        $user = $this->session->userdata('user');
        if (empty($user->id)) {
            $this->session->set_userdata(array("msg" => "login required", "type" => "error"));
            redirect($this->config->base_url() . "login");
        }

        if ($action == 'view') {
            $this->view_stories($user->id);
        } elseif ($action == 'edit') {
            $this->edit_stories($_POST['id']);
        } elseif ($action == 'delete') {
            $this->delete_stories($_POST['id']);
        } elseif ($action == 'update') {
            $this->update_stories();
        } else {

            if (!empty($_POST)) {

                $data['shop_id'] = $user->id;
                $data['type'] = $_POST['type'];
                if (!empty($_FILES['story_image']['name'])) {
                    $newPath = BASEPATH . "../story_image";
                    if (sizeof($_FILES['story_image']['name']) > 1) {
                        $_POST['images'] = '';
                        for ($i = 0; $i < (sizeof($_FILES['story_image']['name']) - 1); $i++) {
                            $fileInfo = pathinfo($_FILES['story_image']['name'][$i]);
                            $ext = $fileInfo['extension'];
                            $rand = 'STORY_' . rand(1, 500) . rand(500, 1000) . rand(1, 500);
                            $newfilename = $rand . '.' . $ext;
                            if (move_uploaded_file($_FILES['story_image']['tmp_name'][$i], $newPath . "/" . $newfilename)) {
                                $img['path'] = "story_image/" . $rand . '.' . $ext;
                                $img['name'] = $rand . '.' . $ext;
                                $this->db->insert('images', $img);
                                $_POST['images'] .= $this->db->insert_id() . ',';
                            } else {
                                $this->session->set_userdata(array("msg" => "Error Occur: News Images not uploaded", "type" => "error"));
                            }
                        }
                        $data['images'] = rtrim($_POST['images'], ',');
                    }
                    unset($_POST['shop_image']);
                }
                $_POST['type'] == 'offer' ? $data['discount'] = $_POST['discount'] : '';
                if ($_POST['type'] == 'offers') {
                    $data['from'] = $_POST['from'];
                    $data['to'] = $_POST['to'];
                } else {
                    $data['created_date'] = $_POST['created_date'];
                    $data['submission_date'] = $_POST['submission_date'];
                }
                $data['discount'] = $_POST['discount'];
                $this->db->insert("stories", $data);
                if (!empty($this->db->insert_id())) {
                    $this->session->set_userdata(array("msg" => "data successfully inserted", "type" => "success"));
                } else {
                    $this->session->set_userdata(array("msg" => "Error occur: try again later", "type" => "error"));
                }
                redirect($this->config->base_url() . "shop/stories/view");
            } else {
                $data = '';

                $this->load->view('layout/header');
                $this->load->view('layout/sidebar');
                $this->load->view('admin/stories', $data);
                $this->load->view('layout/footer');
            }
        }
    }

    public function delete_stories($id) {
        $this->db->where('id', $id);
        $this->db->delete('stories');
    }

    public function edit_stories($id) {
        $q = $this->db->query("select stories.*,shop.name from stories join shop on stories.shop_id = shop.user_id where stories.id = $id");
        $stories = $q->row_array();
        if (!empty($stories['images'])) {
            $query = $this->db->query("select * from images where id in(" . rtrim($stories['images'], ',') . ")");
            $data['images'] = $query->result_array();
        }
        $data['story'] = $stories;

        $this->load->view('admin/ajax/edit_story', $data);
    }

    public function update_stories() {
        $user = $this->session->userdata('user');
        $data['shop_id'] = $user->id;
        $data['type'] = $_POST['type'];
        if (!empty($_FILES['story_image']['name'])) {
            $newPath = BASEPATH . "../story_image";
            if (sizeof($_FILES['story_image']['name']) > 1) {
                $_POST['images'] = '';
                for ($i = 0; $i < (sizeof($_FILES['story_image']['name']) - 1); $i++) {
                    $fileInfo = pathinfo($_FILES['story_image']['name'][$i]);
                    $ext = $fileInfo['extension'];
                    $rand = 'STORY_' . rand(1, 500) . rand(500, 1000) . rand(1, 500);
                    $newfilename = $rand . '.' . $ext;
                    if (move_uploaded_file($_FILES['story_image']['tmp_name'][$i], $newPath . "/" . $newfilename)) {
                        $img['path'] = "story_image/" . $rand . '.' . $ext;
                        $img['name'] = $rand . '.' . $ext;
                        $this->db->insert('images', $img);
                        $_POST['images'] .= $this->db->insert_id() . ',';
                    } else {
                        $this->session->set_userdata(array("msg" => "Error Occur: News Images not uploaded", "type" => "error"));
                    }
                }
                $data['images'] = rtrim($_POST['images'], ',');
            }
            unset($_POST['shop_image']);
        }
        $_POST['type'] == 'offer' ? $data['discount'] = $_POST['discount'] : '';
        if ($_POST['type'] == 'offers') {
            $data['from'] = $_POST['from'];
            $data['to'] = $_POST['to'];
        } else {
            $data['created_date'] = $_POST['created_date'];
            $data['submission_date'] = $_POST['submission_date'];
        }
        $data['discount'] = $_POST['discount'];

        $this->db->where("id", $_POST['id']);
        $this->db->update("stories", $data);
        if (!empty($this->db->affected_rows() > 0)) {
            $this->session->set_userdata(array("msg" => "data successfully updated", "type" => "success"));
        } else {
            $this->session->set_userdata(array("msg" => "Error occur: try again later", "type" => "error"));
        }
        redirect($this->config->base_url() . "shop/stories/view");
    }

    public function view_stories($id) {
        $q = $this->db->query("select  stories.*,shop.name from stories join shop on stories.shop_id = shop.user_id where stories.shop_id = $id");
        $data['stories'] = $q->result();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/stories_view', $data);
        $this->load->view('layout/footer');
    }

    public function imgdel() {
        $this->db->where('id', $_POST['id']);
        $this->db->delete('images');
    }

    public function adddel() {
        $this->db->where('id', $_POST['id']);
        $this->db->delete('shop_address');
    }

}
