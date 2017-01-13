<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class CardController extends CrudController{

    public function all($entity){
        parent::all($entity);

        // /** Simple code of  filter and grid part , List of all fields here : http://laravelpanel.com/docs/master/crud-fields
		$this->filter = \DataFilter::source(new \App\Card);
		$this->filter->add('name', 'Name', 'text');
        $this->filter->add('description', 'Description', 'text');
		$this->filter->submit('search');
		$this->filter->reset('reset');
		$this->filter->build();

		$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID');
		$this->grid->add('user_id', 'User ID');
        $this->grid->add('name', 'Name');
        $this->grid->add('description', 'Description');
        $this->grid->add('blocked', 'Blocked');
        $this->grid->add('impact', 'Impact');
        $this->grid->add('created_at', 'Created');
        $this->grid->add('updated_at', 'Updated');
		$this->addStylesToGrid();

        return $this->returnView();
    }

    public function  edit($entity){

        parent::edit($entity);

		$this->edit = \DataEdit::source(new \App\Card());
		$this->edit->label('Edit Card');
		$this->edit->add('name', 'Name', 'text');
        $this->edit->add('description', 'Description', 'textarea');
        $this->edit->add('blocked', 'Blocked', 'checkbox');
        $this->edit->add('impact', 'Impact', 'text');

        return $this->returnEditView();
    }
}
