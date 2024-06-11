<?php

/**
 * This File was created by kamscode - @nersus15
 * Download From https://github.com/nersus15/qbuilder
 * Also Try Using mnTemplate (PHP template with MVC architecture Base On MVC Web Programmer UNPAS)
 */

class qbuilder
{

    private $db;
    private $query = "";
    private $bind_scirpt = array();

    protected $_like_escape_str = " ESCAPE '%s' ";
    protected $_like_escape_chr = '!';

    private $inputs = [];
    private $selects = [];
    private $whereClause = [];
    private $groupBy = [];
    private $orderBy = [];
    private $limitNumber = null;
    private $offset = 0;
    private $joinClause = [];
    private $table = '';
    private $startQueryGroupSign = [];
    private $endQueryGroupSign = [];

    public function __construct()
    {
        require_once ROOT . '/core/database.php';
        $this->db = new database;
    }

    function select(string $column)
    {
        $this->selects[] = $column;

        return $this;
    }

    function from(string $table)
    {
        $this->table = $table;
        return $this;
    }

    function where(string $column, string $value = null)
    {
        $keybinder = $this->_addToBinder($value)[0];
        $whereClause = [
            'key' => $column,
            'value' => $keybinder,
            'rel' => 'AND'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');
        $this->whereClause[] = $whereClause;
        return $this;
    }

    function or_where(string $column, string $value = null)
    {
        $keybinder = $this->_addToBinder($value)[0];
        $whereClause = [
            'key' => $column,
            'value' => $keybinder,
            'rel' => 'OR'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function like(string $column, string $value, string $position = 'both')
    {
        switch ($position) {
            case 'bfore':
                $value = "%$value";
                break;
            case 'after':
                $value = "$value%";
                break;
            case 'both':
                $value = "%$value%";
                break;
        }

        $keybinder = $this->_addToBinder($value)[0];
        $whereClause = [
            'key' => $column . ' LIKE',
            'value' => $keybinder,
            'rel' => 'AND'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }
    function not_like(string $column, string $value, string $position = 'both')
    {
        switch ($position) {
            case 'bfore':
                $value = "%$value";
                break;
            case 'after':
                $value = "$value%";
                break;
            case 'both':
                $value = "%$value%";
                break;
        }

        $keybinder = $this->_addToBinder($value)[0];
        $whereClause = [
            'key' => $column . ' NOT LIKE',
            'value' => $keybinder,
            'rel' => 'AND'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function or_like(string $column, string $value, string $position = 'both')
    {
        switch ($position) {
            case 'bfore':
                $value = "%$value";
                break;
            case 'after':
                $value = "$value%";
                break;
            case 'both':
                $value = "%$value%";
                break;
        }

        $keybinder = $this->_addToBinder($value)[0];
        $whereClause = [
            'key' => $column . ' LIKE',
            'value' => $keybinder,
            'rel' => 'OR'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function or_not_like(string $column, string $value, string $position = 'both')
    {
        switch ($position) {
            case 'bfore':
                $value = "%$value";
                break;
            case 'after':
                $value = "$value%";
                break;
            case 'both':
                $value = "%$value%";
                break;
        }

        $keybinder = $this->_addToBinder($value)[0];
        $whereClause = [
            'key' => $column . ' NOT LIKE',
            'value' => $keybinder,
            'rel' => 'OR'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function where_in(string $column, array $values)
    {
        $keybinder = $this->_addToBinder($values);

        $inStatement = '';
        foreach ($keybinder as $key) {
            $inStatement .= ($inStatement ? ',' : '') . $key;
        }
        $whereClause = [
            'key' => $column . ' IN',
            'value' => "($inStatement)",
            'rel' => 'AND'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function or_where_in(string $column, array $values)
    {
        $keybinder = $this->_addToBinder($values);

        $inStatement = '';
        foreach ($keybinder as $key) {
            $inStatement .= ($inStatement ? ',' : '') . $key;
        }
        $whereClause = [
            'key' => $column . ' IN',
            'value' => "($inStatement)",
            'rel' => 'OR'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function where_not_in(string $column, array $values)
    {
        $keybinder = $this->_addToBinder($values);

        $inStatement = '';
        foreach ($keybinder as $key) {
            $inStatement .= ($inStatement ? ',' : '') . $key;
        }
        $whereClause = [
            'key' => $column . ' NOT IN',
            'value' => "($inStatement)",
            'rel' => 'AND'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function or_where_not_in(string $column, array $values)
    {
        $keybinder = $this->_addToBinder($values);

        $inStatement = '';
        foreach ($keybinder as $key) {
            $inStatement .= ($inStatement ? ',' : '') . $key;
        }
        $whereClause = [
            'key' => $column . ' NOT IN',
            'value' => "($inStatement)",
            'rel' => 'OR'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    function between(string $column, int $start, int $end)
    {
        $keybinder = $this->_addToBinder([$start, $end]);


        $whereClause = [
            'key' => $column . ' BETWEEN',
            'value' => $keybinder[0] . ' AND ' . $keybinder[1],
            'rel' => 'AND'
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');

        $this->whereClause[] = $whereClause;
        return $this;
    }

    /**
     * @param $type String INNER | LEFT | RIGHT
     * @return qbuilder Instance
     */
    function join(string $tabel, string $on, string $type = 'INNER')
    {
        $this->joinClause[] = [
            'table' => $tabel,
            'on' => $on,
            'type' => $type
        ];

        $this->_checkGrouping($whereClause, 'start');
        $this->_checkGrouping($whereClause, 'end');
        return $this;
    }

    function group_by(...$columns)
    {
        $this->groupBy = $columns;
        return $this;
    }

    function order_by(...$columns)
    {
        $this->orderBy = $columns;
        return $this;
    }

    function limit(int $number = null, int $offset = 0)
    {
        $this->limitNumber = $number;
        $this->offset = $offset;
        return $this;
    }

    function insert(array $input, string $table)
    {
        $this->table = $table;
        foreach ($input as $k => $v) {
            $this->inputs[$k] = $this->_addToBinder($v)[0];
        }

        $this->_compileQuery('insert');
        $this->_runQuery('insert');
    }

    function update(array $input, string $table)
    {
        $this->table = $table;
        foreach ($input as $k => $v) {
            $this->inputs[$k] = $this->_addToBinder($v)[0];
        }

        $this->_compileQuery('update');
        $this->_runQuery('update');
    }

    function delete(string $tabel)
    {
        $this->table = $tabel;

        $this->_compileQuery('delete');
        $this->_runQuery('delete');
    }

    function row()
    {
        $this->_compileQuery('row');
        $this->_runQuery('row');

        return $this->db->single();
    }

    function row_object()
    {
        $this->_compileQuery('row_object');
        $this->_runQuery('row_object');

        return $this->db->single_object();
    }

    function results($type = 'array')
    {
        $this->_compileQuery('results');
        $this->_runQuery('results');

        $data = $this->db->resultSet();
        if($type == 'object'){
            foreach($data as $k => $v){
                $data[$k] = (object) $v;
            }
        }
        return $data;
    }

    function result_object()
    {
        $this->_compileQuery('result_object');
        $this->_runQuery('result_object');

        return $this->db->result_object();
    }

    function num_rows()
    {
        $this->_compileQuery('num_rows');

        $this->db->query($this->query);
        if (!empty($this->bind_scirpt)) {
            foreach ($this->bind_scirpt as $key => $value) {
                $this->db->bind($key, $value);
            }
        }

        $this->reset();
        return $this->db->rowCount();
    }


    function startQueryGroup($prefix = '')
    {
        $this->startQueryGroupSign[] = '(';

        return $this;
    }

    function endQueryGroup()
    {
        $this->endQueryGroupSign[] = ')';
        return $this;
    }

    function get_query()
    {
        return $this->_compileQuery('get_query', true);
    }


    function reset()
    {
        $this->inputs = [];
        $this->selects = [];
        $this->whereClause = [];
        $this->groupBy = [];
        $this->orderBy = [];
        $this->limitNumber = null;
        $this->offset = 0;
        $this->joinClause = [];
        $this->table = '';
        $this->startQueryGroupSign = [];
        $this->endQueryGroupSign = [];
        $this->query = '';
        $this->bind_scirpt = [];
    }

    private function _has_operator($str)
    {
        return (bool) preg_match('/(<|>|!|=|\sIS NULL|\sIS NOT NULL|\sEXISTS|\sBETWEEN|\sLIKE|\sIN\s*\(|\s)/i', trim($str));
    }
    private function _get_operator($str)
    {
        static $_operators;

        if (empty($_operators)) {
            $_les = ($this->_like_escape_str !== '')
                ? '\s+' . preg_quote(trim(sprintf($this->_like_escape_str, $this->_like_escape_chr)), '/')
                : '';
            $_operators = array(
                '\s*(?:<|>|!)?=\s*',             // =, <=, >=, !=
                '\s*<>?\s*',                     // <, <>
                '\s*>\s*',                       // >
                '\s+IS NULL',                    // IS NULL
                '\s+IS NOT NULL',                // IS NOT NULL
                '\s+EXISTS\s*\(.*\)',        // EXISTS(sql)
                '\s+NOT EXISTS\s*\(.*\)',    // NOT EXISTS(sql)
                '\s+BETWEEN\s+',                 // BETWEEN value AND value
                '\s+IN\s*\(.*\)',            // IN(list)
                '\s+NOT IN\s*\(.*\)',        // NOT IN (list)
                '\s+LIKE\s+\S.*(' . $_les . ')?',    // LIKE 'expr'[ ESCAPE '%s']
                '\s+NOT LIKE\s+\S.*(' . $_les . ')?' // NOT LIKE 'expr'[ ESCAPE '%s']
            );
        }

        return preg_match('/' . implode('|', $_operators) . '/i', $str, $match)
            ? $match[0] : FALSE;
    }

    private function _addToBinder($values)
    {
        if (!is_array($values))
            $values = [$values];

        $keys = [];
        foreach ($values as $value) {
            $key = strtolower(':' . 'VAR' . random(3) . random(1, 'int'));
            $keys[] = $key;

            $this->bind_scirpt[$key] = $value;
        }
        return $keys;
    }

    private function _runQuery($caller = 'insert')
    {
        $this->db->query($this->query);

        if (!empty($this->bind_scirpt)) {
            foreach ($this->bind_scirpt as $key => $value) {
                $this->db->bind($key, $value);
            }
        }

        if (in_array($caller, ['insert', 'insert_batch', 'update', 'delete'])) {
            $this->db->execute();
        }

        $this->reset();
    }

    private function _checkGrouping(&$var, $state = 'start')
    {
        if ($state == 'start' && !empty($this->startQueryGroupSign)) {
            $left = count($this->startQueryGroupSign);
            $var['openGroupSign'] = '(';
            unset($this->startQueryGroupSign[$left - 1]);
        } elseif ($state == 'end' && !empty($this->endQueryGroupSign)) {
            $left = count($this->endQueryGroupSign);
            $var['closeGroupSign'] = ')';
            unset($this->endQueryGroupSign[$left - 1]);
        }
    }

    private function _compileQuery($caller = null, $return = false)
    {
        $query = "";
        $table = $this->table;
        $whereSyntax = '';
        $joinSyntax = '';
        $insertSyntax = ['', ''];
        $updateSyntax = '';
        $deleteSyntax = '';
        if (in_array($caller, ['row', 'results', 'result_object', 'row', 'row_object', 'num_rows'])) {
            $selects = empty($this->selects) ? '*' : join(', ', $this->selects);
            $query = "SELECT $selects FROM $table";
        } elseif ($caller == 'update') {
            $query = "UPDATE $table";
        } elseif ($caller == 'delete') {
            $query = "DELETE FROM $table ";
        } elseif ($caller == 'insert') {
            $query = "INSERT INTO $table";
        } else {
            if (!empty($this->selects)) {
                $selects =  join(', ', $this->selects);
                $query = "SELECT $selects FROM $table";
            }
        }

        if (!empty($this->whereClause)) {
            $n = count($this->whereClause);
            $i = 1;
            foreach ($this->whereClause as $v) {
                $key = $v['key'];
                $value = $v['value'];
                $rel = $v['rel'];
                $openGroupSign = isset($v['openGroupSign']) ? $v['openGroupSign'] : '';
                $closeGroupSign = isset($v['closeGroupSign']) ? $v['closeGroupSign'] : '';

                // Makse Sure thereis no one close group sign in the last where clauses
                if ($i == $n) {
                    if (!empty($this->endQueryGroupSign)) {
                        $closeGroupSign = join('', $this->endQueryGroupSign);
                    }
                }

                $operator = '';
                if (is_null($value)) {
                    if (!$this->_has_operator($key))
                        $operator = ' IS NULL ';
                } else {
                    if (!$this->_has_operator($key))
                        $operator = ' = ';
                    else{
                        $operator = $this->_get_operator($key);
                        $key = str_replace($operator, '', $key);
                    }
                }
                $whereSyntax .= ($whereSyntax ? $rel : ' WHERE') . " $openGroupSign $key $operator $value $closeGroupSign";

                $i++;
            }
        }
        if (!empty($this->joinClause)) {
            foreach ($this->joinClause as $join) {
                $table = $join['table'];
                $on = $join['on'];
                $type = $join['type'];

                $joinSyntax .= " $type JOIN $table ON $on ";
            }
        }

        $groupBy = null;
        $orderBy = null;

        if(!empty($this->groupBy)){
            $groupBy =  'GROUP BY ' .  join(',', $this->groupBy);
        }

        if(!empty($this->orderBy)){
            $orderBy = 'ORDER BY ' . join(', ', $this->orderBy);
        }

        if (in_array($caller, ['insert', 'update', 'delete'])) {
            if ($caller == 'insert' && !empty($this->inputs)) {
                $insertSyntax[0] = join('`,`', array_keys($this->inputs));
                $insertSyntax[1] = join(',', array_values($this->inputs));

                $insertSyntax = ' (`' . $insertSyntax[0] . '`) VALUES (' . $insertSyntax[1] . ')';

                $query .= $insertSyntax;
            } elseif ($caller == 'update' && !empty($this->inputs)) {
                foreach ($this->inputs as $key => $value) {
                    $updateSyntax .= ($updateSyntax ? ', ' : ' SET ') . $key . '=' . $value;
                }
                $query .= $updateSyntax . $whereSyntax;
            } elseif ($caller == 'delete') {
                $query .= $deleteSyntax . $whereSyntax;
            }
        } else {
            // Assemble The Query
            $query .= $joinSyntax . $whereSyntax . " $groupBy" . " $orderBy " . (!empty($this->limitNumber) ? 'LIMIT ' . $this->limitNumber . ' OFFSET ' . $this->offset : '');
        }
        if ($return) return $query;

        $this->query = $query;
    }
}