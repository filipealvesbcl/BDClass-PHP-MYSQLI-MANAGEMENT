<?php

/**
 * BD - Simple (easy-use) & Powerfull database management class.
 *
 * @author Filipe Alves (ch) <filipealvesbcl@gmail.com>
 * @version 1.2
 * @package ch-utils
 * @category database
 * @copyright Copyright (c) 2015, Filipe Alves
 *
 * @link https://github.com/filipealvesbcl/BDClass-PHP-MYSQLI-MANAGEMENT/
 *
 */


class BD {
    #Database Configs
    const HOSTNAME = 'localhost';            // HOSTNAME (eg: '127.0.0.1' , 'filipealves.net')
    const USERNAME = 'user';                 // DATABASE USER
    const PASSWORD = 'password';             // USER PASSWORD
    const DATABASE = 'db_example';           // DEFAULT DATABASE
    const CHARSET  = 'utf8';                 // DATABASE CHARSET
    #Error Reporting Configs
    const NEED_CONSTRUCT = TRUE;             // REQUIRE OBJECT CREATION (TRUE or FALSE)
    const ERROR_LANG  = 'EN';                // ERROR LANG (MUST BE ADDED IF NOT 'PT' OR 'EN')
    const ERROR_LOG = TRUE;                  // SAVE ON APACHE ERROR LOG (TRUE or FALSE)
    const NOTIFY_ADMIN_BY_EMAIL = TRUE;      // SEND ERRORS BY EMAIL TO THE WEBMASTER, 'ADM_EMAIL' MUST BE CONFIGURED FOR TRUE (TRUE or FALSE)
    const ADM_EMAIL = 'filipealvesbcl@gmail.com';// WEBMASTER EMAIL FOR RECEIVE THE ERRORS
    #Escape Configs
    const ESCAPE_FLAGS = ENT_QUOTES;         // DATA ESCAPE FLAGS (RECOMENDED: ENT_QUOTES)
    const ESCAPE_CHARSET = 'UTF-8';          // DATA ESCAPE CHARSET (RECOMENDED: SAME OF CHARSET ABOVE)

    /*************************************************/
    /* DO NOT CHANGE ANYTHING BELOW FOR GOOD WORKING */
    /*************************************************/

    private $_file_name         = __FILE__;  // CURRENT CLASS PATH FOR ERROR REPORTING
    private $_is_connected      = FALSE;     // TRUE or FALSE
    private $_connection        = NULL;      // CURRENT CONNECTION LINK
    private $_last_query        = NULL;      // LAST QUERY RESULT
    private $_last_inserted_id  = NULL;      // INSER QUERY LAST INSERTED ID

    /**
     * BD constructor.
     *
     * Creates the connection link to the database.
     *
     */
    public function __construct(){
        $connection = mysqli_connect(self::HOSTNAME,self::USERNAME,self::PASSWORD,self::DATABASE);

        if(mysqli_connect_errno()){
            echo $this->get_error(1,mysqli_connect_error(), __LINE__-3);
            die;
        }
        else{
            $this->_connection = $connection;
            $this->_is_connected = TRUE;
        }

        mysqli_set_charset($this->_connection,self::CHARSET) or die($this->get_error(2,mysqli_error($this->_connection),__LINE__));

        return $connection;
    }

    #region MYSQLI FUNCTIONS

    /**
     * Does a SQL query.
     *
     * @param $sql
     * @return bool|mysqli_result
     */
    public function query($sql){
        $this->check_connection(__LINE__);

        $result = mysqli_query($this->_connection,$sql) or die($this->get_error(3,mysqli_error($this->_connection), __LINE__));

        $this->_last_query = $result;
        $this->_last_inserted_id = mysqli_insert_id($this->_connection);

        return $result;
    }

    /**
     * Returns the fetch_array of a query (numeric & associative array).
     *
     * @param null $query
     * @return array|null
     */
    public function fetch_array($query = NULL){
        $this->check_connection(__LINE__);

        $query = ($query == NULL) ? $this->_last_query : $query;

        $result = mysqli_fetch_array($query);

        return $result;
    }

    /**
     * Returns the fetch_assoc of a query (associative array).
     *
     * @param null $query
     * @return array|null
     */
    public function fetch_assoc($query = NULL){
        $this->check_connection(__LINE__);

        $query = ($query == NULL) ? $this->_last_query : $query;

        $result = mysqli_fetch_assoc($query);

        return $result;
    }

    /**
     * Returns the fetch_row of a query (numeric array).
     *
     * @param null $query
     * @return array|null
     */
    public function fetch_row($query = NULL){
        $this->check_connection(__LINE__);

        $query = ($query == NULL) ? $this->_last_query : $query;

        $result = mysqli_fetch_row($query);

        return $result;
    }

