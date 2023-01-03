<?php

namespace app\models\search;

use app\models\User;

class UserSearch extends User
{


    public function adminBrowse()
    {
        $query = self::find();

        $query->filterWhere(['like', 'name', $this->name])
            ->orFilterWhere(['like', 'surname', $this->name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'is_administrator', $this->is_administrator]);

        $query->orderBy('email');

        return $query;
    }
}