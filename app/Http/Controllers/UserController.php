<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class UserController extends CrudController{

    public function all($entity){
        parent::all($entity);

		$this->filter = \DataFilter::source(new \App\User);
		$this->filter->add('name', 'Name', 'text');
        $this->filter->add('email', 'Email', 'text');
		$this->filter->submit('search');
		$this->filter->reset('reset');
		$this->filter->build();

		$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID');
        $this->grid->add('name', 'Name');
        $this->grid->add('email', 'Email');
        $this->grid->add('created_at', 'Created');
        $this->grid->add('updated_at', 'Updated');
		$this->addStylesToGrid();

        return $this->returnView();
    }

    public function  edit($entity){

        parent::edit($entity);

        /* Simple code of  edit part , List of all fields here : http://laravelpanel.com/docs/master/crud-fields */
		$this->edit = \DataEdit::source(new \App\User());
		$this->edit->label('Edit User');
		$this->edit->add('name', 'Name', 'text')->rule('required');
        $this->edit->add('email', 'Email', 'text')->rule('required');
        $this->edit->add('password', 'Password', 'password')->rule('required');

        return $this->returnEditView();
    }
}
