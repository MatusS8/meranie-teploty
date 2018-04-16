<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 9. 4. 2018
 * Time: 19:08
 */

class Temperatures_model extends CI_Model {

    public function __construct()
    {

    }
    // vrati zoznam teplot
    function getRows($id= "") {
        if(!empty($id)){
            $this->db->select('temperatures.id, measurement_date, temperature, sky, user, CONCAT(firstname," ", lastname) as fullname, description')
                ->join('users','temperatures.user = users.id');
            $query = $this->db->get_where('temperatures', array('temperatures.id' => $id));
            return $query->row_array();
        }else{
            $this->db->select('temperatures.id, measurement_date, temperature, sky, user, CONCAT(firstname," ", lastname) as fullname, description')
                ->join('users','temperatures.user = users.id');
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

    public function get_users_dropdown($id = ""){
        $this->db->order_by('lastname')
            ->select('id, CONCAT(lastname," ", firstname) AS fullname')
            ->from('users');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $dropdowns = $query->result();
            foreach ($dropdowns as $dropdown)
            {
                $dropdownlist[$dropdown->id] = $dropdown->fullname;
            }
            $dropdownlist[''] = 'Select a user ... ';
            return $dropdownlist;
        }
    }

    public function fetch_data($limit,$start) {
        $this->db->limit($limit,$start);
        $query = $this->db->get("temperatures");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function record_count (){
        return $this->db->count_all("temperatures");
    }
}
?>