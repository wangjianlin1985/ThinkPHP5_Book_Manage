<?php
namespace app\front\controller;
use think\Controller;

class Base extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    //向客户端输出ajax响应结果
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

}

