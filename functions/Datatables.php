<?php
class Datatables {
    private $header;
    private $selection;
    private $data;
    private $resultHandler;
    private $keyword;
    private $search_option;
    private $all_data;
    private $filterred_data;
    private $order;
    private $columns;
    public $query;
    
    /**
     * Tambahkan kolom yang akan di select dari database
     * @param String $select String nama field database
     * @return Void
     */

    public function __construct() {
        $this->keyword = isset($_GET['search']) && isset($_GET['search']['value']) ? $_GET['search']['value'] : null;
        $this->order = isset($_GET['order']) ? $_GET['order'] : [];
        $this->columns = isset($_GET['columns']) ? $_GET['columns'] : [];
    }
    public function addSelect(string $select = '*'){
        $this->selection = $select;
        return $this;
    }
    
    /**
     * @param CI_DB_query_builder $query Query sql database
     * @return Void
     */
    
    function setQuery($query){        
        $searchable = [];
        

        // If selection not set by addSelect
        if(empty($this->selection)){
            if(!empty($this->header)){
                $tmp = '';
                $i = 0;
                foreach($this->header as $k => $v){
                    if(isset($v['field']) && !empty($v['field']))
                        $tmp .= $v['field'] . " as " . $k;
                    else 
                        $tmp .= $k;

                    if($i < count($this->header) - 1)
                        $tmp .= ', ';
                    $i++;
                }

                $this->selection = $tmp;
            }
        }
        if(empty($this->selection))
            $this->selection = '*';

        if(!empty($this->order)){
            $columnIndex = $this->order[0]['column'];
            $orderDirection = strtoupper($this->order[0]['dir']);
            $column = $this->columns[$columnIndex];
            $cname = null;
            if(!empty($column['name']))
                $cname = $column['name'];
            elseif(!is_numeric($column['data']))
                $cname = $column['data'];

            if(!empty($cname)){
                $header = $this->header[$cname];
                $orderColumn = isset($header['field']) ? $header['field'] : $cname;
                if(!isset($header['order']) || $header['order'])
                    $query->order_by($orderColumn, $orderDirection);
            }
        }

        $query->select($this->selection);
        // var_dump($query->get_query());die;
        foreach($this->header as $key => $value){
            if(!isset($value['searchable']) || $value['searchable'] == false) continue;

            if(isset($value['field']) && !empty($value['field']))
                $searchable[] = $value['field'];
            else
                $searchable[] = $key;
        }
        $option = $this->search_option;
        $keyword = $this->keyword;
        if(empty($option)){
            $option = array(
                'spesifik' => false,
                'liketipe' => 'after',
            );
        }
        if(!empty($keyword)){
            $i = 0;
            $query->startQueryGroup();
            
            foreach($searchable as $v){
                if($option['spesifik']){
                    if($i == 0) $query->where($v, $keyword);
                    else $query->or_where($v, $keyword);
                }else{
                    if($i == 0) $query->like($v, $keyword, $option['liketipe']);
                    else $query->or_like($v, $keyword, $option['liketipe']);
                }
                $i++;
            }
            $query->endQueryGroup();
        }
        // var_dump($query->get_query());die;
        $this->query = $query;
        $filterred_data_q = clone $query;
        $this->filterred_data = $filterred_data_q->num_rows();
        return $this;
    }

    private function _get_data(){
        $temp = [];
        
        $data = $this->data;
        if(empty($data)) $data = [];

        foreach($data as $v){
            $is_data_obj = is_object($v);
            $tmp = [];
            foreach($this->header as $key => $value){
                $tmp[$key] = $is_data_obj ? $v->{$key} : $v[$key];
            }
            $temp[] = $is_data_obj ? (object) $tmp : (array) $tmp;
        }

        return $temp;
        
    }

    /**
     * @param String $tipe Array or Object
     */
    function getData( string $tipe = 'array', $enableCache = false){
        if(isset($_GET['start']) && isset($_GET['length'])){
            $this->setLimit($_GET['length'], $_GET['start']);
        }
        
        try {
            $this->data = $this->query->results($tipe);
        } catch (\Throwable $th) {
            //throw $th;

            print($th->getMessage());
            exit;
        }
       
        $data = $this->_get_data();
        if(!empty($this->resultHandler)){
            $callback = $this->resultHandler;
            $data = $callback($this->data, $data, $this->header, $this->query);
        }
        $this->all_data = count($data);
        if(!empty($this->keyword))
            $this->filterred_data = count($data);

        // if(isset($_GET['start']) && isset($_GET['length'])){
        //     $start = $_GET['start'];
        //     $length = $_GET['length'];

        //     $data = array_splice($data, $start, $length);
        // }
       
        // if($this->reCount){
        //     if(isset($_GET['start']) && isset($_GET['length'])){
        //         $start = $_GET['start'];
        //         $length = $_GET['length'];
        //     }
        //     $this->all_data = count($data);
        //     if(empty($keyword))
        //         $this->filterred_data = count($data);
        // }

        $this->reset();
        return (object) array(
            'draw' => isset($_GET['draw']) ? $_GET['draw'] : 1, // Ini dari datatablenya    
            'recordsTotal' => $this->all_data,    
            'recordsFiltered'=> $this->filterred_data,    
            'data'=> $data
        );
    }

    function setData($data){
        $this->data = $data;
        return $this;
    }

    function setHeader(array $header){
        $this->header = $header;
        return $this;
    }

    /**
     * @param int $limit number of maxsimum results
     * @param int $offset ending index
     * @return mixed Array of result
     * Used to set the limit of results
     */
    function setLimit($limit, $offset = 0){
        $this->query->limit($limit, $offset);
    }

    /**
     * @param Function $callback function($dataQuery,$dataMap,$header,$query) to handle results
     * @param Array $dataQuer Data from Database
     * @param Array $dataMap Data after compile
     * @param Array $header header for response
     * @param CI_DB_query_builder $query Query sql database
     * @return Function
     */

    function set_resultHandler($callback, $reCount = false){
        $this->resultHandler = $callback;
    }
    
    /**
     * @param Array $option option for filter/searching, -spesifik = true/false (true use where and false use like, default false), -liketipe = after/before/both (default after)
     */
    function set_search_option($option){
        $this->search_option = $option;
        return $this;
    }

    private function reset(){
        $this->header = null;
        $this->selection = null;
        $this->data = null;
        $this->resultHandler = null;
        $this->keyword = null;
        $this->search_option = null;
    }
}