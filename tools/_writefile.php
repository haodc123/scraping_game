<?php

function w_file($data_w, $file) {
	//Khởi tạo đối tượng
	$excel = new PHPExcel();
	//Chọn trang cần ghi (là số từ 0->n)
	$excel->setActiveSheetIndex(0);
	//Tạo tiêu đề cho trang. (có thể không cần)
	$excel->getActiveSheet()->setTitle('ghi dữ liệu');

	//Xét chiều rộng cho từng cot, nếu muốn set height thì dùng setRowHeight()
	$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

	//Xét in đậm cho khoảng cột
	$excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
	//Tạo tiêu đề cho từng cột
	//Vị trí có dạng như sau:
	/**
	 * |A1|B1|C1|..|n1|
	 * |A2|B2|C2|..|n1|
	 * |..|..|..|..|..|
	 * |An|Bn|Cn|..|nn|
	 */
	$excel->getActiveSheet()->setCellValue('A1', 'ID');
	$excel->getActiveSheet()->setCellValue('B1', 'Title');
	$excel->getActiveSheet()->setCellValue('C1', 'Title slug');
	$excel->getActiveSheet()->setCellValue('D1', 'Desc');
	$excel->getActiveSheet()->setCellValue('E1', 'Guide');
	// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
	// dòng bắt đầu = 2
	$numRow = 2;
	foreach ($data_w as $row) {
		$excel->getActiveSheet()->setCellValue('A' . $numRow, $row[0]);
		$excel->getActiveSheet()->setCellValue('B' . $numRow, $row[1]);
		$excel->getActiveSheet()->setCellValue('C' . $numRow, $row[2]);
		$excel->getActiveSheet()->setCellValue('D' . $numRow, $row[3]);
		$excel->getActiveSheet()->setCellValue('E' . $numRow, $row[4]);
		$numRow++;
	}
	// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
	// ở đây mình lưu file dưới dạng excel2007
	PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('file/'.$file);

	// Popup Download
	// header('Content-type: application/vnd.ms-excel');
	// header('Content-Disposition: attachment; filename="data.xls"');
	// PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');
}