<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SL_Controller extends CI_Controller
{
    protected $model;
    protected $category_model = null;
    protected $comment_model = null;
    protected $return_data;
    protected $comment = true;
    protected $show_first_category = false;
    protected $format = 'html';

    // 날짜데이터(달력기능) 사용할 경우
    protected $use_calendar = false;
    protected $today;
    protected $now;
    protected $date;
    protected $min_date = '2010-01-01';
    protected $max_date = '2050-12-31';
    protected $timezone;

    protected $default_view_directory;
    protected $default_view_file;

    protected $script = false;
    protected $login_only=false;
    
    // assets version
    protected $assets_version = 1;



    public function __construct()
    {
        parent::__construct();

        if ($this->input->is_cli_request()) {
            return true;
        }

        // 기본 헬퍼 로드
        $this->load_default_helper();

        $this->load->library('session');
        $this->load->library('layout');

        if ($this->input->get_post('format') == 'json' or $this->input->get_post('json')) {
            $this->format = 'json';
        }

        $this->set_locale();
        $this->login_check();

        /* // ACL 로드
        $this->load->model('Acl');
        $this->permission_check(); */

        $common_data['model'] = ucfirst($this->model);
        $common_data['title'] = _($common_data['model']);

        $common_data['meta_title'] = $this->config->item('seo_title');
        $common_data['meta_description'] = $this->config->item('seo_desc');

        if ($this->input->get('popup')) {
            $this->layout->layout = 'popup';
        }

        $this->timezone = new DateTimeZone($this->config->item('time_reference'));
        $date_time_obj = new DateTime('now', $this->timezone);
        $this->today = $date_time_obj->format('Y-m-d');
        $this->now = $date_time_obj->format('Y-m-d H:i:s');
        $this->date = $this->today;
        $search_data = array('current_year' => $date_time_obj->format('Y'), 'current_month' => $date_time_obj->format('m'), 'today' => $this->today, 'timezone' => $this->timezone, 'min_date' => $this->min_date, 'max_date' => $this->max_date, 'now' => $this->now);

        if ($this->input->get('date')) {
            $this->date = $this->input->get('date');
        }

        if ($this->use_calendar) {
            if ($this->input->get('date')) {
                $this->date = $this->input->get('date');
            }
            $search_data['date'] = $this->date;
        } else {
            $search_data['date'] = $this->date;
        }

        if (empty($this->default_view_directory)) {
            $this->default_view_directory = $this->router->fetch_class();
        }

        if (empty($this->default_view_file)) {
            $this->default_view_file = $this->router->fetch_method();
        }

        $this->load_cdn_setting();

        if ($this->format == 'html') {
            if (ENVIRONMENT != 'production') {
                $this->assets_version = uniqid();
            }

            $this->render_default_resource();
            $this->layout->title_for_layout = _('Main Title');
        }

        $common_data['assets_version'] = $this->assets_version;
        $this->return_data = array('common_data' => $common_data, 'search_data' => $search_data);
    }

    protected function load_default_helper()
    {
        $this->load->helper('sl');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('inflector');
    }

    protected function load_cdn_setting()
    {
        $env_file_path = realpath(BASEPATH.DIRECTORY_SEPARATOR.'..');

        if (!file_exists($env_file_path.DIRECTORY_SEPARATOR.'.env')) {
            return false;
        }

        $dotenv = Dotenv\Dotenv::createImmutable($env_file_path);
        $dotenv->load();

        if (!empty($_ENV['FOG_PROVIDER'])) {
            if ($_ENV['FOG_PROVIDER'] == 'AWS') {
                $this->s3_options = array(
                    'region' => 'ap-northeast-2',
                    'version' => 'latest',
                    'credentials' => array('key' => $_ENV['AWS_ACCESS_KEY_ID'], 'secret' => $_ENV['AWS_SECRET_ACCESS_KEY']),
                );
            }
            $this->file_server_type = $_ENV['FOG_PROVIDER'];
        }
    }

    protected function login_check()
    {
        if(empty($this->login_only)) {
            return true;
        }

        if ($this->format == 'html') {
            if (empty($this->session->userdata('user_id'))) {
                if ($this->input->get('notice')) {
                    redirect('/login?notice=1');
                } else {
                    redirect('/login');
                }
            }
        } else {
            if (empty($this->session->userdata('user_id'))) {
                echo json_encode(array('result' => 'fail', 'message' => 'login first'));
                exit;
            }
        }

        return true;
    }

    protected function set_locale()
    {
        if (!function_exists('_')) {
            echo 'gettext function not exists';
        }

        /* i18n locale */
        $language = 'korean';
        //$language = 'english';
        if ($this->input->get('language')) {
            $language = $this->input->get('language');
            $this->session->set_userdata('language', $language);
        }

        if($this->session->userdata('language')) {
            $language=$this->session->userdata('language');
        }

        switch ($language) {
            case 'korean':
                $locale = 'ko_KR.UTF-8';
                break;
            default:
                $locale = 'en_US.UTF-8';
        }

        putenv('LC_ALL='.$locale);
        setlocale(LC_ALL, $locale);
        if ($this->session->userdata('is_apt')) {
            $messages = 'messages_apt';
        } else {
            $messages = 'messages';
        }
        bindtextdomain($messages, APPPATH.DIRECTORY_SEPARATOR.'language');
        textdomain($messages);
        bind_textdomain_codeset($messages, 'UTF-8');
    }

    protected function render_default_resource()
    {
        if (ENVIRONMENT == 'development') {
            $this->layout->add_css('bootstrap.min.css');
            $this->layout->add_css('bootstrap-datepicker3.min.css');
            $this->layout->add_css('font-face.css');
            $this->layout->add_css('index.css?version='.$this->assets_version);
        } else {
            // uglifycss --output common.min.css  bootstrap.min.css select2.min.css animate.min.css bootstrap-datepicker.css style.css theme/default.css jquery.fancybox-1.3.4.css font-face-product.css index.css
            $this->layout->add_css('common.min.css?version='.$this->assets_version);
        }

        if (ENVIRONMENT == 'development') {
            $this->layout->add_js('jquery-2.1.1.min.js');
            $this->layout->add_js('popper.min.js');
            $this->layout->add_js('bootstrap.min.js');
            $this->layout->add_js('bootstrap-datepicker.min.js');
            $this->layout->add_js('jquery.fancybox.1.3.4.js');
            $this->layout->add_js('validate.min.js');
            $this->layout->add_js('plugin/jquery.tagcanvas.min.js');
            $this->layout->add_js('common.js');
        } else {
            // uglifyjs --output common.min.js jquery-2.1.1.min.js popper.min.js bootstrap.min.js bootstrap-datepicker.min.js jquery.fancybox.1.3.4.js validate.min.js  jquery.form.min.js jquery.fancybox.1.3.4.js select2.min.js jquery.pagination.js bootstrap-datepicker.min.js common.js
            $this->layout->add_js('common.min.js?version='.$this->assets_version);
        }
    }

    public function index()
    {
        $this->load->model($this->model);

        if (empty($this->per_page)) {
            $config['per_page'] = 10;
        } else {
            $config['per_page'] = $this->per_page;
        }

        if (isset($_GET['page'])) {
            $page = ($_GET['page'] - 1) * $config['per_page'];
        } else {
            $page = 0;
        }

        if (isset($this->{$this->model}->category_model)) {
            $category = $this->get_category($this->{$this->model}->category_model);
            $categoryId = $category['current_category_id'];
        } else {
            $categoryId = null;
        }

        $data = $this->{$this->model}
        ->get_index($config['per_page'], $page, $categoryId);
        $config['total_rows'] = $data['total'];

        if ($this->input->get('id')) {
            if (!$this->{$this->model}->get_count($id)) {
                show_404();
            }

            $data['content'] = $this->{$this->model}
            ->get_content($id);
        } else {
            if ($data['total']) {
                $data['content'] = $data['list'][0];
            }
        }

        $this->return_data['data'] = $data;

        if (isset($category)) {
            $this->return_data['data']['category'] = $category;
        }

        //$this -> output -> cache(1200);
        $this->setting_pagination($config);
        $this->render_format();
    }

    protected function render_format(array $json_array = null, $format = 'html')
    {
        if ($this->input->get('format') == 'json' or $this->input->get('json') == 'true') {
            $format = 'json';
        }

        if (empty($json_array)) {
            $json_array = $this->json_format();
        }

        if ($format == 'json') {
            echo json_encode($json_array);
        } else {
            $this->render_index_resource();
            $this->layout->render($this->router->fetch_class().'/'.$this->router->fetch_method(), $this->return_data);
        }
    }

    protected function json_format()
    {
        if (isset($this->return_data['data']['total'])) {
            if ($this->return_data['data']['total']) {
                return array('result' => 'success', 'total' => $this->return_data['data']['total'], 'list' => $this->return_data['data']['list']);
            } else {
                return array('result' => 'success', 'total' => $this->return_data['data']['total']);
            }
        }
    }

    protected function render_index_resource()
    {
        if ($this->script) {
            $this->layout->add_js($this->script.'?version='.$this->assets_version);
        }
    }

    protected function get_error_messages()
    {
        $message = array(
            'required' => _('The %s field is required.'),
            'min_length' => _('The %s field must be at least %s characters in length.'),
            'max_length' => _('The %s field cannot exceed %s characters in length.'),
            'numeric' => _('The %s field must contain only numbers.'),
            'is_unique' => _('%s field must contain a unique value.'),
            'matches' => _('The %s field does not match the %s field.'),
            'greater_than' => _('The %s field must contain a number greater than %s.'),
            'less_than' => _('The %s field must contain a number less than %s.'),
            'valid_email' => _('The %s field must contain a valid email address.'),
        );

        return $message;
    }

    public function set_message()
    {
        $message = $this->get_error_messages();

        $this->form_validation->set_message('required', $message['required']);
        $this->form_validation->set_message('min_length', $message['min_length']);
        $this->form_validation->set_message('max_length', $message['max_length']);
        $this->form_validation->set_message('numeric', $message['numeric']);
        $this->form_validation->set_message('is_unique', $message['is_unique']);
        $this->form_validation->set_message('matches', $message['matches']);
        $this->form_validation->set_message('greater_than', $message['greater_than']);
        $this->form_validation->set_message('less_than', $message['less_than']);
        $this->form_validation->set_message('valid_email', $message['valid_email']);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', _('title'), 'required|min_length[3]|max_length[60]');
        //$this -> form_validation -> set_rules('content',_('content'), 'required]');

        $this->load->model($this->model);
        if (isset($this->{$this->model}->category_model)) {
            $category = $this->get_category($this->{$this->model}->category_model);
            $categoryId = $category['current_category_id'];

            if ($category['total']) {
                $this->return_data['data']['category'] = $category;
            }
        } else {
            $categoryId = null;
        }

        if ($this->form_validation->run() == false) {
            if ($this->session->userdata('user_id')) {
                $this->layout->add_js('ckeditor/ckeditor.js');
                $this->layout->add_js('boards/add.js');
            }
            $this->layout->render($this->router->fetch_class().'/add', $this->return_data);
        } else {
            $data = $this->input->post(null, true);

            if ($id = $this->{$this->model}->insert($data)) {
                if (isset($this->{$this->model}->category_model)) {
                    $this->load->model($this->{$this->model}->category_model);

                    $this->{$this->{$this->model}
                    ->category_model}->update_count_plus($data[$this->{$this->model}->category_id_name]);
                }

                $this->session->set_flashdata('message', array('type' => 'success', 'message' => _('Successfully Created Article')));
                redirect($this->router->fetch_class().'/'.$id);
            } else {
                redirect($this->router->fetch_class().'/add');
            }
        }
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', _('title'), 'required|min_length[3]|max_length[60]');
        //$this -> form_validation -> set_rules('content',_('content'), 'required]');

        $this->load->model($this->model);
        if (isset($this->{$this->model}->category_model)) {
            $category = $this->get_category($this->{$this->model}->category_model);
            $categoryId = $category['current_category_id'];

            if ($category['total']) {
                $this->return_data['data']['category'] = $category;
            }
        } else {
            $categoryId = null;
        }

        $data = array();
        $data['content'] = $this->{$this->model}
        ->get_content($id);

        if ($this->form_validation->run() == false) {
            $this->return_data['data']['content'] = $data['content'];

            if ($this->session->userdata('user_id')) {
                $this->layout->add_js('ckeditor/ckeditor.js');
                $this->layout->add_js('boards/add.js');
            }
            $this->layout->render($this->router->fetch_class().'/edit', $this->return_data);
        } else {
            $data = $this->input->post(null, true);
            $data['id'] = $id;

            $result = $this->{$this->model}
            ->update($data);
            if ($result) {
                if (isset($this->{$this->model}->category_model)) {
                    $this->load->model($this->{$this->model}->category_model);

                    $this->{$this->{$this->model}
                    ->category_model}->update_count_minus($data['content'][$this->{$this->model}->category_id_name]);
                    $this->{$this->{$this->model}
                    ->category_model}->update_count_plus($data[$this->{$this->model}->category_id_name]);
                }

                $this->session->set_flashdata('message', array('type' => 'success', 'message' => _('Successfully Edited Article')));
                redirect($this->router->fetch_class().'/'.$id);
            } else {
                redirect($this->router->fetch_class().'/edit');
            }
        }
    }

    public function view($id)
    {
        $this->load->model($this->model);

        if (!$this->{$this->model}->get_count($id)) {
            show_404();
        }

        $data['content'] = $this->{$this->model}
        ->get_content($id);

        if ($this->comment_model) {
            $this->load->model($this->comment_model);
            $data['comments'] = $this->{$this->comment_model}
            ->get_index($data['content']['id']);
        }

        $this->return_data['data'] = $data;

        if ($this->addImpression($id)) {
            $this->{$this->model}
            ->id = $id;
            $this->{$this->model}
            ->update_count_plus($id);
        }

        // $this -> output -> cache(1200);
        $this->layout->add_js('boards/view.js');
        $this->layout->render($this->router->fetch_class().'/view', $this->return_data);
    }

    public function confirm_delete($id)
    {
        $this->return_data['data']['id'] = $id;
        $this->layout->render($this->router->fetch_class().'/confirm_delete', $this->return_data);
    }

    public function delete($id)
    {
        if (!$this->session->userdata('admin')) {
            if ($this->session->userdata('delete_auth')) {
            } else {
                throw new Exception('Error Processing Request', 1);
            }
        }

        $this->load->model($this->model);
        if ($this->{$this->model}->delete($id)) {
            if (isset($this->{$this->model}->category_model)) {
                $this->load->model($this->{$this->model}->category_model);

                $this->{$this->{$this->model}
                ->category_model}->update_count_minus($data[$this->{$this->model}->category_id_name]);
            }

            redirect($this->router->fetch_class());
        } else {
            throw new Exception('Error Processing Request', 1);
        }
    }

    protected function getImpressionCount($id)
    {
        $this->load->model('Impression');

        return $this->Impression->get_count_impression(array('impressionable_type' => $this->model, 'controller_name' => $this->router->fetch_class(), 'action_name' => $this->router->fetch_method(), 'ip_address' => $this->input->ip_address(), 'impressionable_id' => $id));
    }

    protected function addImpression($id)
    {
        if ($this->getImpressionCount($id)) {
            return false;
        } else {
            $this->load->model('Impression');
            if (!$this->Impression->insert(
            array('impressionable_type' => $this->model,
            'controller_name' => $this->router->fetch_class(),
            'action_name' => $this->router->fetch_method(),
            'ip_address' => $this->input->ip_address(),
            'request_hash' => hash_hmac('sha512', time().rand(0, 10000), 'sleepinglion'),
            'session_hash' => session_id(),
            'impressionable_id' => $id, 'referrer' => $_SERVER['HTTP_REFERER'], )
            )) {
                $this->session->set_flashdata('message', array('type' => 'error', 'message' => _('The post could not be saved. Please, try again.')));
            }

            return true;
        }
    }

    protected function setting_pagination(array $config)
    {
        $this->load->library('pagination');

        if (empty($config['per_page'])) {
            $config['per_page'] = $this->per_page;
        }

        if (empty($config['base_url'])) {
            if ($this->router->fetch_method() == 'index' or $this->router->fetch_method() == 'edit') {
                $config['base_url'] = base_url().$this->router->fetch_class();
            } else {
                $config['base_url'] = base_url().$this->router->fetch_class().'/'.$this->router->fetch_method();
            }
        }
        $config['page_query_string'] = true;
        $config['use_page_numbers'] = true;
        $config['query_string_segment'] = 'page';

        $query_string = $this->input->get();
        if (isset($query_string['page'])) {
            unset($query_string['page']);
        }

        if (count($query_string) > 0) {
            $config['suffix'] = '&'.http_build_query($query_string, '', '&');
            $config['first_url'] = $config['base_url'].'?'.http_build_query($query_string, '', '&');
        }

        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&laquo; '._('First');
        $config['first_tag_open'] = '<li class="prev page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = _('Last').' &raquo;';
        $config['last_tag_open'] = '<li class="next page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '▶';
        $config['next_tag_open'] = '<li class="next page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '◀';
        $config['prev_tag_open'] = '<li class="prev page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active page-item"><a href="" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'page-link');
        $this->pagination->initialize($config);
    }
}
