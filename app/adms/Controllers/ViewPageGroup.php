<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ViewPageGroup as ModelsViewPageGroup;

class ViewPageGroup
{
    private ?array $data = [];

    public function index(int|string $id): void
    {
   
        if (! empty($id)) {
            $id = (int) $id;
            $group = new ModelsViewPageGroup();
            $groupDetails = $group->viewInfoGroup($id);
            if ($group->getResult()) {
              
                $this->data['viewPageGroup'] = $groupDetails;
                $this->viewGroupDetails();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Grupo não encontrado!</div>";
                header("Location: " . URL . "page-groups/index");
                exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Grupo não encontrado!</div>";
            header("Location: " . URL . "page-groups/index");
            exit;
        }
    }

    private function viewGroupDetails(): void
    {
        $view = new ConfigView("adms/Views/pageGroups/viewPageGroup", $this->data);
        $view->loadView();
    }
}