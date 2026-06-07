<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError()
    {
        return $this->render('error');
    }
    
    public function actionInfo()
{
    $this->layout = 'main'; // pour garder ton header/footer
    return $this->render('info');
}

}
