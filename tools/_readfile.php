<?php
/**
* arr_colnum: Index of column need read
*/
function r_file($file, $arr_colnum) {
	$file = 'file/'.$file;
	//Tiến hành xác thực file
	$objFile = PHPExcel_IOFactory::identify($file);
	$objData = PHPExcel_IOFactory::createReader($objFile);

	//Chỉ đọc dữ liệu
	$objData->setReadDataOnly(true);

	// Load dữ liệu sang dạng đối tượng
	$objPHPExcel = $objData->load($file);

	//Lấy ra số trang sử dụng phương thức getSheetCount();
	// Lấy Ra tên trang sử dụng getSheetNames();

	//Chọn trang cần truy xuất
	$sheet = $objPHPExcel->setActiveSheetIndex(0);

	//Lấy ra số dòng cuối cùng
	$Totalrow = $sheet->getHighestRow();
	//Lấy ra tên cột cuối cùng
	$LastColumn = $sheet->getHighestColumn();

	//Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
	$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);

	//Tạo mảng chứa dữ liệu
	$data_r = [];

	//Tiến hành lặp qua từng ô dữ liệu
	//----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
	for ($i = 2; $i <= $Totalrow; $i++) {
		//----Lặp cột
		for ($j = 0; $j < $TotalCol; $j++) {
			// Tiến hành lấy giá trị của từng ô đổ vào mảng
			if (in_array($j, $arr_colnum))
				$data_r[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
		}
	}
	return $data_r;
}