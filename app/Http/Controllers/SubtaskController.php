<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class SubtaskController extends CrudController{

    public function all($entity){
        parent::all($entity);

		$this->filter = \DataFilter::source(new \App\Subtask);
		$this->filter->add('body', 'Body', 'text');
		$this->filter->submit('search');
		$this->filter->reset('reset');
		$this->filter->build();

		$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID');
        $this->grid->add('user_id', 'User ID');
        $this->grid->add('team_id', 'Team ID');
        $this->grid->add('card_id', 'Card ID');
        $this->grid->add('body', 'Body');
		$this->grid->add('priority', 'Priority');
        $this->grid->add('checked', 'Checked');
        $this->grid->add('created_at', 'Created');
        $this->grid->add('updated_at', 'Updated');
		$this->addStylesToGrid();

        return $this->returnView();
    }

    public function  edit($entity){

        parent::edit($entity);

		$this->edit = \DataEdit::source(new \App\Subtask());
        $this->edit->label('Edit Subtask');
        $this->edit->add('body', 'Body', 'textarea');
        $this->edit->add('user_id', 'User', 'select')
            ->options(\App\User::lists("name", "id")
            ->all());
        $this->edit->add('team_id', 'Team', 'select')
            ->options(\App\Team::lists("name", "id")
            ->all());
        $this->edit->add('card_id', 'Card', 'select')
            ->options(\App\Card::lists("name", "id")
            ->all());
        $this->edit->add('priority', 'Priority', 'text');
        $this->edit->add('checked', 'Checked', 'checkbox');

        return $this->returnEditView();
    }
}
