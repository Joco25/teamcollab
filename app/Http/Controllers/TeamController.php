<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class TeamController extends CrudController{

    public function all($entity){
        parent::all($entity);

		$this->filter = \DataFilter::source(new \App\Team);
		$this->filter->add('name', 'Name', 'text');
		$this->filter->submit('search');
		$this->filter->reset('reset');
		$this->filter->build();

		$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID');
		$this->grid->add('user_id', 'User ID');
        $this->grid->add('name', 'Name');
        $this->grid->add('created_at', 'Created');
        $this->grid->add('updated_at', 'Updated');
		$this->addStylesToGrid();

        return $this->returnView();
    }

    public function  edit($entity){

        parent::edit($entity);

		$this->edit = \DataEdit::source(new \App\Team());
		$this->edit->label('Edit Team');
		$this->edit->add('name', 'Name', 'text');
        $this->edit->add('user_id', 'User', 'select')
            ->options(\App\User::lists("name", "id")->all());

        return $this->returnEditView();
    }
}
