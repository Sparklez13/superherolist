<?php

use SuperHeroList\app\Router;
use SuperHeroList\Controller;

Router::get('/index.php', Controller::class, 'index');

Router::post('/index.php', Controller::class, 'saveRecord');

Router::get('/index.php/records', Controller::class, 'getRecordsAll');

Router::delete('/index.php', Controller::class, 'deleteRecord');