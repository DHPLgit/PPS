<?php

function GetJson()
{
    $jsonFile = file_get_contents('../public/uploads/dropdown.json');
    $data = json_decode($jsonFile);

    return $data;
}
function GetPaginationLinks($totalRecords, $perPage, $currentPage): string
    {

        $pager = \Config\Services::pager();
        
        $pagerLinks = $pager->makeLinks($currentPage, $perPage, $totalRecords, 'default_full');

        return $pagerLinks;
    }

function makeLinks($totalPages, $currentPage)
    {
        $links = '';
        $baseUrl = base_url('yourcontroller'); // Change to your controller's URL

        // Previous link
        if ($currentPage > 1) {
            $links .= '<li>' . anchor($baseUrl . '?page=' . ($currentPage - 1), 'Previous') . '</li>';
        }

        // Page number links
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $currentPage) ? 'active' : '';
            $links .= '<li class="' . $activeClass . '">' . anchor($baseUrl . '?page=' . $i, $i) . '</li>';
        }

        // Next link
        if ($currentPage < $totalPages) {
            $links .= '<li>' . anchor($baseUrl . '?page=' . ($currentPage + 1), 'Next') . '</li>';
        }

        return '<ul class="pagination">' . $links . '</ul>';
    }
