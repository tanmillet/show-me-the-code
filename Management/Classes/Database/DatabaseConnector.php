<?php

interface ICreatable{
    public function createByResult( $result );
}

class DatabaseConnector {
    const const_DatabaseHost = '127.0.0.1';
    const const_DataBaseUser = 'uo_aolinedu_user';
    const const_DataBasePassword = 'Q1w2sxd_AL';
    const const_DataBaseName = 'uo_aolinedu_db';

    private static $shared_instance = null;
    private $m_databaseConnector = null;

    private function __construct(){
        $this->m_databaseConnector = mysqli_connect( self::const_DatabaseHost, self::const_DataBaseUser, self::const_DataBasePassword, self::const_DataBaseName )
            or die( "Can NOT access your data temporarily". mysqli_connect_error());
    }

    function __destruct(){
        if ( !empty( $this->m_databaseConnector ) ){
            mysqli_close( $this->m_databaseConnector );
        }
    }

    public function queryStatementByGettingResult($statement,Array &$outResult){
        if ( !empty( $this->m_databaseConnector ) ){
            $this->m_databaseConnector->query("set character set 'utf8'");
            if ( $result = $this->m_databaseConnector->query( $statement ) ){
                $resultSet = array();
                while( $row = $result->fetch_array() ){
                    $eachResult = Array();
                    foreach ( $outResult as $eachKey => $value ){
                        $eachResult[ $eachKey ] = $row[ $eachKey ];
                    }
                    array_push( $resultSet, $eachResult );
                }
                $outResult = $resultSet;
                $result->close();
            }
        }
    }

    public function queryStatement($statement, ICreatable $creatable ){
        if ( !empty( $this->m_databaseConnector ) ){
            $this->m_databaseConnector->query("set character set 'utf8'");
            if ( $result = $this->m_databaseConnector->query( $statement ) ){
                $instance = $creatable->createByResult( $result );
                $result->close();
                return $instance;
            } else{
                return null;
            }
        } else{
            return null;
        }
    }

    public function queryExistenceStatement( $statement ){
        if ( !empty( $this->m_databaseConnector ) ){
            $this->m_databaseConnector->query("set character set 'utf8'");
            if ( $result = $this->m_databaseConnector->query( $statement ) ){
                $isExisted = false;
                if ( $result->num_rows > 0 ){
                    $isExisted = true;
                }
                $result->close();
                return $isExisted;
            } else{
                return false;
            }
        } else{
            return false;
        }
    }

    //
    public function executeStatement($state){
        if ( !empty( $this->m_databaseConnector ) ){
            $this->m_databaseConnector->query("set names 'utf8'");
            return ( $this->m_databaseConnector->query( $state ) === TRUE );
        } else{
            return false;
        }
    }

    // \param valueMaps a map
    public function executeInsert($table, array $valueMaps){
        if ( !empty( $table ) && !empty( $valueMaps ) ){
            $prefixString = "insert into $table (";
            $valuesString = 'values (';
            foreach ( $valueMaps as $valueKey => $value ){
                $prefixString .= "$valueKey ,";
                $valuesString .= "$value ,";
            }

            $prefixString = rtrim( $prefixString, ",");
            $prefixString .= ') ';
            $valuesString = rtrim( $valuesString, "," );
            $valuesString .= ')';

            return $this->executeStatement( $prefixString . $valuesString );
        } else{
            return false;
        }
    }

    // \param setValues a array
    public function executeUpdate($table, array $setValues, array $conditions){
        if ( !empty( $table ) && !empty( $setValues ) ){
            $queryString = "update $table set ";

            $valuesCountExcludeLast = count( $setValues ) - 1;
            foreach ( $setValues as $valueKey => $updateValue ){
                if ( $valueKey == $valuesCountExcludeLast ){
                    $queryString .= $updateValue;
                } else{
                    $queryString .= "$updateValue,";
                }
            }

            $conditionCountExcludeLast = count( $conditions ) - 1;
            if ( $conditionCountExcludeLast > -1 ){
                $queryString .= " where ";
                foreach ( $conditions as $conditionKey => $value ){
                    if ( $conditionKey == $conditionCountExcludeLast ){
                        $queryString .= $value;
                    } else{
                        $queryString .= "$value and ";
                    }
                }
            }

            return $this->executeStatement( $queryString );
        } else{
            return false;
        }
    }

    public function executeDelete($table, array $conditions){
        if ( !empty( $table ) && !empty( $conditions ) ){
            $queryString = "delete from $table where ";

            $conditionCountExcludeLast = count( $conditions ) - 1;
            foreach ( $conditions as $conditionKey => $value ){
                if ( $conditionKey == $conditionCountExcludeLast ){
                    $queryString .= $value;
                } else{
                    $queryString .= "$value and";
                }
            }
            return $this->executeStatement( $queryString );
        } else{
            return false;
        }
    }

    public static function getInstance()
    {
        if(! (self::$shared_instance instanceof self) ) {
            self::$shared_instance = new self();
        }
        return self::$shared_instance;
    }

    private function __clone() {}
}
