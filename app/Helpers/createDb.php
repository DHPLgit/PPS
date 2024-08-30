<?php

if (!function_exists("reverse_string")) {
    function reverse_string(string $string)
    {
        return strrev($string);
    }
}
if(!function_exists('createDatabase')){
    function createDatabase($dbname = '') {
       //new DB creation for Tenant details
       $db = db_connect();
       $db->query('CREATE DATABASE `'.$dbname."`");
       $db->query('USE `'.$dbname."`");

       //new Table creation for Tenant Details
       $nps_answer_table = "CREATE TABLE `nps_answers_details` (
           `answer_id` int(11) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
           `answer_name` text NOT NULL,
           `description` text NOT NULL,
           `created_id` int(11) NOT NULL,
           `info_details` varchar(120) NOT NULL,
           `status` enum('0','1') NOT NULL COMMENT '0-static, 1-dynamic',
           `created_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_answer_table);

       $nps_campaign_details = "CREATE TABLE `nps_campaign_details` (`id` int(11) NOT NULL,
           `category_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
           `user_id` int(11) NOT NULL,
           `question_id` varchar(55) NOT NULL,
           `answer_id` varchar(55) NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_campaign_details);

       $emailsendlist = "CREATE TABLE `nps_email_send_list` (
           `id` int(11) NOT  NULL AUTO_INCREMENT PRIMARY KEY,
           `survey_id` int(11) NOT NULL,
           `user_id` int(11) NOT NULL,
           `subject` varchar(120) NOT NULL,
           `email_list` text NOT NULL,
           `message` blob NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($emailsendlist);

       $nps_external_contacts = "CREATE TABLE `nps_external_contacts` (
           `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
           `created_by` int(11) NOT NULL COMMENT 'User_id',
           `name` varchar(120) NOT NULL,
           `firstname` varchar(120) NOT NULL,
           `lastname` varchar(120) NOT NULL,
           `contact_details` text NOT NULL,
           `email_id` varchar(120) NOT NULL,
           `status` enum('1','0') NOT NULL DEFAULT '1',
           `created_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_external_contacts);

       $nps_login_user_info = "CREATE TABLE `nps_login_user_info` (
           `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
           `user_id` int(11) NOT NULL,
           `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
           `logout_time` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_login_user_info);

       $nps_question_details = "CREATE TABLE `nps_question_details` (
           `question_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
           `question_name` text NOT NULL,
           `description` text NOT NULL,
           `info_details` varchar(120) NOT NULL,
           `other_option` text NOT NULL,
           `priority` int(11) NOT NULL,
           `user_id` int(11) NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_question_details);


       $nps_survey_details = "CREATE TABLE `nps_survey_details` (
           `campaign_id` int(11) NOT NULL,
           `user_id` int(11) NOT NULL,
           `campaign_name` varchar(120) NOT NULL,
           `question_id_1` int(11) NOT NULL,
           `question_id_2` int(11) NOT NULL,
           `answer_id_2` varchar(200) NOT NULL COMMENT 'comma separated answer Id''s',
           `question_id_3` int(11) NOT NULL,
           `answer_id_3` varchar(200) NOT NULL COMMENT 'comma separated answer Id''s',
           `question_id_4` int(11) NOT NULL,
           `answer_id_4` varchar(200) NOT NULL COMMENT 'comma separated answer Id''s',
           `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_survey_details);

       $nps_survey_response = "CREATE TABLE `nps_survey_response` (
           `id` int(11) NOT  NULL AUTO_INCREMENT PRIMARY KEY,
           `campaign_id` int(11) NOT NULL,
           `user_id` int(11) NOT NULL,
           `email` varchar(120) NOT NULL,
           `question_id` int(11) NOT NULL,
           `answer_id` varchar(55) NOT NULL,
           `question_id2` int(11) NOT NULL,
           `answer_id2` TEXT NOT NULL,
           `mail_status` varchar(120) NOT NULL,
           `ip_details` varchar(120) NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_survey_response);

       $nps_users = "CREATE TABLE `nps_users` (
           `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
           `firstname` varchar(120) DEFAULT NULL,
           `lastname` varchar(55) NOT NULL,
           `username` varchar(120) DEFAULT NULL,
           `tenant_id` int(11) NOT NULL,
           `email` varchar(120) NOT NULL,
           `phone_no` varchar(120) NOT NULL,
           `role` enum('admin','user') NOT NULL,
           `password` varchar(240) NOT NULL,
           `logo_update` text NOT NULL,
           `status` enum('1','0') NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
           `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
       $db->query($nps_users);
       $db->close();
       return "New Database created for given Tenant";
    }
}