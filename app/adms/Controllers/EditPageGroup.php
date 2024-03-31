<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\EditPageGroup as ModelsEditPageGroup;

class EditPageGroup
{
    private ?array $data;

    public function index(string|int $id)
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(! empty($id) && empty($formData['sendEditPageGroup'])) {
            $id = (int) $id;
            $editPageGroup = new ModelsEditPageGroup();
            $details = $editPageGroup->viewInfoGroup($id);
            
            if ($editPageGroup->getResult()) {
                $this->data['editPageGroup'] = $details;
                $this->viewEditPageGroup();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Grupo não encontrado!</div>";
                header("Location: " . URL . "page-groups/index");exit;
            }
        }
        else {
            $this->editPageGroup($formData);
        }
    }

    private function viewEditPageGroup(): void
    {
        $view = new ConfigView("adms/Views/pageGroups/editPageGroup", $this->data);
        $view->loadView();
    }

    private function editPageGroup(?array $formData): void
    {
        if (! empty($formData['sendEditPageGroup'])) {
            $group = new ModelsEditPageGroup();
            $group->edit($formData);
            if ($group->getResult()) {
                header("Location:" . URL . "view-page-group/index/{$formData['id']}");exit;
            }
            else {
                $this->data['editPageGroup'] = $formData;
                $this->viewEditPageGroup();
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Grupo não encontrado!</div>";
            header("Location: " . URL . "page-groups/index");exit;
        }
    }
}