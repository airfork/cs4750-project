<?php

class Articles extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('article_model');
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->library('encryption');
    }

    // Checkout article
    public function checkout() {
        if (!$this->validate()) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You need to be signed in to checkout an article/journal', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if ($this->validate_lib()) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You cannot checkout items as a Librarian', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $author = $this->input->post('author');
        $title = $this->input->post('title');
        $pubDate = $this->input->post('pubDate');
        $checked_out = $this->article_model->checked_out($author, $title, $pubDate);
        if ($checked_out['count'] == 1) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'This article/journal has already been checked out', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $id = $this->encryption->decrypt($_SESSION['id']);
        $checkoutCount = $this->article_model->checkout_count($id);
        if ($checkoutCount['count'] >= 3) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You cannot checkout any more articles/journals', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $this->article_model->checkout($id, $author, $title, $pubDate);
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
    }

    public function deadline() {
        $id = $this->encryption->decrypt($_SESSION['id']);
        $data['deadline'] = $this->article_model->get_aj_deadline($id);
        $this->load->view('articles/deadlines', $data);
    }

    public function history() {
        $id = $this->encryption->decrypt($_SESSION['id']);
        $data['hist'] = $this->article_model->get_aj_hist($id);
        $this->load->view('articles/history', $data);
    }

    public function download_ajhist() {
        $id = $this->encryption->decrypt($_SESSION['id']);
        $this->article_model->get_aj_hist($id, TRUE);
        $this->load->helper('download');
        force_download('aj_checkout.csv', NULL);
    }

    private function validate() : bool {
        if (empty($_SESSION['id'])) {
            return false;
        }
        return true;
    }

    // Returns true if user is librarian
    private function validate_lib() {
        if (empty($_SESSION['lib'])) {
            return false;
        }
        return true;
    }


    private function insert_article(){
        if (!$this->validate()) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You need to be signed in to insert a book', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->validate_lib()) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You cannot insert items as a Librarian', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $isbn = $this->input->post('article');
        $this->book_model->insert_article($id, $isbn);
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));

    }

    private function delete_article(){
        if (!$this->validate()) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You need to be signed in to delete a book', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->validate_lib()) {
            header('Content-Type: application/json');
            echo json_encode(array('issue' => 'You cannot delete items as a Librarian', 'valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $isbn = $this->input->post('article');
        $this->book_model->delete_article($id, $isbn);
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));


    }




}