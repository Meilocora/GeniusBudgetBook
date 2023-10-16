<?php 

namespace App\Entry;

class EntryModel {

    public int $id;
    public string $category;
    public string $title;
    public float $amount;
    public string $dateslug;
    public string $comment;
    public int $income;
    public int $fixedentry;
}