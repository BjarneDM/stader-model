<?php namespace stader\model ;

interface ICrudDao
{
    /**
     * Store the new object and assign a unique auto-generated ID.
     */
    function create( $object ) ;

    /**
     * Return one object based upon select
     */
    function readOne( $object ) ;

    /**
     * Return all objects based upon select
     */
    function readAll( $object ) ;

    /**
     * Update the object's fields.
     */
    function update( $object , Array $diffValues ) ;

    /**
     * Delete the object from the database.
     */
    function delete( $object ) ;
}

?>
