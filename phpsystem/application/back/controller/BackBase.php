<?php
namespace app\back\controller;
use think\Controller;

class BackBase extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    public function _initialize(){
        if(!session('username')){
            $this->error('请先登录系统！','Login/index');
        }
    }

    public function writeJsonResponse($success, $message) {
        $response = array(
            "success" => $success,
            "message" => $message,
        );
        echo json_encode($response);
    }

    /**
     * @param $obj:  保存图片路径的对象
     * @param $indexName 索引名称
     * @param $photoFiledName 提交的图片表单名称
     */
    public function uploadPhoto(&$obj,$indexName,$photoFiledName) {
        if($_FILES[$photoFiledName]['tmp_name']){
            $file = $this->request->file($photoFiledName);
            //控制上传的文件类型，大小
            if(!(($_FILES[$photoFiledName]["type"]=="image/jpeg"
                    || $_FILES[$photoFiledName]["type"]=="image/jpg"
                    || $_FILES[$photoFiledName]["type"]=="image/png") && $_FILES[$photoFiledName]["size"] < 1024000)) {
                $message = "图书图片请选择jpg或png格式的图片!";
                $this->writeJsonResponse(false,$message);
                exit;
            }
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    /**
     * @param $obj:  保存文件路径的对象
     * @param $indexName 索引名称
     * @param $resourceFiledName 提交的文件表单名称
     */
    public function uploadFile(&$obj,$indexName,$resourceFiledName) {
        if($_FILES[$resourceFiledName]['tmp_name']){
            $file = $this->request->file($resourceFiledName);
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    //接收提交的BookType信息参数
    public function getBookTypeForm($insertFlag) {
        $bookType = [
            'bookTypeId'=> input("bookType_bookTypeId"), //图书类别
            'bookTypeName'=> input("bookType_bookTypeName"), //类别名称
            'days'=> input("bookType_days"), //可借阅天数
        ];
        return $bookType;
    }

    //接收提交的Book信息参数
    public function getBookForm($insertFlag) {
        $book = [
            'barcode'=> input("book_barcode"), //图书条形码
            'bookName'=> input("book_bookName"), //图书名称
            'bookTypeObj'=> input("book_bookTypeObj_bookTypeId"), //图书所在类别
            'price'=> input("book_price"), //图书价格
            'count'=> input("book_count"), //库存
            'publishDate'=> input("book_publishDate"), //出版日期
            'publish'=> input("book_publish"), //出版社
            'bookPhoto' => $insertFlag==true?"upload/NoImage.jpg":input("book_bookPhoto"), //图书图片
            'bookDesc'=> input("book_bookDesc"), //图书简介
            'bookFile' => $insertFlag==true?"":input("book_bookFile"), //图书文件
        ];
        return $book;
    }

    //接收提交的ReaderType信息参数
    public function getReaderTypeForm($insertFlag) {
        $readerType = [
            'readerTypeId'=> input("readerType_readerTypeId"), //读者类型编号
            'readerTypeName'=> input("readerType_readerTypeName"), //读者类型
            'number'=> input("readerType_number"), //可借阅数目
        ];
        return $readerType;
    }

    //接收提交的Reader信息参数
    public function getReaderForm($insertFlag) {
        $reader = [
            'readerNo'=> input("reader_readerNo"), //读者编号
            'readerTypeObj'=> input("reader_readerTypeObj_readerTypeId"), //读者类型
            'readerName'=> input("reader_readerName"), //姓名
            'sex'=> input("reader_sex"), //性别
            'birthday'=> input("reader_birthday"), //读者生日
            'telephone'=> input("reader_telephone"), //联系电话
            'email'=> input("reader_email"), //联系Email
            'qq'=> input("reader_qq"), //登录密码
            'address'=> input("reader_address"), //读者地址
            'photo' => $insertFlag==true?"upload/NoImage.jpg":input("reader_photo"), //读者头像
        ];
        return $reader;
    }

    //接收提交的LoanInfo信息参数
    public function getLoanInfoForm($insertFlag) {
        $loanInfo = [
            'loadId'=> input("loanInfo_loadId"), //借阅编号
            'book'=> input("loanInfo_book_barcode"), //图书对象
            'reader'=> input("loanInfo_reader_readerNo"), //读者对象
            'borrowDate'=> input("loanInfo_borrowDate"), //借阅时间
            'returnDate'=> input("loanInfo_returnDate"), //归还时间
        ];
        return $loanInfo;
    }

    /** * 公共数据导出实现功能
     * @param $expTitle 导出文件名
     * @param $expCellName 导出文件列名称
     * @param $expTableData 导出数据
     */
    public function export_excel($expTitle,$expCellName,$expTableData) {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle . date('_Ymd');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        //这些文件需要下载phpexcel，然后放在vendor文件里面。具体参考上一篇数据导出。
        vendor("PHPExcel.PHPExcel");
        //vendor("PHPExcel.PHPExcel.Writer.IWriter");
        //vendor("PHPExcel.PHPExcel.Writer.Abstract");
        //vendor("PHPExcel.PHPExcel.Writer.Excel5");
        //vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();//方法一
        $cellName = array('A','B', 'C','D', 'E', 'F','G','H','I', 'J', 'K','L','M', 'N', 'O', 'P', 'Q','R','S', 'T','U','V', 'W', 'X','Y', 'Z', 'AA',    'AB', 'AC','AD','AE', 'AF','AG','AH','AI', 'AJ', 'AK', 'AL','AM','AN','AO','AP','AQ','AR', 'AS', 'AT','AU', 'AV','AW', 'AX', 'AY', 'AZ');
        //设置头部导出时间备注
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . ' 导出时间:' . date('Y-m-d H:i:s'));
        //设置列名称
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(20); //设置每列宽度
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中对齐
        }
        //赋值
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if($expCellName[$j][2] == 'photo') {
                    try {
                        // 表格高度
                        $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(80);
                        // 图片生成
                        $objDrawing = new \PHPExcel_Worksheet_Drawing();
                        $objDrawing->setPath(PUBLIC_PATH.$expTableData[$i][$expCellName[$j][0]]);
                        // 设置宽度高度
                        $objDrawing->setHeight(80);//照片高度
                        $objDrawing->setWidth(80); //照片宽度
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates($cellName[$j].($i + 3));
                        // 图片偏移距离
                        $objDrawing->setOffsetX(5);
                        $objDrawing->setOffsetY(5);
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                    } catch (\Exception $ex) {}
                } else {
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i + 3), $expTableData[$i][$expCellName[$j][0]]);
                }
            }
        }
        ob_end_clean();//这一步非常关键，用来清除缓冲区防止导出的excel乱码
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//"xls"参考下一条备注
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'  );//"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls
        $objWriter->save('php://output');
    }
}

