<?php
class comics_db
{
    protected $conn;
    protected $query;
    protected $enable_errors = true;
    protected $close_query = true;
    public $q_count = 0;

    public function __construct(
        $dbhost = 'localhost',
        $dbuser = 'dbuser',
        $dbpass = '',
        $dbname = 'dbname',
        $charset = 'utf8'
    ) {
        $this->conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($this->conn->connect_error) {
            $this->error(
                'connection to database failed - ' . $this->conn->connect_error
            );
        }
        $this->conn->set_charset($charset);
    }

    public function insert($email, $validation)
    {
        $sql = "INSERT INTO users (verified, email, shortner) VALUES (0, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $validation);
        $stmt->execute();
    }

    public function query($query)
    {
        if (!$this->close_query) {
            $this->query->close();
        }
        if ($this->query = $this->conn->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = [];
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array([$this->query, 'bind_param'], $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                $this->error(
                    'something went wrong check your params - ' .
                        $this->query->error
                );
            }
            $this->close_query = false;
            $this->q_count++;
        } else {
            $this->error('syntax error - ' . $this->conn->error);
        }
        return $this;
    }

    public function fetchAll($callback = null)
    {
        $params = [];
        $row = [];
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array([$this->query, 'bind_result'], $params);
        $result = [];
        while ($this->query->fetch()) {
            $r = [];
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') {
                    break;
                }
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->close_query = true;
        return $result;
    }

    public function fetchArray()
    {
        $params = [];
        $row = [];
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array([$this->query, 'bind_result'], $params);
        $result = [];
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->close_query = true;
        return $result;
    }

    public function close()
    {
        return $this->conn->close();
    }

    public function error($error)
    {
        if ($this->enable_errors) {
            exit($error);
        }
    }

    private function _gettype($var)
    {
        if (is_string($var)) {
            return 's';
        }
        if (is_float($var)) {
            return 'd';
        }
        if (is_int($var)) {
            return 'i';
        }
        return 'b';
    }
}
