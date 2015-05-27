<?php

use helper\User;
use model\WidgetModel;

class WidgetController extends BaseController {

    function index(){
        try {
            $widgetModel = new WidgetModel();
            $widgets = $widgetModel->getEnabled();
            echo json_encode(['success' => true, 'data' => $widgets]);
        } catch (\Exception $e){
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    function getAll(){
        if (User::isModerator()){
            try {
                $widgetModel = new WidgetModel();
                $widgets = $widgetModel->getAll();
                echo json_encode(['success' => true, 'data' => $widgets]);
            } catch (\Exception $e){
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'permission' => "Access denied"]);
        }
    }

    function get($search){

    }

    function update($id){

    }

    function install(){
        try {
            $widgetModel = new WidgetModel();
            $widgets = $widgetModel->getAll();
            $widgets_index = [];
            foreach ($widgets as $widget) {
                $widgets_index[$widget['directory']] = $widget;
            }

            // Scan for widgets
            $files = scandir(root.'/../Widget/');
            $widgets = [];
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') continue;
                if (isset($widgets_index[$file])) continue;
                $widgets[] = $file;
            }

            foreach ($widgets as $widget) {
                $config = (include(root.'/../Widget/'.$file.'/package.php'));
                $widgetModel->create($config['name'], $file, 0);
            }
            echo json_encode(['success' => true, 'targets' => count($widgets)]);
        } catch (\Exception $e){
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}