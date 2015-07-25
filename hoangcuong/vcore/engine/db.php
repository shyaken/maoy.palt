<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class db
{
    /**
     * global variables
     */
    var $dbHost = 'localhost';            // default database host
    var $dbUser = 'root';                         // database login name
    var $dbPass = '';                          // database login password
    var $dbName = 'admin_masterapk';                          // database name
    var $dbLink;                          // database link identifier
    var $queryId;                         // database query identifier
    var $errors = array();                 // storage for errors messages
    var $totalRecords;                    // the total number of records received from a select statement
    var $lastInsertId;                  // last incremented value of the primary key
    var $prevId = 0;                      // previus record id. [for navigating through the db]
    var $transactionsCapable = false;    // does the server support transactions?
    var $beginWork = false;              // sentinel to keep track of active transactionsvar
    var $lastId = 0;

    /**
     * get and set type methods for retrieving properties.
     */

    function getDbHost(){
        return $this->dbHost;
    }

    function getDbUser(){
        return $this->dbUser;
    }

    function getDbPass(){
        return $this->dbPass;
    } // end function

    function getDbName(){
        return $this->dbName;
    } // end function

    function setDbHost($value){
        return $this->dbHost = $value;
    } // end function

    function setDbUser($value){
        return $this->dbUser = $value;
    } // end function

    function setDbPass($value){
        return $this->dbPass = $value;
    } // end function

    function setDbName($value){
        return $this->dbName = $value;
    } // end function

    function getErrors(){
        return $this->errors;
    } // end function

    /**
     * End of the Get and Set methods
     */

    /**
     * Constructor
     *
     * @param      String $dbUser, String $dbPass, String $dbName
     * @return     void
     * @access     public
     */
    function db(){
        $this->setDbUser(USER);
        $this->setDbPass(PASS);
        $this->setDbName(DBNAME);
        
        if ($this->dbHost != null) {
            $this->setDbHost(HOST);
        }
    } // end function

    /**
     * Connect to the database and change to the appropriate database.
     *
     * @param      none
     * @return     database link identifier
     * @access       public
     * @scope      public
     */
    function connect(){
        $this->dbLink = @mysql_pconnect($this->dbHost, $this->dbUser, $this->dbPass);
        
        if (!$this->dbLink) {
            $this->setErrors('Khong the ket noi toi may chu du lieu.');
            die('Khong the ket noi toi may chu du lieu.');
        }

        $t = @mysql_select_db($this->dbName, $this->dbLink);

        if (!$t) {
            $this->setErrors('Khong the ket noi toi may chu du lieu.');
            die('Khong the ket noi toi may chu du lieu.');
        }

        if ($this->serverHasTransaction()) {
            $this->transactionsCapable = true;
        }
    
        return $this->dbLink;

    } // end function

    /**
     * Disconnect from the mySQL database.
     *
     * @param      none
     * @return     void
     * @access     public
     * @scope      public
     */
    function disconnect(){
        $test = @mysql_close($this->dbLink);

        if (!$test) {
            $this->setErrors('Khong the ket noi toi may chu du lieu.');
        }

        unset($this->dbLink);

    } // end function

    /**
     * Stores errors messages
     *
     * @param      String $message
     * @return     String
     * @access     private
     * @scope      public
     */
    function setErrors($message){
        return $this->errors[] = $message.' '.@mysql_error().'.';

    } // end function

    /**
     * Show any errorss that occurred.
     *
     * @param      none
     * @return     void
     * @access     public
     * @scope      public
     */
    function showErrors(){return;
        if ($this->hasErrors()){
            reset($this->errors);

            $errcount = count($this->errors);    //count the number of errors messages

            echo "<p>error(s) found: <b>'$errcount'</b></p>\n";

            // print all the errors messages.
            while (list($key, $val) = each($this->errors)) {
                echo "+ $val<br>\n";
            }

            $this->resetErrors();
        }

    } // end function

    /**
     * Checks to see if there are any errors messages that have been reported.
     *
     * @param      none
     * @return     boolean
     * @access     private
     */
    function hasErrors(){
        if (count($this->errors) > 0) {
            return true;
        } else {
            return false;
        }

    } // end function

    /**
     * Clears all the errors messages.
     *
     * @param      none
     * @return     void
     * @access     public
     */
    function resetErrors(){
        if ($this->hasErrors()) {
            unset($this->errors);
            $this->errors = array();
        }

    } // end function

    /**
     * Performs an SQL query.
     *
     * @param      String $sql
     * @return     int query identifier
     * @access     public
     * @scope      public
     */
    function query($sql, $debug = true){
        if (empty($this->dbLink)) {
            // check to see if there is an open connection. If not, create one.
            $this->connect();
        }
        $this->resetErrors();
        
        $this->queryId = @mysql_query($sql, $this->dbLink);

        if (!$this->queryId) {
            if ($this->beginWork) {
                $this->rollbackTransaction();
            }

            $this->setErrors('Unable to perform the query <b>' . $sql . '</b>.');
            if($debug){
                $this->showErrors();
            }
        }

        $this->prevId = 0;
        
        return $this->queryId;
        
    } // end function

    /**
     * Grabs the records as a array.
     * [edited by MoMad to support movePrev()]
     *
     * @param      none
     * @return     array of db records
     * @access     public
     */
    function fetchRow($fetchMode = 'object'){
        if (isset($this->queryId)) {
            $this->prevId++;
            return ($fetchMode == 'object') ? @mysql_fetch_object($this->queryId) : @mysql_fetch_assoc($this->queryId);
        } else {
            $this->setErrors('No query specified.');
        }
    } // end function
    
    function fetchRowSet(){
        if (isset($this->queryId)) {
            $rows = array();
            
            while($row = $this->fetchRow()){
                $rows[] = $row;
            }
            
            return $rows;
        } else {
            $this->setErrors('No query specified.');
        }
    } // end function

    /**
     * If the last query performed was an 'INSERT' statement, this method will
     * return the last inserted primary key number. This is specific to the
     * MySQL database server.
     *
     * @param        none
     * @return        int
     * @access        public
     * @scope        public
     * @since        version 1.0.1
     */
    function insert_id(){
        $this->lastInsertId = @mysql_insert_id($this->dbLink);

        if (!$this->lastInsertId) {
            $this->setErrors('Unable to get the last inserted id from MySQL.');
        }
        $this->disconnect();
        return $this->lastInsertId;
    } // end function
   
    /**
     * Counts the number of rows returned from a SELECT statement.
     *
     * @param      none
     * @return     Int
     * @access     public
     */
    
    function numrows(){
        $this->totalRecords = @mysql_num_rows($this->queryId);

        if (!$this->totalRecords) {
            $this->setErrors('Unable to count the number of rows returned');
            $this->totalRecords = 0;
        }

        return $this->totalRecords;
    } // end function

    /**
     * Checks to see if there are any records that were returned from a
     * SELECT statement. If so, returns true, otherwise false.
     *
     * @param      none
     * @return     boolean
     * @access     public
     */
    function resultExist(){
        if (isset($this->queryId) && ($this->numRows() > 0)) {
            return true;
        }

        return false;
    } // end function

    /**
     * Clears any records in memory associated with a result set.
     *
     * @param      Int $result
     * @return     void
     * @access     public
     */
    function clear($result = 0){
        if ($result != 0) {
            $t = @mysql_free_result($result);

            if (!$t) {
                $this->setErrors('Unable to free the results from memory');
            }
        } else {
            if (isset($this->queryId)) {
                $t = @mysql_free_result($this->queryId);

                if (!$t) {
                    $this->setErrors('Unable to free the results from memory (internal).');
                }
            } else {
                $this->setErrors('No SELECT query performed, so nothing to clear.');
            }
        }
    } // end function

    /**
     * Checks to see whether or not the MySQL server supports transactions.
     *
     * @param      none
     * @return     bool
     * @access     public
     */
    function serverHasTransaction(){
        $this->query('SHOW VARIABLES');

        if ($this->resultExist()) {
            while ($record = $this->fetchRow()) {
                if ($record->Variable_name == 'have_bdb' && $record->Value == 'YES') {
                    $this->transactionsCapable = true;
                    return true;
                }

                if ($record->Variable_name == 'have_gemini' && $record->Value == 'YES') {
                    $this->transactionsCapable = true;
                    return true;
                }

                if ($record->Variable_name == 'have_innodb' &&$record->Value == 'YES') {
                    $this->transactionsCapable = true;
                    return true;
                }
            }
        }

        return false;
    } // end function

    /**
     * Start a transaction.
     *
     * @param   none
     * @return  void
     * @access  public
     */
    function beginTransaction(){
        if ($this->transactionsCapable) {
            $this->query('BEGIN');
            $this->beginWork = true;
        }
    } // end function

    /**
     * Perform a commit to record the changes.
     *
     * @param   none
     * @return  void
     * @access  public
     */
    function commitTransaction(){
        if ($this->transactionsCapable) {
            if ($this->beginWork) {
                $this->query('COMMIT');
                $this->beginWork = false;
            }
        }
    }

    /**
     * Perform a rollback if the query fails.
     *
     * @param   none
     * @return  void
     * @access  public
     */
    function rollbackTransaction(){
        if ($this->transactionsCapable) {
            if ($this->beginWork) {
                $this->query('ROLLBACK');
                $this->beginWork = false;
            }
        }
    } // end function
    
    
    function getEscaped( $text ) {
        // Reverse magic_quotes_gpc/magic_quotes_sybase effects on those vars if ON.

        if (get_magic_quotes_gpc()) {
            if (ini_get('magic_quotes_sybase')) {
                $text        = str_replace( "''", "'", $text );
            } else {
                $text        = stripslashes($text);
            }
        } else {
            $text        = $text;
        }

        /*
        * Use the appropriate escape string depending upon which version of php
        * you are running
        */
        
        if (version_compare(phpversion(), '4.3.0', '<')) {
            $string = mysql_escape_string( $text );
        } else     {
            if(empty($this->dbLink))    $this->connect();
            $string = mysql_real_escape_string( $text , $this->dbLink );
        }

        return $string;
    }
    
    /**
     * "Smart" Escape String
     *
     * Escapes data based on type
     * Sets boolean and null types
     *
     * @access    public
     * @param    string
     * @return    mixed        
     */    
    function escape($str)
    {
        if (is_string($str))
        {
            $str = "'".$this->escape_str($str)."'";
        }
        elseif (is_bool($str))
        {
            $str = ($str === FALSE) ? 0 : 1;
        }
        elseif (is_null($str))
        {
            $str = 'NULL';
        }

        return $str;
    }

    /**
     * Escape String
     *
     * @access    public
     * @param    string
     * @param    bool    whether or not the string will be used in a LIKE condition
     * @return    string
     */
    function escape_str($str, $like = FALSE)    
    {    
        if (is_array($str))
        {
            foreach($str as $key => $val)
               {
                $str[$key] = $this->escape_str($val, $like);
               }
           
               return $str;
           }

        //if (function_exists('mysql_real_escape_string') AND is_resource($this->conn))
//        {
//            $str = mysql_real_escape_string($str, $this->conn);
//        }
//        /*elseif (function_exists('mysql_escape_string'))
//        {
//            $str = mysql_escape_string($str);
//        }*/
//        else
//        {
            $str = addslashes($str);
        //}
        
        // escape LIKE condition wildcards
        if ($like === TRUE)
        {
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
        }
        
        return $str;
    }
    
    function set_utf8(){ //MY~
        @mysql_query("SET NAMES 'utf8'", $this->dbLink);
    }
    
    //dem record theo SQL
    function count_records_SQL($SQL = ''){//MY~
        if(empty($SQL) || !$this->dbLink){
            return 0;
        }else{
            $this->query($SQL);
            $r = $this->fetchRow();
            foreach($r as $k => $v){
                return $v;
            }
        }
    }
    
    //dem record 
    function count_records($table = '', $where = ''){//MY~
        if(empty($table) || !$this->dbLink){
            return 0;
        }else{
            if(empty($where)){
                $clause = '';
            }else{
                $clause = ' WHERE ' . $where . ' ';
            }
            $SQL = "SELECT COUNT(*) AS `c` FROM {$table}  {$clause}"; 
            $this->query($SQL);
            $r = $this->fetchRow();
            return $r->c;
        }
    }
    function num_rows($sql){
        $num = $this->query($sql);
        return @mysql_num_rows($num);
    }
    
    // getRow
    function row($sql)
    {
        $this->query($sql);
        $this->disconnect();
        return $this->numrows() ? $this->fetchRow() : FALSE;
    }
    //get rows
    function result($sql)
    {
        $this->query($sql);
        $this->disconnect();
        return $this->numrows() ? $this->fetchRowSet() : array();
    }

    // insert data
    
    function str_insert($table, $keys, $values)
    {    
        return "INSERT INTO ".$table." (`".implode('`,`', $keys)."`) VALUES('".implode("','", $values)."')";
    }

    function insert($table, $values){
        $fieldnames  = $this->check_colum($table);
        foreach($values as $key=>$val)
        {
            $values[$key] = $this->escape_str($val);
            if (!in_array($key, $fieldnames)){
                die('Colum: '.$key.' not exit in Table '.$table);
            }            
            
        }
        $sql = $this->str_insert($table, array_keys($values), array_values($values));
        return $this->query($sql);
      
    }
    
    function check_colum($table_name){
        $result = $this->query("SHOW COLUMNS FROM ". $table_name);
        if (!$result) {
            die('Table name '.$table_name.' not exit');
        }
        $fieldnames=array();
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $fieldnames[] = $row['Field'];
            }
        }
        return $fieldnames;
    }

    
    function update($table_name, $form_data, $where_clause=''){
        $fieldnames  = $this->check_colum($table_name);
        $whereSQL = '';
        if(!empty($where_clause))
        {
            // not found, add key word
            
            $whereSQL = " WHERE ";
            $i = 1;
            foreach($where_clause as $key => $_value) {
                $whereSQL .= "`$key`= '$_value'";
                if ($i < count($where_clause)) { // last item should not include the AND
                    $whereSQL .= ' AND ';
                }
            $i++;
            }

        }
        // start the actual SQL statement
        $sql = "UPDATE ".$table_name." SET ";

        // loop and build the column /
        $sets = array();
        
        foreach($form_data as $column => $value)
        {
            $sets[] = "`".$column."` = '".$this->escape_str($value)."'";
            if (!in_array($key, $fieldnames)){
                die('Colum: '.$key.' not exit in Table '.$table_name);
            } 
        }
        $sql .= implode(', ', $sets);

        // append the where statement
        $sql .= $whereSQL;
        // run and return the query result
        return $this->query($sql);
    }

    
    // delete data 
    function delete($table, $where_clause)
    {
        $whereSQL = '';
        if(!empty($where_clause))
        {
            // check to see if the 'where' keyword exists
            //if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
            //{
                // not found, add key word
                $whereSQL = " WHERE ";
                $i = 1;
                foreach($where_clause as $key => $_value) {
                    $whereSQL .= "`$key`= '$_value'";
                    if ($i < count($where_clause)) { // last item should not include the AND
                        $whereSQL .= ' AND ';
                    }
                $i++;
                }
            //} else
            //{
                //$whereSQL = " ".trim($where_clause);
            //}
        }
        $sql = "DELETE FROM ".$table." ".$whereSQL;
        return $this->query($sql);
    }
    
    
} // end class