<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\BookTypeModel;
use app\common\model\BookModel;

class Book extends BackBase {
    protected $bookTypeModel;
    protected $bookModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->bookTypeModel = new BookTypeModel();
        $this->bookModel = new BookModel();
    }

    /*添加图书信息*/
    public function add(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $book = $this->getBookForm(true);
            $this->uploadPhoto($book,"bookPhoto","bookPhotoFile"); //处理图书图片上传
            $this->uploadFile($book,"bookFile","bookFileFile"); //处理图书文件上传
            try {
                $this->bookModel->addBook($book);
                $message = "图书添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return view('book/book_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("barcode",input("barcode"));
        return view("book/book_modify");
    }

    /*ajax方式按照查询条件分页查询图书信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->bookModel->setRows($this->request->param("rows"));
            $barcode = input("barcode")==null?"":input("barcode");
            $bookName = input("bookName")==null?"":input("bookName");
            $bookTypeObj["bookTypeId"] = input("bookTypeObj_bookTypeId")==null?0:input("bookTypeObj_bookTypeId");
            $publishDate = input("publishDate")==null?"":input("publishDate");
            $bookRs = $this->bookModel->queryBook($barcode, $bookName, $bookTypeObj, $publishDate, $this->currentPage);
            $expTableData = [];
            foreach($bookRs as $bookRow) {
                $bookRow["bookTypeObj"] = $this->bookTypeModel->getBookType($bookRow["bookTypeObj"])["bookTypeName"];
                $expTableData[] = $bookRow;
            }
            $data["rows"] = $bookRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->bookModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("book/book_query");
        }
    }

    /*ajax方式查询图书信息*/
    public function listAll() {
        $bookRs = $this->bookModel->queryAllBook();
        echo json_encode($bookRs);
    }
    /*更新图书信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $book = $this->getBookForm(false);
            $this->uploadPhoto($book,"bookPhoto","bookPhotoFile"); //处理图书图片上传
            $this->uploadFile($book,"bookFile","bookFileFile"); //处理图书文件上传
            try {
                $this->bookModel->updateBook($book);
                $message = "图书更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取图书对象*/
            $barcode = input("barcode");
            $book = $this->bookModel->getBook($barcode);
            echo json_encode($book);
        }
    }

    /*删除多条图书记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $barcodes = input("barcodes");
        try {
            $count = $this->bookModel->deleteBooks($barcodes);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

    /*按照查询条件导出图书信息到Excel*/
    public function outToExcel() {
        $barcode = input("barcode")==null?"":input("barcode");
        $bookName = input("bookName")==null?"":input("bookName");
        $bookTypeObj["bookTypeId"] = input("bookTypeObj_bookTypeId")==null?0:input("bookTypeObj_bookTypeId");
        $publishDate = input("publishDate")==null?"":input("publishDate");
        $bookRs = $this->bookModel->queryOutputBook($barcode,$bookName,$bookTypeObj,$publishDate);
        $expTableData = [];
        foreach($bookRs as $bookRow) {
            $bookRow["bookTypeObj"] = $this->bookTypeModel->getBookType($bookRow["bookTypeObj"])["bookTypeName"];
            $expTableData[] = $bookRow;
        }
        $xlsName = "Book";
        $xlsCell = array(
            array('barcode','图书条形码','string'),
            array('bookName','图书名称','string'),
            array('bookTypeObj','图书所在类别','string'),
            array('price','图书价格','float'),
            array('count','库存','int'),
            array('publishDate','出版日期','string'),
            array('publish','出版社','string'),
            array('bookPhoto','图书图片','photo'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

