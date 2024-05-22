<?php
/**
 * This File was created by kamscode - @nersus15
 * Download From https://github.com/nersus15/mnTemplate/blob/master/apps/helpers/qbuilder.php
 * Also Try Using mnTemplate (PHP template with MVC architecture Base On MVC Web Programmer UNPAS)
 */
use function PHPSTORM_META\type;

class qbuilder
{
    private $db;
    private $query = "";
    private $countWhere = 0;
    private $countselect = 0;
    private $countjoin = 0;
    private $qeueu = array();
    private $new = true;
    private $functions = array("select", "from", "join", "where",  "wherein", "or_where", "like", "orlike", "group_by", 'order_by', "subquery", "limit");
    private $bind_scirpt = array();
    private $hasil = null;
    private $callOrder = [];
    public function __construct()
    {
        require_once ROOT . '/core/database.php';
        $this->db = new database;
    }

    function subquery()
    {
        if ($this->new) {
            $this->callOrder[] = 'subquery';
            $this->qeueu['subquery'][] = array(
                'temp' => array(
                   
                )
            );
        } else {
            return $this->query;
        }
        return $this;
    }
    function group_by($kolom)
    {
        if ($this->new) {
            $this->callOrder[] = 'group_by';
            $this->qeueu['group_by'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                )
            );
        } else {
            $this->query .= ' GROUP BY ' . $kolom;
        }
        return $this;
    }

    /**
     * @param $tipe enum['ASC'|'DESC']
     */
    function order_by($kolom, $tipe = 'ASC')
    {
        if ($this->new) {
            $this->callOrder[] = 'order_by';
            $this->qeueu['order_by'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                    'tipe' => $tipe
                )
            );
        } else {
            $this->query .= ' ORDER BY ' . $kolom . ' ' . $tipe;
        }
        return $this;

    }

    function startQueryGroup($prefix = ""){
        if ($this->new) {
            $this->callOrder[] = 'startQueryGroup';
            $this->qeueu['startQueryGroup'][] = array(
                'temp' => array(
                    'prefix' => $prefix,
                )
            );
        } else {
            $this->query .= "$prefix (";
        }

        return $this;
    }

    function endQueryGroup($suffix = ""){
        if ($this->new) {
            $this->callOrder[] = 'endQueryGroup';
            $this->qeueu['endQueryGroup'][] = array(
                'temp' => array(
                    'suffix' => $suffix,
                )
            );
        }else{
            $this->query .= ") $suffix";
        }
        return $this;
    }

    function get_query()
    {
        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();

        if (count($this->qeueu) == 0) {
            $this->query .= "";
            $this->db->query($this->query);
            foreach ($this->bind_scirpt as $bind) {
                $this->db->bind($bind['key'], $bind['value']);
            }
            $query = $this->query;
            $this->reset();

            return $query;
        }
    }

    function setQuery($query, $reset = false){
        $this->query = $query;

        
        return $this;
    }
    function join($tabel, $on, $tipe = "INNER")
    {

        if ($this->new) {
            $this->callOrder[] = 'join';
            $this->qeueu['join'][] = array(
                'temp' => array(
                    'tabel' => $tabel,
                    'on' => $on,
                    'tipe' => $tipe,
                )
            );
        } else {
            // if($this->countjoin >0)
            $this->query .= ' ' . $tipe . " JOIN " . $tabel . " ON " . $on;
        }
        return $this;
    }

    function select($selection)
    {
        if ($this->new) {
            $this->callOrder[] = 'select';
            $this->qeueu['select'][] = array(
                'temp' => $selection
            );
        } else {
            if ($this->countselect > 0)
                $this->query .=  ", " . $selection;
            else
                $this->query .=  " SELECT " . $selection;

            $this->countselect++;
        }
        return $this;
    }
    function insert($input, $table)
    {
        $query = 'INSERT INTO ' . $table . '(';
        $jml = count($input);
        $bts = $jml - 1;
        $i = 0;
        foreach ($input as $k => $v) {
            if ($i != $bts)
                $query .= '`' . $k . '`, ';
            elseif ($i == $bts)
                $query .= '`' . $k . '`) VALUES (';

            $i++;
        }

        $i = 0;
        foreach ($input as $k => $v) {
            if ($i != $bts)
                $query .= '"' . $v . '", ';
            elseif ($i == $bts)
                $query .= '"' . $v . '")';

            $i++;
        }

        // var_dump($query);die;
        $this->db->query($query);
        $this->db->execute();
        return $this;
    }

    function update($input, $table){
        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();

        $query = 'UPDATE ' . $table . ' SET ';
        $jml = count($input);
        $bts = $jml - 1;
        $i = 0;
        foreach ($input as $k => $v) {
            if ($i != $bts)
                $query .= '`' . $k . '` = "' . $v . '", ';
            elseif ($i == $bts)
                $query .= '`' . $k . '` = "' . $v .'" ' ;

            $i++;
        }
        $this->db->query($query . $this->query);
        foreach ($this->bind_scirpt as $bind) {
            $this->db->bind($bind['key'], $bind['value']);
        }
        $this->db->execute();
        return $this;
    }

    function delete($table){
        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();

        $query = 'DELETE FROM ' . $table;
        $this->db->query($query . $this->query);
        foreach ($this->bind_scirpt as $bind) {
            $this->db->bind($bind['key'], $bind['value']);
        }
        $this->db->execute();
        return $this;
    }

    function insert_batch($inputs, $table)
    {
        $query = 'INSERT INTO ' . $table . '(';
        $juml_batch = count($inputs);
        $bts_batch = $juml_batch - 1;
        $jml = count($inputs[0]);
        $bts = $jml - 1;
        $j = 0;
        $i = 0;
        foreach ($inputs[0] as $k => $v) {
            if ($i != $bts)
                $query .= '`' . $k . '`, ';
            elseif ($i == $bts)
                $query .= '`' . $k . '`) VALUES (';

            $i++;
        }

        foreach ($inputs as $input) {
            $i = 0;
            foreach ($input as $k => $v) {
                if ($i != $bts)
                    $query .= '"' . $v . '", ';
                elseif ($i == $bts)
                    $query .= '"' . $v . '")';

                $i++;
            }

            $query .= $j != $bts_batch ? ', (' : null;
            $j++;
        }
        $this->db->query($query);
        $this->db->execute();
        // var_dump($query);
        return $this;
    }
    function from($table)
    {
        if ($this->new) {
            $this->callOrder[] = 'from';
            $this->qeueu['from'][] = array(
                'temp' => $table
            );
        } else {
            $this->query .= " FROM " . $table;
        }
        return $this;
    }
    function where($kolom, $nilai, $operator = "=")
    {
        if ($this->new) {
            $this->callOrder[] = 'where';
            $this->qeueu['where'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                    'nilai' => $nilai,
                    'operator' => $operator
                )
            );
        } else {
            $key_binding = 'VAR' . random(1) . random(1, 'int');
            if (is_string($nilai))
                $nilai = "$nilai";
            if (stristr($this->query, "where"))
                $this->query .= " and " . $kolom . " " . $operator . " :" . $key_binding;
            else
                $this->query .= " where " . $kolom . " " . $operator . " :" . $key_binding;

            $this->bind_scirpt[] = array(
                "key" => $key_binding,
                "value" => $nilai,
            );
            $this->countWhere++;
        }
        return $this;
    }

    function like($kolom, $nilai, $type = 'both'){
        if ($this->new) {
            $this->callOrder[] = 'like';
            $this->qeueu['like'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                    'nilai' => $nilai,
                    'type' => $type
                )
            );
        } else {
            $key_binding = 'VAR' . random(1) . random(1, 'int');
            $likStatement = " LIKE :" . $key_binding;

            switch($type){
                case 'bfore':
                    $nilai = "%$nilai";
                    break;
                case 'after':
                    $nilai = "$nilai%";
                    break;
                case 'both':
                    $nilai = "%$nilai%";
                    break;
            }

            if (stristr($this->query, "where"))
                $this->query .= " and " . $kolom . $likStatement;
            else
                $this->query .= " where " . $kolom . $likStatement;

            $this->bind_scirpt[] = array(
                "key" => $key_binding,
                "value" => $nilai,
            );
            $this->countWhere++;
        }
        return $this;
    }

    function orlike($kolom, $nilai, $type = 'both'){
        if ($this->new) {
            $this->callOrder[] = 'orlike';
            $this->qeueu['orlike'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                    'nilai' => $nilai,
                    'type' => $type
                )
            );
        } else {
            $key_binding = 'VAR' . random(1) . random(1, 'int');
            $likStatement = " LIKE :" . $key_binding;

            switch($type){
                case 'bfore':
                    $nilai = "%$nilai";
                    break;
                case 'after':
                    $nilai = "$nilai%";
                    break;
                case 'both':
                    $nilai = "%$nilai%";
                    break;
            }
            
            if (stristr($this->query, "where"))
                $this->query .= " OR " . $kolom . $likStatement;

            $this->bind_scirpt[] = array(
                "key" => $key_binding,
                "value" => $nilai,
            );
            $this->countWhere++;
        }
        return $this;
    }

    function wherein($kolom, $nilai, $not = false)
    {
        if ($this->new) {
            $this->callOrder[] = 'wherein';
            $this->qeueu['wherein'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                    'nilai' => $nilai,
                    'not' => $not
                )
            );
        } else {

            $inStatement = '';
            foreach($nilai as $v){
                $key = 'VAR' . random(1) . random(1, 'int');
                $inStatement .= ($inStatement ? ',:': ':') . $key;
                $this->bind_scirpt[] = array(
                    'key' => $key,
                    'value' => $v
                );
            }
        
            $inStatement = "($inStatement)";

            $operator = $not ? "NOT IN" : "IN";

            if (stristr($this->query, "where"))
                $this->query .= " and " . $kolom . " $operator $inStatement";
            else
                $this->query .= " where " . $kolom . " $operator $inStatement";

            $this->countWhere++;
        }
        return $this;
    }
    function or_where($kolom, $nilai, $operator = "=")
    {
        if ($this->new) {
            $this->callOrder[] = 'or_where';
            $this->qeueu['or_where'][] = array(
                'temp' => array(
                    'kolom' => $kolom,
                    'nilai' => $nilai,
                    'operator' => $operator
                )
            );
        } else {
            $key_binding = 'VAR' . random(1) . random(1, 'int');
            $this->query .= " OR " . $kolom . " " . $operator . " :" . $key_binding;
            $this->bind_scirpt[] = array(
                "key" => $key_binding,
                "value" => $nilai,
            );
            $this->countWhere++;
        }
        return $this;
    }

    function limit($limit = null){
        if ($this->new) {
            $this->callOrder[] = 'limit';
            $this->qeueu['limit'][] = array(
                'temp' => array(
                    'limit' => $limit
                )
            );
        } else {
            if(!empty($limit)){
                $this->query .= ' LIMIT ' . $limit;
            }
        }
        return $this;
    }
    function row()
    {
        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();

        if (count($this->qeueu) == 0) {
            $this->query .= "";
            $this->db->query($this->query);
            foreach ($this->bind_scirpt as $bind) {
                $this->db->bind($bind['key'], $bind['value']);
            }
            $this->reset();
            return $this->db->single();
        }
        return $this;

    }
    function results()
    {

        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();
        if (count($this->qeueu) == 0) {
            $this->query .= "";
            $this->db->query($this->query);
            foreach ($this->bind_scirpt as $bind) {
                $this->db->bind($bind['key'], $bind['value']);
            }
            $this->reset();
            return $this->db->resultSet();
        }
        
    }
    function result_object()
    {
        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();

        if (count($this->qeueu) == 0) {
            $this->query .= "";
            $this->db->query($this->query);
            foreach ($this->bind_scirpt as $bind) {
                $this->db->bind($bind['key'], $bind['value']);
            }
            $this->reset();
            return $this->db->result_object();
        }
    }

    function num_rows(){
        $this->new = false;
        if (count($this->qeueu) > 0)
            $this->execute();

        if (count($this->qeueu) == 0) {
            $this->query .= "";
            $this->db->query($this->query);
            foreach ($this->bind_scirpt as $bind) {
                $this->db->bind($bind['key'], $bind['value']);
            }
            $this->reset();
            return $this->db->rowCount();
        }
    }
    function call_function($f, $t, $index)
    {
        if ($f == "select")
            $this->select($t);

        if ($f == 'from')
            $this->from($t);

        if ($f == 'join')
            $this->join($t['tabel'], $t['on'], $t['tipe']);

        if ($f == "where")
            $this->where($t['kolom'], $t['nilai'], $t['operator']);

        if ($f == "wherein")
            $this->wherein($t['kolom'], $t['nilai'], $t['not']);

        if ($f == "or_where")
            $this->or_where($t['kolom'], $t['nilai'], $t['operator']);

        if($f == 'like')
            $this->like($t['kolom'], $t['nilai'], $t['type']);

        if($f == 'orlike')
            $this->orlike($t['kolom'], $t['nilai'], $t['type']);

        if ($f == "group_by")
            $this->group_by($t['kolom']);

        if ($f == "order_by")
            $this->order_by($t['kolom'], $t['tipe']);

        if ($f == 'subquery')
            $this->subquery();

        if($f == 'startQueryGroup')
            $this->startQueryGroup();
        if($f == 'endQueryGroup')
            $this->endQueryGroup();
            
        if($f == 'liit')
            $this->limit();

        unset($this->qeueu[$f][$index]);
    }
    function execute()
    {
        $unorder = ['startQueryGroup', 'endQueryGroup', ];
        $indexOfUnOrderd = [];


        // $indexOfUnOrderd = array_filter($this->callOrder, function($v) use($unorder) { return in_array($v, $unorder);});
       
        foreach ($this->functions as $f) {
            foreach ($this->qeueu as $k => $v) {
                if ($k == $f) {
                    foreach ($v as $key => $value){
                        $this->call_function($f, $value['temp'], $key);
                    }
                    
                }
                if (empty($this->qeueu[$f]))
                    unset($this->qeueu[$f]);
            }
        }

        if (count($this->qeueu) > 0)
            $this->execute();
    }
    function get()
    {
        $this->reset();
        return $this;
    }
    function reset()
    {
        $this->query = "";
        $this->countWhere = 0;
        $this->countselect = 0;
        $this->qeueu = array();
        $this->new = true;
        $this->bind_scirpt = array();
        $this->hasil = null;
    }
}