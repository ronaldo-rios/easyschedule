<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\EditAccessLevel as ModelsEditAccessLevel;

class EditAccessLevel
{
    private $data;

    public function index(string|int $id)
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(! empty($id) && empty($formData['sendEditAccessLevel'])) {
            $id = (int) $id;
            $accessLevel = new ModelsEditAccessLevel();
            $details = $accessLevel->viewInfoAccessLevel($id);
            
            if ($accessLevel->getResult()) {
                $this->data['editAccessLevel'] = $details;
                $this->viewEditAccessLevel();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de acesso não encontrado!</div>";
                header("Location: " . URL . "access-levels/index");exit;
            }
        }
        else {
            $this->editAccessLevel($formData);
        }
    }

    private function viewEditAccessLevel(): void
    {
        $view = new ConfigView("adms/Views/accesslevel/editAccessLevel", $this->data);
        $view->loadView();
    }

    private function editAccessLevel(?array $formData): void
    {
        if (! empty($formData['sendEditAccessLevel'])) {
            $accessLevel = new ModelsEditAccessLevel();
            $accessLevel->edit($formData);
            if ($accessLevel->getResult()) {
                header("Location:" . URL . "access-levels/index");exit;
            }
            else {
                $this->data['editAccessLevel'] = $formData;
                $this->viewEditAccessLevel();
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de acesso não encontrado!</div>";
            header("Location: " . URL . "access-levels/index");exit;
        }
    }
}