<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class TopicPostController extends CrudController{

    public function all($entity){
        parent::all($entity);

		$this->filter = \DataFilter::source(new \App\TopicPost);
		$this->filter->add('body', 'Body', 'text');
		$this->filter->submit('search');
		$this->filter->reset('reset');
		$this->filter->build();

		$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID');
		$this->grid->add('user_id', 'User ID');
        $this->grid->add('team_id', 'Team ID');
        $this->grid->add('topic_id', 'Topic ID');
        $this->grid->add('topic_post_id', 'Topic Post ID');
        $this->grid->add('body', 'Body');
        $this->grid->add('created_at', 'Created');
        $this->grid->add('updated_at', 'Updated');
		$this->addStylesToGrid();

        return $this->returnView();
    }

    public function  edit($entity){

        parent::edit($entity);

        $this->edit = \DataEdit::source(new \App\TopicPost());
        $this->edit->label('Edit Topic Post');
        $this->edit->add('user_id', 'User', 'select')
            ->options(\App\User::lists("name", "id")
            ->all());
        $this->edit->add('team_id', 'Team', 'select')
            ->options(\App\Team::lists("name", "id")
            ->all());
        $this->edit->add('topic_id', 'Topic', 'select')
            ->options(\App\Topic::lists("name", "id")
            ->all());
        $this->edit->add('topic_post_id', 'Topic Post', 'select')
            ->option('', '-- No parent topic post --')
            ->options(\App\TopicPost::lists("body", "id")->all());
        $this->edit->add('body', 'Body', 'textarea');

        return $this->returnEditView();
    }
}
