<?php 

namespace App\Controller;

use App\Controller\EntryController;
use App\Entry\EntryRepository;
use App\Controller\UsersController;

class CustomOverviewController extends AbstractController{

    public function __construct(
        protected EntryController $entryController,
        protected EntryRepository $entryRepository,
        protected UsersController $usersController) {}

    public function showCustomOverview($navRoutes, $colorTheme, $userShortcut, $cTimeinterval, $cStartDate, $cEndDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cTitles, $cTitleQuery, $cAmounts, $fromAmount, $toAmount, $cComments, $cCommentQuery, $cSortingProperty, $cSort, $currentPage, $perPage) {
        $startDate = $this->giveInterval($cTimeinterval, $cStartDate, $cEndDate)[0];
        $endDate = $this->giveInterval($cTimeinterval, $cStartDate, $cEndDate)[1];
        $timeSpan = calculateTimespanDays($startDate, $endDate);
        $categories = $this->giveCategories();
        $maxAmount = $this->entryController->highestAmount();

        $entries = $this->giveEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        $sortButtons = $this->entryController->sortButtons($cSort, 'custom-overview');
        $countEntries = $this->countEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        
        $numPages = (int) ceil($countEntries / $perPage);

        $this->render('budget-book/custom-overview', [
            'navRoutes' => $navRoutes,
            'colorTheme' => $colorTheme,
            'userShortcut' => $userShortcut,
            'cTimeinterval' => $cTimeinterval,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'timeSpan' => $timeSpan,
            'cEntryType' => $cEntryType,
            'cFixation' => $cFixation,
            'cCategories' => $cCategories,
            'cCategoryQuery' => $cCategoryQuery,
            'categories' => $categories,
            'cTitles' => $cTitles,
            'cTitleQuery' => $cTitleQuery,
            'cAmounts' => $cAmounts,
            'maxAmount' => $maxAmount,
            'fromAmount' => $fromAmount,
            'toAmount' => $toAmount,
            'cComments' => $cComments,
            'cCommentQuery' => $cCommentQuery,
            'sortButtons' =>$sortButtons,
            'entries' => $entries,
            'numPages' => $numPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'countEntries' => $countEntries,
        ]);
    }

    public function giveInterval($cTimeinterval, $startDate, $endDate) {
        switch ($cTimeinterval) {
            case 'YTD':
                $startDate = date('Y') . '-01-01';
                $endDate = date('Y-m-d');
                break;
            case 'YoY':
                $startDate = date('Y')-1 . '-' . date('m-d');
                $endDate = date('Y-m-d');
                break;
            case 'All':
                $startDate = $this->entryController->dateFirstEntry();
                $endDate = date('Y-m-d');
            case 'Custom':
                break;
        }
        return [$startDate, $endDate];
    }

    public function giveCategories() {
        $rawCategories = $this->usersController->fetchUserCats();
        $categories = [];
        foreach ($rawCategories['revcats'] as $category) {
            if($category !== '') $categories[] = $category;
        }
        foreach ($rawCategories['expcats'] as $category) {
            if($category !== '') $categories[] = $category;
        }
        return $categories;
    }

    public function giveEntries($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort) {
        $queryArray = $this->generateQueryArray($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort);
        $queryString = implode('', $queryArray);
        return $this->entryRepository->fetchCustomQuery($queryString);
    }

    public function generateQueryArray($startDate, $endDate, $entryType, $fixation, $entryCategory, $categoryQuery, $amounts, $fromAmount, $toAmount, $titles, $titleQuery, $comments, $commentQuery, $perPage, $currentPage, $sortingProperty, $sort) {
        $queryArray = [];
        $queryArray[] = "WHERE `dateslug` BETWEEN '{$startDate}' AND '{$endDate}'";
        switch ($entryType) {
            case 'All':
                break;
            case 'revenues':
                $queryArray[] = " AND `income` = 1 ";
                break;
            case 'expenditures':
                $queryArray[] = " AND `income` = 0 ";
                break;
        }
        switch ($fixation) {
            case 'AllFixations':
                break;
            case 'fixed':
                $queryArray[] = " AND `fixedentry` = 1 ";
                break;
            case 'unfixed':
                $queryArray[] = " AND `fixedentry` = 0 ";
                break;
        }
        switch ($entryCategory) {
            case 'allCategories':
                break;
            case 'certainCategory':
                $queryArray[] = " AND `category` = '{$categoryQuery}' ";
                break;
        }
        switch ($amounts) {
            case 'allAmounts':
                break;
            case 'Custom':
                $queryArray[] = " AND `amount` BETWEEN {$fromAmount} AND {$toAmount} ";
                break;
        }
        switch ($titles) {
            case 'certainTitle':
                $queryArray[] = " AND `title` REGEXP '.*{$titleQuery}.*'";
        }
        switch ($comments) {
            case 'noComments': 
                $queryArray[] = " AND `comment` = ''";
            case 'certainComment': 
                $queryArray[] = " AND `comment` REGEXP '.*{$commentQuery}.*'";
        }
        if(preg_match('/^.*Asc$/', $sort)) {
            $sortMode =  'Asc';
        } elseif (preg_match('/^.*Desc$/', $sort)) {
            $sortMode =  'Desc';
        }
        $limit = $perPage;
        $offset = ($currentPage - 1) * $perPage;
        $queryArray[] = " ORDER BY `{$sortingProperty}` {$sortMode} LIMIT {$limit} OFFSET {$offset}";
        return $queryArray;
    }

    public function countEntries($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort) {
        $queryArray = $this->generateQueryArray($startDate, $endDate, $cEntryType, $cFixation, $cCategories, $cCategoryQuery, $cAmounts, $fromAmount, $toAmount, $cTitles, $cTitleQuery, $cComments, $cCommentQuery, $perPage, $currentPage, $cSortingProperty, $cSort);
        array_pop($queryArray);
        $queryString = implode("", $queryArray);
        return $this->entryRepository->countCustomEntries($queryString);
    }

    public function filterEntries($rawEntries, $filterCriteria, $regex) {
        $entries = [];
        switch ($filterCriteria) {
            case 'title':
                foreach ($rawEntries as $entry) {
                    if(preg_match("/.*{$regex}.*/", $entry->title)) $entries[] = $entry;
                }   
                break;
            case 'noComment':
                foreach ($rawEntries as $entry) {
                    if($entry->comment === $regex) $entries[] = $entry;
                }
                break;
            case 'comment':
                foreach ($rawEntries as $entry) {
                    if(preg_match("/.*{$regex}.*/", $entry->comment)) $entries[] = $entry;
                }
                break;
            default:
                $entries = $rawEntries;
        }
        return $entries;
    }
}