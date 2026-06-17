<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setting Email Controller
 *
 * Manages SMTP email configuration stored in the `settings` table.
 */
class Setting_email extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Setting Email',
            'icon'  => 'fa fa-envelope'
        ]);
    }

    /**
     * Index - display current SMTP settings form
     */
    public function index()
    {
        // Get smtp settings from DB
        $smtp = $this->db->where('setting_name LIKE', 'smtp%')->get('settings')->result();
        $settings = [];
        foreach ($smtp as $row) {
            $settings[$row->setting_name] = $row->value;
        }

        $this->template->set('settings', $settings);
        $this->template->render('index');
    }

    /**
     * Save - AJAX save SMTP settings
     */
    public function save()
    {
        $data = $this->input->post();

        if (!$data) {
            echo json_encode(['status' => 0, 'msg' => 'Data not valid.']);
            return;
        }

        $this->db->trans_begin();

        // Update or insert each smtp setting
        $fields = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_crypto'];

        foreach ($fields as $field) {
            $value = isset($data[$field]) ? trim($data[$field]) : '';

            // Encrypt password before storing
            if ($field === 'smtp_pass' && $value !== '') {
                $value = $this->_encrypt($value);
            }

            $exists = $this->db->get_where('settings', ['setting_name' => $field])->row();
            if ($exists) {
                $this->db->update('settings', ['value' => $value], ['setting_name' => $field]);
            } else {
                $this->db->insert('settings', ['setting_name' => $field, 'value' => $value]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'msg' => 'Gagal menyimpan pengaturan.']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 1, 'msg' => 'Pengaturan email berhasil disimpan.']);
        }
    }

    /**
     * Test Email - AJAX send test email
     */
    public function test()
    {
        $data = $this->input->post();
        $test_email = isset($data['test_email']) ? trim($data['test_email']) : '';

        if (empty($test_email) || !filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 0, 'msg' => 'Masukkan email tujuan yang valid.']);
            return;
        }

        // Get current settings from DB
        $smtp = $this->db->where('setting_name LIKE', 'smtp%')->get('settings')->result();
        $settings = [];
        foreach ($smtp as $row) {
            $settings[$row->setting_name] = $row->value;
        }

        $host = isset($settings['smtp_host']) ? $settings['smtp_host'] : '';
        $port = isset($settings['smtp_port']) ? $settings['smtp_port'] : '465';
        $user = isset($settings['smtp_user']) ? $settings['smtp_user'] : '';
        $pass = isset($settings['smtp_pass']) ? $this->_decrypt($settings['smtp_pass']) : '';
        $crypto = isset($settings['smtp_crypto']) ? $settings['smtp_crypto'] : 'ssl';

        if (empty($host) || empty($user) || empty($pass)) {
            echo json_encode(['status' => 0, 'msg' => 'Lengkapi konfigurasi SMTP terlebih dahulu.']);
            return;
        }

        // Build smtp_host with protocol prefix
        $smtp_host = $host;
        if (strpos($host, '://') === false) {
            $smtp_host = $crypto . '://' . $host;
        }

        $this->load->library('email');

        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => $smtp_host,
            'smtp_port' => (int) $port,
            'smtp_user' => $user,
            'smtp_pass' => $pass,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
        ];

        $this->email->initialize($config);
        $this->email->from($user, 'Sentral Sistem - Test Email');
        $this->email->to($test_email);
        $this->email->subject('Test Email - Sentral Sistem');
        $this->email->message('<h3>Test Email Berhasil!</h3><p>Konfigurasi SMTP Anda sudah benar.</p>');

        if ($this->email->send()) {
            echo json_encode(['status' => 1, 'msg' => 'Test email berhasil dikirim ke ' . $test_email]);
        } else {
            $error = $this->email->print_debugger(['headers']);
            log_message('error', 'Test email failed: ' . $error);
            echo json_encode(['status' => 0, 'msg' => 'Gagal mengirim test email. Periksa konfigurasi SMTP.']);
        }
    }

    /**
     * Simple encrypt for storing password
     */
    private function _encrypt($value)
    {
        $key = 'sentral_sistem_2024';
        return base64_encode(openssl_encrypt($value, 'AES-256-CBC', $key, 0, str_pad(substr($key, 0, 16), 16, '0')));
    }

    /**
     * Simple decrypt for reading password
     */
    private function _decrypt($value)
    {
        $key = 'sentral_sistem_2024';
        return openssl_decrypt(base64_decode($value), 'AES-256-CBC', $key, 0, str_pad(substr($key, 0, 16), 16, '0'));
    }
}
