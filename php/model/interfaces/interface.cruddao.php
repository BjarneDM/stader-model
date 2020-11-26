<?php namespace stader\model ;

interface ICrudDao
{
    /**
     * Store the new object and assign a unique auto-generated ID.
     */
    function create( Array $array ) ;

    /**
     * Return one object based upon select
     */
    function readOne( ...$args ) ;

    /**
     * Return all objects based upon select
     */
    function readAll( ...$args ) ;

    /**
     * Update the object's fields.
     */
    function update( int $id, string $key, $value ) ;

    /**
     * Delete the object from the database.
     */
    function delete( int $id ) ;
}

?>
