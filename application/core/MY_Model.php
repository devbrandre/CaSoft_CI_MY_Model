<?php
/**
 * MY_Model.php
 *
 * Model MY_Model.
 *
 * This model is a collection of generic methods to retrieve and save data to the database.
 *
 * @author Evaldo Junior <junior@casoft.info>
 * @author André da Silva Severino <andredasilvaseverino@gmail.com>
 * @url       http://andrewd.com.br
 * @subpackage  core
 * @version 0.1
 *
 * Copyright 2011 CaSoft Tecnologia e Desenvolvimento. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 *  1. Redistributions of source code must retain the above copyright notice, this list of
 *     conditions and the following disclaimer.
 *
 *  2. Redistributions in binary form must reproduce the above copyright notice, this list
 *     of conditions and the following disclaimer in the documentation and/or other materials
 *     provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY CASOFT _AS IS_ AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL CASOFT OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those of the
 * authors and should not be interpreted as representing official policies, either expressed
 * or implied, of CaSoft Tecnologia e Desenvolvimento.'
 */

/**
 * MY_Model
 *
 * @property    CI_DB_active_record     $db
 */
class MY_Model extends CI_Model {

    /**
     * table
     *
     * The database table of the model
     *
     * When extending this class, you should set the table name in the constructor
     *
     * @var string
     * @access protected
     */
    protected $table;

    /**
     * return_type
     *
     * Which type of data methods should return.
     * Options are:
     *   - 'array' (Default)
     *   - 'object'
     *
     * If you want methods to return objects, change this value
     * in your Models' constructors.
     *
     * @var mixed
     * @access protected
     */
    protected $return_type;

    /**
     * constructor method
     */
    public function  __construct() {
        parent::__construct();

        $this->return_type = 'array';
    }


    /**
     * save
     *
     * Method to save data to the database.
     *
     * To update data you must have an 'id' element in the given array.
     *
     * This method returns the row id (inserted or updated)
     *
     * @param array $data
     * @param array assoc $where (db->update)
     * 
     * @access public
     * 
     * @return boolean
     */
    public function save($data, $where = NULL) {
        
        if ( !is_null($where) && is_array($where) && !empty($where) ) {
            
            $this->db->where($where);
            $st = $this->db->update($this->table, $data);
            
        } else {
            $st = $this->db->insert($this->table, $data);
        }
        
        if($st)
            return TRUE;
        else
            return FALSE;
        
    }

    /**
     * get
     *
     * Method to retrieve data from database
     *
     * @param array $fields     array
     * @param array assoc $where
     * @param string $orderby   
     * @param int $ini  
     * @param int $off 
     * 
     * @access public
     * @return array or object
     */
    public function get($fields = NULL, $where = NULL, $orderby = NULL, $ini = NULL, $off = NULL)
    {
        $this->db->from( $this->table );
        
        ( ! is_null($fields) && is_array($fields) ) ? $this->db->select( implode(', ', array_values($fields)) ) : NULL;
        ( ! is_null($where) && is_array($where) ) ? $this->db->where( $where ) : NULL;
        ( ! is_null($off) && ! is_null($ini) ) ? $this->db->limit( $ini, $off ) : NULL;
        ( ! is_null($orderby) ) ? $this->db->order_by( $orderby ) : NULL;

        $query = $this->db->get();
        
        if ($this->return_type == 'array') 
            $results = $query->result_array();
        elseif($this->return_type == 'row')
            $results = $query->row();    
        else 
            $results = $query->result();

        if(count($results) == 0)
            return NULL;
        
        return $results;
        
    }

    /**
     * delete
     *
     * removes a row from database
     *
     * @param integer $id
     * @access public
     * @return booelan
     */
    public function delete($id) {
        if (! is_numeric($id)) {
            return FALSE;
        }
        else {
            $this->db->where('id', $id);
            $this->db->delete($this->table);

            return TRUE;
        }
    }

    /**
     * count_results
     *
     * Count the number of rows in a table
     *
     * @param array assoc $where
     * @access public
     * @return int
     */
    public function count_results($where = '') {
        if (is_array($where)) 
            $this->db->where($where);
        
        elseif (strlen($where) > 0) 
            $this->db->where($where);
        

        return $this->db->count_all_results($this->table);
    }
    
    /**
     * setTable
     * 
     * Define o nome da tabela
     * @access public
     * @param type $table string
     */
    public function setTable( $table ) {
        if( !empty($table) ) 
            $this->table = $table;
    }
    
    
    /**
     * setReturn
     * 
     * Define a forma de retorno da consulta
     * @access public
     * @param type $type string
     */
    public function setReturn( $type ) {
        if( !empty($type) )
        $this->return_type = $type;
    }
    
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
