<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class TopicController extends CrudController{

    public function all($entity){
        parent::all($entity);

		$this->filter = \DataFilter::source(new \App\Topic);
		$this->filter->add('name', 'Name', 'text');
		$this->filter->submit('search');
		$this->filter->reset('reset');
		$this->filter->build();

		$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID');
        $this->grid->add('user_id', 'User ID');
        $this->grid->add('team_id', 'Team ID');
        $this->grid->add('name', 'Name');
        $this->grid->add('post_count', 'Post Count');
        $this->grid->add('like_count', 'Like Count');
        $this->grid->add('view_count', 'View Count');
        $this->grid->add('created_at', 'Created');
        $this->grid->add('updated_at', 'Updated');
		$this->addStylesToGrid();

        return $this->returnView();
    }

    public function  edit($entity){

        parent::edit($entity);

		$this->edit = \DataEdit::source(new \App\Topic());
		$this->edit->label('Edit Topic');
		$this->edit->add('name', 'Name', 'text');
        $this->edit->add('user_id', 'User', 'select')
            ->options(\App\User::lists("name", "id")
            ->all());
        $this->edit->add('team_id', 'Team', 'select')
            ->options(\App\Team::lists("name", "id")
            ->all());
        $this->edit->add('post_count', 'Post Count', 'text');
        $this->edit->add('like_count', 'Like Count', 'text');
        $this->edit->add('view_count', 'View Count', 'text');

        return $this->returnEditView();
    }
}
