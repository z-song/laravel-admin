# 数据导出 #

model-grid内置的导出功能只是实现了简单的csv格式文件的导出，如果遇到文件编码问题或者满足不了自己需求的情况，可以按照下面的步骤来自定义导出功能

本示例用Laravel-Excel作为excel操作库，当然也可以使用任何其他excel库

首先安装好它：

composer require maatwebsite/excel:~2.1.0

php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
然后新建自定义导出类，比如app/Admin/Extensions/ExcelExpoter.php:

    <?php

    namespace App\Admin\Extensions;
    
    use Encore\Admin\Grid\Column;
    use Encore\Admin\Grid\Exporters\AbstractExporter;
    use Maatwebsite\Excel\Facades\Excel;
    
    class ExcelExpoter extends AbstractExporter
	{
	    public $titles = [];
	
	    public function __construct($grid, $name)
	    {
	        $this->grid = $grid;
	        $this->tablename = $name;
	    }
	
	    public function export()
	    {
	        Excel::create($this->tablename . date('Y-m-d'), function ($excel) {
	
	            $excel->setTitle('这是啥');
	            $excel->setCreator('Creator这又是啥')
	                ->setCompany('Maatwebsite又是什么鬼');
	            $excel->setDescription('Description这个好像挺长，写点啥呢');
	            $columns = $this->grid->columns();
	            if (!empty($columns)) {
	                foreach ($columns as $c) {
	                    array_push($this->titles, $c->getLabel());
	                }
	            }
	            $excel->sheet($this->tablename, function ($sheet) {
	                $this->grid->build();
	                // 这段逻辑是从表格数据中取出需要导出的字段
	                $dbcolumnnames = [];
	                $titlenames = [];
	                $this->grid->columns()->map(function (Column $column) use (&$dbcolumnnames, &$titlenames) {
	                    if ($column->getName() != '__row_selector__') {
	                        array_push($dbcolumnnames, $column->getName());
	                        array_push($titlenames, $column->getLabel());
	                    }
	                });
	                $dbcolumnnames = array_unique($dbcolumnnames);
	                $titlenames = array_unique($titlenames);
	                $sheet->rows([$titlenames]);
	
	                $rows = $this->grid->rows();
	                $datas = $rows->map(function ($item) use ($dbcolumnnames) {
	                    $row = array();
	                    $model = $item->model();
	                    foreach ($dbcolumnnames as $key) {
	                        $row[$key] = $this->cutstr_html($model[$key]);
	                    }
	                    return $row;                 
	                });
	                $sheet->rows($datas);
	            });
	
	        })->export('xls');
	    }
	
	
	    //去掉文本中的HTML标签
	    public function cutstr_html($string, $length = 0, $ellipsis = '…')
	    {
	        $string = strip_tags($string);
	        $string = preg_replace('/\n/is', '', $string);
	        $string = preg_replace('/ |　/is', '', $string);
	        $string = preg_replace('/&nbsp;/is', '', $string);
	        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $string);
	        if (is_array($string) && !empty($string[0])) {
	            if (is_numeric($length) && $length) {
	                $string = join('', array_slice($string[0], 0, $length)) . $ellipsis;
	            } else {
	                $string = implode('', $string[0]);
	            }
	        } else {
	            $string = '';
	        }
	        return $string;
	    }
    }

然后在model-grid中使用这个导出类：

    use App\Admin\Extensions\ExcelExpoter;
    $filename="youfilename"
    
    $grid->exporter(new ExcelExpoter($grid,$filename));
这里还有些小问题，在Grid中使用display的时候需要做些调整，不优雅，只图方便
尽量这样写

    $grid->is_closed('是否关闭')->display(function ($is_closed) {
    	//不要$is_closed== 1 ? '是' : '否';不然可能会有问题
    	return $this->is_closed == 1 ? '是' : '否';
    });
