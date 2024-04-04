<?php

namespace App\adms\Models\helpers;

class Pagination
{
    private int $page; 
    private int $limit;
    private int $offset;
    private ?string $result;
    private int $totalPages;
    private int $count;
    private const NUM_MAX_LINKS_VISIBLE = 2;

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function getOffset(): string
    {
        return $this->offset;
    }

    public function __construct(private string $link, private ?string $var = null)
    {
    }
    
    public function condiction(int $page, int $limit): void
    {
        $this->page = $page ? (int) $page : 1;
        $this->limit = $limit;
        $this->offset = (int) ($this->page * $this->limit) - $this->limit;
    }

    public function paginate(int $countOfDb): void
    {
        $this->count = (int) $countOfDb;  
        $this->pageInstruction(); 
    }

    /*
    * Divide o total de registros pelo limite de registros por página para obter o total de páginas
    */
    private function pageInstruction(): void
    {
        $this->totalPages = ceil($this->count / $this->limit);
        $this->totalPages >= $this->page 
            ? $this->layoutPagination() 
            : header("Location: {$this->link}");
    }

    private function layoutPagination(): void
    {
        $this->result = "<ul>";
        $this->result .= "<li><a href='{$this->link}/1{$this->var}'>Primeira</a></li>";

        for ($beforePage = $this->page - self::NUM_MAX_LINKS_VISIBLE; $beforePage <= $this->page - 1; $beforePage++) {
            if ($beforePage >= 1) {
                $this->result .= "<li><a href='{$this->link}/{$beforePage}{$this->var}'>{$beforePage}</a></li>";
                
            }
        }

        $this->result .= "<li>{$this->page}</li>"; 

        for ($afterPage = $this->page + 1; $afterPage <= $this->page + self::NUM_MAX_LINKS_VISIBLE; $afterPage++) {
            if ($afterPage <= $this->totalPages) {
                $this->result .= "<li><a href='{$this->link}/{$afterPage}{$this->var}'>{$afterPage}</a></li>";
            }
        }

        $this->result .= "<li><a href='{$this->link}/{$this->totalPages}{$this->var}'>Última</a></li>";   
        $this->result .= "</ul>";
    }

}