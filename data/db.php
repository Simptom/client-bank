<?
class PDO_ extends PDO
{
    function __construct($dsn, $username, $password)
    {
        parent::__construct($dsn, $username, $password);
        $this -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    function prepare($sql, $a = array())
    {
        $stmt = parent::prepare($sql, array(PDO::ATTR_STATEMENT_CLASS => array('PDOStatement_')));
        return $stmt;
    }

    function query($sql, $params=array())
    {
        global $_QUERY;
        $stmt = $this -> prepare($sql);
        $_QUERY[] = $sql;
        $stmt -> execute($params);
        return $stmt;
    }

    function querySingle($sql, $params=array())
    {
        $stmt = $this -> query($sql, $params);
        $stmt -> execute($params);
        return $stmt -> fetchColumn(0);
    }

    function queryFetch($sql, $params=array())
    {
        $stmt = $this -> query($sql, $params);
        $stmt -> execute($params);
        return $stmt -> fetch();
    }
}


class PDOStatement_ extends PDOStatement
{
    function execute($params=array())
    {
        if (func_num_args() == 1)
        {
            $params = func_get_arg(0);
        } else {
            $params = func_get_args();
        }
        if (!is_array($params))
        {
            $params = array($params);
        }
        parent::execute($params);
        return $this;
    }  

    function fetchSingle()
    {
        return $this -> fetchColumn(0);
    }  

    function fetchAssoc()
    {
        $this -> setFetchMode(PDO::FETCH_NUM);
        $data = array();
        while ($row = $this -> fetch())
        {
            $data[$row[0]] = $row[1];
        }
        return $data;
    }
}


class DB
{
    static $pdo;
    static $dbh;
    public function __construct()
    {
        try {
            self::$pdo = new PDO_('mysql:host='.S_HOST.';port='.S_PORT.';dbname='.S_DB_NAME, S_USER, S_PASS);
            self::$pdo -> exec('SET CHARACTER SET '.S_CHARSET_NAMES);
            self::$pdo -> exec('SET NAMES '.S_CHARSET_NAMES);
            self::$pdo -> exec('SET wait_timeout=600');
            self::$dbh = self::$pdo;
        }
        catch (PDOException $e)
        {
            die('<h1>No connection to the database server!<br />Check the DB-connection file!</h1>');
        }
    }  

    final public function __destruct()
    {
        self::$pdo = null;
        self::$dbh = null;
    }
}
$database = new DB();
?>