<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\BookTypeModel;

class BookType extends BackBase {
    protected $bookTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->bookTypeModel = new BookTypeModel();
    }

    /*添加图书类型信息*/
    public function add(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $bookType = $this->getBookTypeForm(true);
            try {
                $this->bookTypeModel->addBookType($bookType);
                $message = "图书类型添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书类型添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return view('bookType/bookType_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("bookTypeId",input("bookTypeId"));
        return view("bookType/bookType_modify");
    }

    /*ajax方式按照查询条件分页查询图书类型信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->bookTypeModel->setRows($this->request->param("rows"));
            $bookTypeRs = $this->bookTypeModel->queryBookType($this->currentPage);
            $expTableData = [];
            foreach($bookTypeRs as $bookTypeRow) {
                $expTableData[] = $bookTypeRow;
            }
            $data["rows"] = $bookTypeRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->bookTypeModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("bookType/bookType_query");
        }
    }

    /*ajax方式查询图书类型信息*/
    public function listAll() {
        $bookTypeRs = $this->bookTypeModel->queryAllBookType();
        echo json_encode($bookTypeRs);
    }
    /*更新图书类型信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $bookType = $this->getBookTypeForm(false);
            try {
                $this->bookTypeModel->updateBookType($bookType);
                $message = "图书类型更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书类型更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取图书类型对象*/
            $bookTypeId = input("bookTypeId");
            $bookType = $this->bookTypeModel->getBookType($bookTypeId);
            echo json_encode($bookType);
        }
    }

    /*删除多条图书类型记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $bookTypeIds = input("bookTypeIds");
        try {
            $count = $this->bookTypeModel->deleteBookTypes($bookTypeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

    /*按照查询条件导出图书类型信息到Excel*/
    public function outToExcel() {
        $bookTypeRs = $this->bookTypeModel->queryOutputBookType();
        $expTableData = [];
        foreach($bookTypeRs as $bookTypeRow) {
            $expTableData[] = $bookTypeRow;
        }
        $xlsName = "BookType";
        $xlsCell = array(
            array('bookTypeId','图书类别','int'),
            array('bookTypeName','类别名称','string'),
            array('days','可借阅天数','int'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

