<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Antigravity
 * Controller for Master Hari Libur / Holidays
 */

class Holidays extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->set([
            'title' => 'Master Hari Libur (Holidays)',
            'icon'  => 'fa fa-calendar-alt'
        ]);

        date_default_timezone_set("Asia/Jakarta");
    }

    public function index()
    {
        $year = $this->input->get('year') ? $this->input->get('year') : date('Y');
        
        $this->db->order_by('holiday_date', 'ASC');
        if ($year != 'all') {
            $this->db->where("YEAR(holiday_date)", $year);
        }
        $data = $this->db->get_where('master_holidays', ['status' => '1'])->result();

        // Get distinct years available in DB
        $years = $this->db->query("SELECT DISTINCT YEAR(holiday_date) as y FROM master_holidays ORDER BY y DESC")->result();

        $this->template->set('data', $data);
        $this->template->set('selected_year', $year);
        $this->template->set('years', $years);
        $this->template->render('index');
    }

    public function add()
    {
        $this->load->view('form', ['data' => null]);
    }

    public function edit($id = null)
    {
        $data = $this->db->get_where('master_holidays', ['id' => $id])->row();
        $this->load->view('form', ['data' => $data]);
    }

    public function save()
    {
        $post = $this->input->post();
        if (!$post || empty($post['holiday_date']) || empty($post['holiday_name'])) {
            echo json_encode([
                'status' => 0,
                'msg'    => 'Tanggal dan Nama Hari Libur wajib diisi!'
            ]);
            return;
        }

        $id           = !empty($post['id']) ? $post['id'] : null;
        $holiday_date = trim($post['holiday_date']);
        $holiday_name = trim($post['holiday_name']);
        $holiday_type = !empty($post['holiday_type']) ? $post['holiday_type'] : 'Nasional';
        $descriptions = !empty($post['descriptions']) ? trim($post['descriptions']) : null;

        // Check unique date
        $this->db->where('holiday_date', $holiday_date);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $exists = $this->db->get('master_holidays')->row();
        if ($exists) {
            echo json_encode([
                'status' => 0,
                'msg'    => "Tanggal $holiday_date sudah terdaftar sebagai '{$exists->holiday_name}'!"
            ]);
            return;
        }

        $this->db->trans_begin();
        $saveData = [
            'holiday_date' => $holiday_date,
            'holiday_name' => $holiday_name,
            'holiday_type' => $holiday_type,
            'descriptions' => $descriptions,
            'status'       => '1'
        ];

        if ($id) {
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            $saveData['updated_by'] = $this->auth->user_id();
            $this->db->update('master_holidays', $saveData, ['id' => $id]);
        } else {
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $saveData['created_by'] = $this->auth->user_id();
            $this->db->insert('master_holidays', $saveData);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 0,
                'msg'    => 'Gagal menyimpan data hari libur. Silakan coba lagi.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => 1,
                'msg'    => 'Data hari libur berhasil disimpan!'
            ]);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            echo json_encode(['status' => 0, 'msg' => 'ID tidak valid']);
            return;
        }

        $this->db->trans_begin();
        $this->db->delete('master_holidays', ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'msg' => 'Gagal menghapus data.']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 1, 'msg' => 'Data hari libur berhasil dihapus.']);
        }
    }

    /**
     * Download template Excel (.xlsx) for easy 1-year holiday input
     */
    public function download_template()
    {
        $year = $this->input->get('year') ? $this->input->get('year') : date('Y');
        $filename = "Template_Hari_Libur_" . $year . ".xlsx";

        $headers = [
            'No',
            'Tanggal Libur (YYYY-MM-DD)',
            'Nama Hari Libur',
            'Tipe Libur (Nasional / Cuti Bersama / Khusus)',
            'Keterangan'
        ];

        $examples = [
            ['1', $year . '-01-01', 'Tahun Baru ' . $year . ' Masehi', 'Nasional', 'Libur Nasional Awal Tahun'],
            ['2', $year . '-03-20', 'Hari Raya Idul Fitri 1447 H', 'Nasional', 'Hari Raya Idul Fitri'],
            ['3', $year . '-03-21', 'Hari Raya Idul Fitri 1447 H', 'Nasional', 'Hari Raya Idul Fitri'],
            ['4', $year . '-05-01', 'Hari Buruh Internasional', 'Nasional', 'Libur Nasional'],
            ['5', $year . '-06-01', 'Hari Lahir Pancasila', 'Nasional', 'Libur Nasional'],
            ['6', $year . '-08-17', 'Hari Kemerdekaan Republik Indonesia', 'Nasional', 'HUT Kemerdekaan RI'],
            ['7', $year . '-12-25', 'Hari Raya Natal', 'Nasional', 'Libur Nasional'],
        ];

        $this->load->library('simple_excel_reader');
        $this->simple_excel_reader->downloadXlsx($filename, $headers, $examples, 'Hari Libur ' . $year);
    }

    /**
     * Upload & Import Excel / CSV holidays in bulk
     */
    public function import_excel()
    {
        if (!isset($_FILES['file_excel']['name']) || empty($_FILES['file_excel']['name'])) {
            echo json_encode([
                'status' => 0,
                'msg'    => 'Pilih file Excel (.xlsx, .xls) atau CSV untuk diupload!'
            ]);
            return;
        }

        $allowed = ['xlsx', 'xls', 'csv'];
        $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo json_encode([
                'status' => 0,
                'msg'    => 'Format file tidak didukung. Harap upload file .xlsx, .xls, atau .csv!'
            ]);
            return;
        }

        $this->load->library('simple_excel_reader');
        $filePath = $_FILES['file_excel']['tmp_name'];
        $rows = $this->simple_excel_reader->parse($filePath);

        if (!$rows || empty($rows)) {
            echo json_encode([
                'status' => 0,
                'msg'    => 'File kosong atau tidak dapat dibaca.'
            ]);
            return;
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        $this->db->trans_begin();

        foreach ($rows as $index => $row) {
            $values = array_values($row);
            if (empty($values)) continue;

            // Detect if this is header row
            $joined = strtolower(implode(' ', $values));
            if ($index === 0 && (strpos($joined, 'tanggal') !== false || strpos($joined, 'nama') !== false || strpos($joined, 'holiday') !== false || strpos($joined, 'libur') !== false)) {
                continue; // Skip header row
            }

            $rawDate = null;
            $rawName = null;
            $rawType = 'Nasional';
            $rawDesc = null;

            // Auto-detect date column
            $dateFound = false;
            foreach ($values as $colIdx => $colVal) {
                $parsed = $this->simple_excel_reader->parseDate($colVal);
                if ($parsed && !$dateFound) {
                    $rawDate = $parsed;
                    $dateFound = true;
                    // Usually the next column is the holiday name
                    if (isset($values[$colIdx + 1]) && !empty($values[$colIdx + 1])) {
                        $rawName = trim($values[$colIdx + 1]);
                    }
                    if (isset($values[$colIdx + 2]) && !empty($values[$colIdx + 2])) {
                        $rawType = trim($values[$colIdx + 2]);
                    }
                    if (isset($values[$colIdx + 3]) && !empty($values[$colIdx + 3])) {
                        $rawDesc = trim($values[$colIdx + 3]);
                    }
                    break;
                }
            }

            if (!$rawDate || !$rawName) {
                $skipped++;
                continue;
            }

            // Normalize type
            if (stripos($rawType, 'cuti') !== false) {
                $rawType = 'Cuti Bersama';
            } elseif (stripos($rawType, 'khusus') !== false || stripos($rawType, 'internal') !== false) {
                $rawType = 'Khusus';
            } else {
                $rawType = 'Nasional';
            }

            // Check if exists
            $existing = $this->db->get_where('master_holidays', ['holiday_date' => $rawDate])->row();
            if ($existing) {
                $this->db->update('master_holidays', [
                    'holiday_name' => $rawName,
                    'holiday_type' => $rawType,
                    'descriptions' => $rawDesc ?: $existing->descriptions,
                    'status'       => '1',
                    'updated_at'   => date('Y-m-d H:i:s'),
                    'updated_by'   => $this->auth->user_id()
                ], ['id' => $existing->id]);
                $updated++;
            } else {
                $this->db->insert('master_holidays', [
                    'holiday_date' => $rawDate,
                    'holiday_name' => $rawName,
                    'holiday_type' => $rawType,
                    'descriptions' => $rawDesc,
                    'status'       => '1',
                    'created_at'   => date('Y-m-d H:i:s'),
                    'created_by'   => $this->auth->user_id()
                ]);
                $inserted++;
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 0,
                'msg'    => 'Gagal memproses import data Excel.'
            ]);
        } else {
            $this->db->trans_commit();
            $msg = "Import berhasil! {$inserted} data baru ditambahkan, {$updated} data diperbarui.";
            if ($skipped > 0) {
                $msg .= " ({$skipped} baris kosong/header dilewati)";
            }
            echo json_encode([
                'status'   => 1,
                'msg'      => $msg,
                'inserted' => $inserted,
                'updated'  => $updated
            ]);
        }
    }
}
