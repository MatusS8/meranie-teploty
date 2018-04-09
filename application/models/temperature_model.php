<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 9. 4. 2018
 * Time: 19:08
 */
<?php
class Temperature_model extends CI_Model {

    public function __construct()
    {

    }
    // vrati zoznam teplot
    function getRows($id= "") {
        if(!empty($id)){
            $query = $this->db->get_where('temperatures', array('id' => $id));
            return $query->row_array();
        }else{
            $query = $this->db->get('temperatures');
            return $query->result_array();
        }
    }

    // vlozenie zaznamu
    public function insert($data = array()) {
        $insert = $this->db->insert('temperatures', $data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    // aktualizacia zaznamu
    public function update($data, $id) {
        if(!empty($data) && !empty($id)){
            $update = $this->db->update('temperatures', $data, array('id'=>$id));
            return $update?true:false;
        }else{
            return false;
        }
    }

    // odstranenie zaznamu
    public function delete($id){
        $delete = $this->db->delete('temperatures',array('id'=>$id));
        return $delete?true:false;
    }
}
?>