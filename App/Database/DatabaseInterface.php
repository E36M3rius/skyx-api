<?php

namespace App\Database;

interface DatabaseInterface
{

    /**
     * Abstraction for get
     */
    public function get();

    /**
     * Abstraction for set
     */
    public function add();

    /**
     * Abstraction for delete
     */
    public function delete();

    /**
     * Abstraction for update
     */
    public function update();

}
