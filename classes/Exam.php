<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of exam
 *
 * @author memad
 */
class Exam implements Exam_interface {

    private $url;
    private $page;
    private $questions;
    private $question_number;
   
    
    function getQuestion_number() {
        return count($this->questions);
    }

        
    function getPage() {
        return $this->page;
    }

    
    
    public function __construct() {
        $this->url = Helper::get_current_Page_URL();
        $this->page = ($this->get_current_page_index() > 0) ? (int) $this->get_current_page_index() : 1;
        $this->questions = $this->get_questions();
    }

    public function load_exam_page($page) {
        if (isset($this->questions[$page - 1])) {
            return $this->questions[$page - 1];
        } else {

            throw new Exception("Question dosn't exist");
        }
    }

    public function move_previous() {
        $current_url = explode("?", $this->url)[0];
        $previous_page = (int) $this->page - 1;
        $previous_page = ($previous_page > 0) ? $previous_page : 1;
        return $current_url . "?" . "page=$previous_page";
    }

    public function move_next() {
        $current_url = explode("?", $this->url)[0];
        $next_page = (int) $this->page + 1;

        return $current_url . "?" . "page=$next_page";
    }

    private function get_current_page_index() {
        $url_parts = explode("?", $this->url);
        $query_string = $url_parts[1];

        if (!empty($query_string) || !strstr("page", $query_string)) {

            $query_string_array = explode("=", $query_string);


            return (int) $query_string_array[1];
        } else
            return 1;
    }

    private function get_questions() {
        $lines = file(exam_file);
        $questions = array();
        foreach ($lines as $line) {

            if (substr($line, 0, 1) === "Q") {
                if (isset($new_mcquestion)) {
                    $questions[] = $new_mcquestion;
                }
                $new_mcquestion = new MCQuestion($line);
            } elseif (substr($line, 0, 1) === "*") {
                $new_tofquestion = new TrueOrFalseQuestion(str_replace("*", "", $line));
                $questions[] = $new_tofquestion;
            } else {
                $new_mcquestion->Add_an_Option($line);
            }
        }
        return $questions;
    }

}
