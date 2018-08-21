<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_attendance extends CI_Controller {

	public  function __construct()
	{
		parent::__construct();
		$this->active = $this->uri->segment(1);
        $this->load->helper('pdf_helper');
		$this->load->model('mattendance');
		$this->load->library('PHPExcel');
	}

	public function index()
	{
		if($this->session->userdata('login')) {
			$this->load->view('blocks/header');
			$this->load->view('report/attendance');
			$this->load->view('blocks/footer');
		} else {
			$this->load->helper(array('form'));
			redirect('/auth');
		}
	}

	public function getReport()
	{
		$limit		= (isset($_GET['limit'])) ? $_GET['limit'] : 10;
		$offset 	= (isset($_GET['offset'])) ? $_GET['offset'] : 0;
		$order		= (isset($_GET['order'])) ? $_GET['order'] : 'ASC';
		$search 	= (isset($_GET['search'])) ? $_GET['search'] : '';
		$sort		= (isset($_GET['sort'])) ? $_GET['sort'] : 'A.team_name';
		echo @$this->mattendance->getReport($_SESSION['login']['group_user'], $_SESSION['login']['id'], $limit, $offset, $order, $search, $sort);
	}

	public function export_excel($site_id, $date_from, $date_thru)
	{
        
        $query	= $this->db->query("SELECT S.site_name, C.ta_name AS client_name, T.team_name FROM team_attendance TA LEFT JOIN site S ON S.id = TA.site_id LEFT JOIN ta_users C ON C.id = S.client_id LEFT JOIN team T ON T.id = TA.team_id WHERE TA.site_id = '".$site_id."' AND TA.att_date BETWEEN '".$date_from."' AND '".$date_thru."' AND T.position_id in ('1','2','4')");
        
        $results = $query->result();
        
		if($query->num_rows() > 0) {
            
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()
						->setCreator("PT TRIMITRA PUTRA MANDIRI")
						->setTitle("Attendance Report"); 

			$objset = $objPHPExcel->setActiveSheetIndex(0);
			$objget = $objPHPExcel->getActiveSheet();

			$objget->setTitle('Attendance Summary');
            
            $alpha = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
                               'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
                               'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO',
                               'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
                               'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO',
                               'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
                               'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO',
                               'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
                               'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO',
                               'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
                               'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO',
                               'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ'
                               );
                               
            $date1 = new DateTime($date_from);
            $date2 = new DateTime($date_thru);
            $days  = $date2->diff($date1)->format('%a') + 1;
            
            $styleAll = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
                'font' => array(
                    'size' => '10',
                    'name' => 'Tahoma',
                )
            );
            
            $objPHPExcel->getDefaultStyle()->applyFromArray($styleAll);
            
			$objget->getStyle("A4:C6")->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '92d050')
					),
					'font' => array(
						'color' => array('rgb' => '000000')
					)
				)
			);
            
			$objget->getStyle("D4:".$alpha[($days*3) + 2]."6")->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '00e9ff')
					),
					'font' => array(
						'color' => array('rgb' => '000000')
					)
				)
			);
            
			$objget->getStyle($alpha[($days*3) + 3]."4:".$alpha[($days*3) + 16]."6")->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'ffd400')
					),
					'font' => array(
						'color' => array('rgb' => '000000')
					)
				)
			);
            
            $styleLeft = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                )
            );
            
            $styleLeftBold = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ),
                'font' => array(
                    'bold' => true
                )
            );
            
            $styleBold = array(
                'font' => array(
                    'bold' => true
                )
            );
            
            $styleBoldRed = array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'f45f42')
					),
					'font' => array(
						'bold' => true
					)
            );
            
            $objPHPExcel->getActiveSheet()->freezePane('D7');
            
            $objset->setCellValue('A1', 'REKAP ABSENSI - ' . $results[0]->site_name);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleLeftBold);
            $objset->setCellValue('A2', 'PERIODE: ' . $date_from . ' - ' . $date_thru);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleLeftBold);
            
            $objset->setCellValue('A4', 'NO');
            $objset->setCellValue('B4', 'NAMA');
            $objset->setCellValue('C4', 'JABATAN');
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleBold);
            $objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleBold);
            $objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleBold);
            $objPHPExcel->getActiveSheet()->mergeCells('A4:A6');
            $objPHPExcel->getActiveSheet()->mergeCells('B4:B6');
            $objPHPExcel->getActiveSheet()->mergeCells('C4:C6');
            
            for($i=0;$i<$days;$i++) {
                $col_date = date('d/m', strtotime(' +'.$i.' day', strtotime($date_from)));
                $col_days = date('D', strtotime(' +'.$i.' day', strtotime($date_from)));
                
                switch($col_days) {
                    case 'Mon':
                        $col_days_id = 'Senin';
                        Break;
                    case 'Tue':
                        $col_days_id = 'Selasa';
                        Break;
                    case 'Wed':
                        $col_days_id = 'Rabu';
                        Break;
                    case 'Thu':
                        $col_days_id = 'Kamis';
                        Break;
                    case 'Fri':
                        $col_days_id = 'Jumat';
                        Break;
                    case 'Sat':
                        $col_days_id = 'Sabtu';
                        Break;
                    case 'Sun':
                        $col_days_id = 'Minggu';
                        Break;
                    Default:
                        $col_days_id = 'Minggu';
                }
                
                $sty = ($col_days == 'Sun' ? $styleBoldRed : $styleBold);
                
                $num_col = ($i*3)+3;
                $objset->setCellValue( $alpha[$num_col] . '4', $col_days_id);
                $objPHPExcel->getActiveSheet()->getStyle($alpha[$num_col].'4')->applyFromArray($sty);
                $objPHPExcel->getActiveSheet()->mergeCells($alpha[$num_col].'4:'.$alpha[$num_col+2].'4');
                $objset->setCellValue( $alpha[$num_col] . '5', $col_date);
                $objPHPExcel->getActiveSheet()->getStyle($alpha[$num_col].'5')->applyFromArray($sty);
                $objPHPExcel->getActiveSheet()->mergeCells($alpha[$num_col].'5:'.$alpha[$num_col+2].'5');
                
                $objset->setCellValue( $alpha[$num_col] . '6', 'P');
                $objPHPExcel->getActiveSheet()->getColumnDimension($alpha[$num_col])->setWidth(3);
                $objPHPExcel->getActiveSheet()->getStyle($alpha[$num_col].'6')->applyFromArray($sty);
                $objset->setCellValue( $alpha[$num_col+1] . '6', 'S');
                $objPHPExcel->getActiveSheet()->getColumnDimension($alpha[$num_col+1])->setWidth(3);
                $objPHPExcel->getActiveSheet()->getStyle($alpha[$num_col+1].'6')->applyFromArray($sty);
                $objset->setCellValue( $alpha[$num_col+2] . '6', 'M');
                $objPHPExcel->getActiveSheet()->getColumnDimension($alpha[$num_col+2])->setWidth(3);
                $objPHPExcel->getActiveSheet()->getStyle($alpha[$num_col+2].'6')->applyFromArray($sty);
                
            }
            
            $num_col = $num_col+3;
            $objset->setCellValue( $alpha[$num_col] . '4', 'JUMLAH KEHADIRAN / KETIDAKHADIRAN');
            $objPHPExcel->getActiveSheet()->getStyle($alpha[$num_col].'4')->applyFromArray($styleBold);
            $objPHPExcel->getActiveSheet()->mergeCells($alpha[$num_col]."4:".$alpha[$num_col+13]."5");
            
			$arr = array(
                            array('col' => $alpha[$num_col], 'code' => 'P', 'name' => 'PAGI', 'full' => 'PAGI'),
                            array('col' => $alpha[$num_col+1], 'code' => 'S', 'name' => 'SIANG', 'full' => 'SIANG'),
                            array('col' => $alpha[$num_col+2], 'code' => 'M', 'name' => 'MALAM', 'full' => 'MALAM'),
                            array('col' => $alpha[$num_col+3], 'code' => 'OF', 'name' => 'OFF', 'full' => 'OFF'),
                            array('col' => $alpha[$num_col+4], 'code' => 'SK', 'name' => 'SAKIT', 'full' => 'SAKIT'),
                            array('col' => $alpha[$num_col+5], 'code' => 'CT', 'name' => 'CUTI', 'full' => 'CUTI'),
                            array('col' => $alpha[$num_col+6], 'code' => 'AL', 'name' => 'ALPA', 'full' => 'ALPA'),
                            array('col' => $alpha[$num_col+7], 'code' => 'LS', 'name' => 'L/SPKL', 'full' => 'LEMBUR SPKL'),
                            array('col' => $alpha[$num_col+8], 'code' => 'OF', 'name' => 'L/BKO', 'full' => 'LEMBUR KBO'),
                            array('col' => $alpha[$num_col+9], 'code' => 'BD', 'name' => 'BKO/D', 'full' => 'BKO DIKLAT'),
                            array('col' => $alpha[$num_col+10], 'code' => 'DI', 'name' => 'DIKLAT', 'full' => 'DIKLAT'),
                            array('col' => $alpha[$num_col+11], 'code' => 'HD', 'name' => 'HADIR', 'full' => 'HADIR'),
                            array('col' => $alpha[$num_col+12], 'code' => 'TD', 'name' => 'T/HADIR', 'full' => 'TIDAK HADIR'),
                            array('col' => $alpha[$num_col+13], 'code' => 'TO', 'name' => 'TOTAL', 'full' => 'HARI KERJA')
                        );
                        
			for ($a=0;$a<14;$a++) {
                $objset->setCellValue( $arr[$a]['col'] . '6', $arr[$a]['code']);
                $objPHPExcel->getActiveSheet()->getColumnDimension($arr[$a]['col'])->setWidth(5);
                $objPHPExcel->getActiveSheet()->getStyle($arr[$a]['col'].'6')->applyFromArray($styleBold);
			}
            
            //$query	= $this->db->query("SELECT TA.att_date, TA.att_reason, C.ta_name AS client_name, S.name AS shift_name, T.team_name, MA.name AS att_type_name FROM team_attendance TA LEFT JOIN ta_users C ON C.id = TA.client_id LEFT JOIN master_shift S ON S.id = TA.att_shift LEFT JOIN team T ON T.id = TA.team_id LEFT JOIN master_attendance MA ON MA.id = TA.att_type WHERE TA.site_id = '".$site_id."' AND TA.att_date BETWEEN '".$date_from."' AND '".$date_thru."' AND T.position_id in ('1','2','4')");
            
            $query	= $this->db->query("SELECT T.id, T.team_name, MP.name AS position_name, MP.ordering FROM team_attendance TA LEFT JOIN team T ON T.id = TA.team_id LEFT JOIN master_position MP ON MP.id = T.position_id WHERE TA.site_id = '".$site_id."' AND TA.att_date BETWEEN '".$date_from."' AND '".$date_thru."' AND T.position_id in ('1','2','4') GROUP BY T.id, T.team_name, MP.name, MP.ordering ORDER BY MP.ordering, T.team_name");
            
            $teams = $query->result();
            $teams_numrow = $query->num_rows();
            
            $baris = 7;
            $total = array();
            $tot_team = array();
            if($teams_numrow > 0) {
                $no = 1;
                
                for($i=0;$i<$days;$i++) {
                    for($j=1;$j<=3;$j++) {
                        for($k=0;$k<=13;$k++) {
                            $total[$i][$j][$k] = 0;
                        }
                    }
                }
                
                foreach($teams as $team) {
                    $objset->setCellValue("A".$baris, $no++);
                    $objset->setCellValue("B".$baris, $team->team_name);
                    $objPHPExcel->getActiveSheet()->getStyle("B".$baris)->applyFromArray($styleLeft);
                    $objset->setCellValue("C".$baris, $team->position_name);
                    $objPHPExcel->getActiveSheet()->getStyle("C".$baris)->applyFromArray($styleLeft);
                    $last_baris_team = $baris;
                    $kolom = 1;
                    for($k=0;$k<=10;$k++) {
                        $tot_team[$k] = 0;
                    }
                    for($i=0;$i<$days;$i++) {
                        $this_date = date('Y-m-d', strtotime(' +'.$i.' day', strtotime($date_from)));
                        for($j=1;$j<=3;$j++) {
                            $query	= $this->db->query("SELECT TA.att_date, TA.att_shift, MAX(MA.code) AS att_code, MAX(S.code) AS shift_code FROM team_attendance TA LEFT JOIN site ST ON ST.id = TA.site_id LEFT JOIN ta_users C ON C.id = ST.client_id LEFT JOIN master_shift S ON S.id = TA.att_shift LEFT JOIN team T ON T.id = TA.team_id LEFT JOIN master_attendance MA ON MA.id = TA.att_type WHERE TA.site_id = '".$site_id."' AND TA.att_date = '".$this_date."' AND TA.team_id = '". $team->id."' AND TA.att_shift = '".$j."' AND T.position_id in ('1','2','4') GROUP BY TA.att_date, TA.att_shift ORDER BY TA.att_date, TA.att_shift");
                            $attends = $query->result();
                            $attends_numrow = $query->num_rows();
                            
                            $att_code = "-";
                            $att_code2 = "-";
                            if($attends_numrow > 0) {
                                if($attends[0]->att_date == $this_date && $attends[0]->att_shift == $j) {
                                    if($attends[0]->att_code == 'HD') {
                                        $att_code = $attends[0]->shift_code;
                                    } else {
                                        $att_code = $attends[0]->att_code;
                                    }
                                    $att_code2 = $attends[0]->att_code;
                                }
                            } 
                            
                            for($k=0;$k<=13;$k++) {
                                if($att_code == $arr[$k]['code']) {
                                    $total[$i][$j][$k] = $total[$i][$j][$k]+1;
                                    $tot_team[$k] = $tot_team[$k] + 1;
                                }
                            }
                            $objset->setCellValue($alpha[$kolom+2].$baris, $att_code);
                            
                            if($j==3){
                                $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+2]."4:".$alpha[$kolom+3].($baris+15))->getBorders()->getRight()->applyFromArray(
                                    array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('rgb' => '000000')
                                    )
                                );
                            }
                            
                            $kolom++;
                        }
                    }
                    
                    for($k=0;$k<=10;$k++) {
                        $objset->setCellValue($alpha[$kolom+2+$k].$baris, $tot_team[$k]);
                    }
                    
                    
                    $tot = $tot_team[0] + $tot_team[1] + $tot_team[2];
                    $objset->setCellValue($alpha[$kolom+2+$k].$baris, $tot);
                    $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+2+$k].$baris)->applyFromArray($styleBold);
                    
                    $tot = $tot_team[3] + $tot_team[4] + $tot_team[5] + $tot_team[6];
                    $objset->setCellValue($alpha[$kolom+3+$k].$baris, $tot);
                    $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+3+$k].$baris)->applyFromArray($styleBold);
                    
                    $tot = 0;
                    for($k=0;$k<=10;$k++) {
                        $tot = $tot + $tot_team[$k];
                    }
                    $objset->setCellValue($alpha[$kolom+4+$k].$baris, $tot);
                    $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+4+$k].$baris)->applyFromArray($styleBold);
                    
                    
                    
                    $baris++;
                }
            }
            
            for($k=0;$k<=10;$k++) {
                $baris++;
                $tot = 0;
                $kolom = 1;
                $objset->setCellValue("C".$baris, 'TOTAL '.$arr[$k]['full']);
                $objPHPExcel->getActiveSheet()->getStyle("C".$baris)->applyFromArray($styleLeft);
                for($i=0;$i<$days;$i++) {
                    for($j=1;$j<=3;$j++) {
                        $objset->setCellValue($alpha[$kolom+2].$baris, $total[$i][$j][$k]);
                        $tot = $tot + $total[$i][$j][$k];
                        $kolom++;
                    }
                }
                $objset->setCellValue($alpha[$kolom+2+$k].$baris, $tot);
            }
            
            $baris++;
            $tot2 = 0;
            $kolom = 1;
            $objset->setCellValue("C".$baris, 'TOTAL '.$arr[11]['full']);
            $objPHPExcel->getActiveSheet()->getStyle("C".$baris)->applyFromArray($styleLeftBold);
            for($i=0;$i<$days;$i++) {
                for($j=1;$j<=3;$j++) {
                    $tot = 0;
                    $tot = $total[$i][$j][0] + $total[$i][$j][1] + $total[$i][$j][2];
                    $tot2 = $tot2 + $tot;
                    $objset->setCellValue($alpha[$kolom+2].$baris, $tot);
                    $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+2].$baris)->applyFromArray($styleBold);
                    $kolom++;
                }
            }
            $objset->setCellValue($alpha[$kolom+13].$baris, $tot2);
            $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+13].$baris)->applyFromArray($styleBold);
            
            $baris++;
            $tot2 = 0;
            $kolom = 1;
            $objset->setCellValue("C".$baris, 'TOTAL '.$arr[12]['full']);
            $objPHPExcel->getActiveSheet()->getStyle("C".$baris)->applyFromArray($styleLeftBold);
            for($i=0;$i<$days;$i++) {
                for($j=1;$j<=3;$j++) {
                    $tot = 0;
                    $tot = $total[$i][$j][3] + $total[$i][$j][4] + $total[$i][$j][5] + $total[$i][$j][6];
                    $tot2 = $tot2 + $tot;
                    $objset->setCellValue($alpha[$kolom+2].$baris, $tot);
                    $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+2].$baris)->applyFromArray($styleBold);
                    $kolom++;
                }
            }
            $objset->setCellValue($alpha[$kolom+14].$baris, $tot2);
            $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+14].$baris)->applyFromArray($styleBold);
            
            $baris++;
            $tot2 = 0;
            $kolom = 1;
            $objset->setCellValue("C".$baris, 'TOTAL '.$arr[13]['full']);
            $objPHPExcel->getActiveSheet()->getStyle("C".$baris)->applyFromArray($styleLeftBold);
            for($i=0;$i<$days;$i++) {
                for($j=1;$j<=3;$j++) {
                    $tot = 0;
                    for($k=0;$k<=10;$k++) {
                        $tot = $tot + $total[$i][$j][$k];
                    }
                    $tot2 = $tot2 + $tot;
                    $objset->setCellValue($alpha[$kolom+2].$baris, $tot);
                    $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+2].$baris)->applyFromArray($styleBold);
                    $kolom++;
                }
            }
            $objset->setCellValue($alpha[$kolom+15].$baris, $tot2);
            $objPHPExcel->getActiveSheet()->getStyle($alpha[$kolom+15].$baris)->applyFromArray($styleBold);
            
            
            
            for($i=7;$i<=$baris;$i++){
                $objPHPExcel->getActiveSheet()->getStyle("A".$i.":".$alpha[$kolom+15].$i)->applyFromArray(
                    array(
                        'borders' => array(
                            'bottom' => array(
                                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                                'color' => array('rgb' => '000000')
                            )
                        )
                    )
                );
            }
            
            for($i=3;$i<=$kolom+15;$i++){
                $objPHPExcel->getActiveSheet()->getStyle($alpha[$i]."7:".$alpha[$i].$baris)->applyFromArray(
                    array(
                        'borders' => array(
                            'right' => array(
                                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                                'color' => array('rgb' => '000000')
                            )
                        )
                    )
                );
            }
            
            $objPHPExcel->getActiveSheet()->getStyle("A".$last_baris_team.":".$alpha[$kolom+15].$last_baris_team)->applyFromArray(
                array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
            
            $objPHPExcel->getActiveSheet()->getStyle("A".$baris.":".$alpha[$kolom+15].$baris)->applyFromArray(
                array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
            
            $objPHPExcel->getActiveSheet()->getStyle("A4:".$alpha[$kolom+15].$baris)->applyFromArray(
                array(
                    'borders' => array(
                        'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
            
            $objPHPExcel->getActiveSheet()->getStyle("A6:".$alpha[$kolom+15]."6")->applyFromArray(
                array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
            
            $objPHPExcel->getActiveSheet()->getStyle("B4:B".$last_baris_team)->applyFromArray(
                array(
                    'borders' => array(
                        'left' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        ),
                        'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
            
            $objPHPExcel->getActiveSheet()->getStyle("C4:C".$baris)->applyFromArray(
                array(
                    'borders' => array(
                        'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );
            
            
            $baris = $baris + 3;
            //legenda
            $objset->setCellValue("A".$baris, 'Keterangan:');
            $objPHPExcel->getActiveSheet()->getStyle("A".$baris)->applyFromArray($styleLeftBold);
            for($i=0;$i<=13;$i++){
                $baris++;
                $objset->setCellValue("A".$baris, $arr[$i]['code']);
                $objset->setCellValue("B".$baris, $arr[$i]['full']);
                $objPHPExcel->getActiveSheet()->getStyle("B".$baris)->applyFromArray($styleLeft);
            }

			$objPHPExcel->getActiveSheet()->setTitle('Attendance Summary');

			$objPHPExcel->setActiveSheetIndex(0);  
			$filename = urlencode('AttendanceReport.xls');

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                
			$objWriter->save('php://output');
		} else {
            echo "no-data";
			//redirect('report_attendance');
		}
	}
}