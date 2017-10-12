<?php

namespace App\Http\Controllers;

use Chrisbjr\ApiGuard\Http\Controllers\ApiGuardController;
use App\Models\Test;

class Testcontroller extends ApiGuardController {

    public function all() {
        $books = Test::getUsers();
        return $this->response = $books;
    }

    public function show($id) {
        try {
            $book = Test::findOrFail($id);
            return $this->response = $book;
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound();
        }
    }

}