    /**
     * Returns the number of rows of last query (ex: select).
     *
     * @param null $query
     * @return int
     */
    public function num_rows($query = NULL){
        $this->check_connection(__LINE__);

        $query = ($query == NULL) ? $this->_last_query : $query;

        $result = mysqli_num_rows($query);

        return (is_int($result)) ? $result : 0;
    }

    /**
     * Returns the last inserted id of the last insert query.
     *
     * @return int|null
     */
    public function last_inserted_id(){
        $this->check_connection(__LINE__);

        return $this->_last_inserted_id;
    }

    /**
     * Closes the connection to the database.
     */
    public function close(){
        if($this->_is_connected == TRUE){
            mysqli_close($this->_connection);
            $this->_is_connected = FALSE;
        }
    }

    #endregion

    /**
     * Escape data from SQL Injection and XSS Injection.
     *
     * @param $data array|string
     * @param $flags
     * @return array|string
     */
    public static function escape($data, $flags = self::ESCAPE_FLAGS){
        $con = new BD();

        if(is_array($data)){
            $array = $data;

            unset($data);

            $data = [];

            foreach($array as $key => $value){
                if(is_array($value)){
                    foreach($value as $item => $sub_item){
                        $item   = htmlspecialchars($item,$flags, self::ESCAPE_CHARSET);
                        $sub_item = htmlspecialchars($sub_item,$flags, self::ESCAPE_CHARSET);

                        $item    = mysqli_real_escape_string($con->_connection, $item);
                        $sub_item  = mysqli_real_escape_string($con->_connection, $sub_item);

                        $data[$key][$item] = $sub_item;
                    }
                }
                else{
                    $key   = htmlspecialchars($key,$flags, self::ESCAPE_CHARSET);
                    $value = htmlspecialchars($value,$flags, self::ESCAPE_CHARSET);

                    $key    = mysqli_real_escape_string($con->_connection, $key);
                    $value  = mysqli_real_escape_string($con->_connection, $value);

                    $data[$key] = $value;
                }
            }
        }
        else{
            $data = htmlspecialchars($data,$flags, self::ESCAPE_CHARSET);
            $data = mysqli_real_escape_string($con->_connection,$data);
        }

        $con->close();

        return $data;
    }

    /**
     * Generates the html message of an error.
     *
     * @param $id_error
     * @param null $mysqli_error
     * @param string $error_line
     * @return string
     */
    private function get_error($id_error, $mysqli_error = NULL, $error_line = ''){
        /**
         * Lista de erros possíveis (retorna os erros)
         */

        $errors = [
            'PT' => [
                1 => '<br />Não foi possível estabelecer conexão à base de dados!<br>',
                2 => '<br />Não foi possível definir o charset da conexão!',
                3 => '<br />Não foi possível executar a query!',
                5 => '<br />Não foi possível executar esta função pois a conexão à base de dados não foi estabelecida!'
            ],
            'EN' => [
                1 => '<br />Could not establish database connection!<br>',
                2 => '<br />Could not set the charset of the connection!',
                3 => '<br />Could not execute the query!',
                5 => '<br />Could not perform this function because the database connection was not established!'
            ]
        ];
        
        $error = "
            <div style='border: 1px solid red; z-index: 999999;padding: 15px; width: 500px'>
                <h1 style='font-family: Arial'>BDClass ERROR #{$id_error}</h1>
                <hr>
                <strong style='font-family: Arial'>System message:</strong> <i style='font-family: Arial'>{$errors[self::ERROR_LANG][$id_error]}</i>
                <br>
                <i style='font-family: Arial'>
                    Filename: {$this->_file_name}
                    <br>
                    Line: {$error_line}
                </i>";

        if($mysqli_error !== NULL){
            $error .= "
                <hr><strong style='font-family: Arial'>MySQLi error:</strong> <i style='font-family: Arial'>{$mysqli_error}</i>
            </div>";
        }


        if(self::ERROR_LOG == TRUE) {
            if($mysqli_error !== NULL){
                $error_message = "BD ERROR: {$errors[self::ERROR_LANG][$id_error]} | ON LINE: {$error_line} | MySQLi ERROR: {$mysqli_error}";
            }
            else{
                $error_message = "BD ERROR: {$errors[self::ERROR_LANG][$id_error]} | ON LINE: {$error_line}";
            }

            error_log($error_message, 0);

            if(self::NOTIFY_ADMIN_BY_EMAIL == TRUE){
                error_log($error_message, 1,self::ADM_EMAIL);
            }
        }

        return $error;
    }

    /**
     * Checks if the connection it's ok ;)
     *
     * @param $error_line
     */
    private function check_connection($error_line){
        if($this->_is_connected == FALSE){
            if(self::NEED_CONSTRUCT == FALSE){
                self::__construct();
            }
            else{
                die($this->get_error(5,NULL, $error_line));
            }
        }
    }
}
