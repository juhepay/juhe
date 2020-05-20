<?php
namespace App\Tool;

class ExportTool{
    protected $excel;
    public function __construct()
    {
        $tmp = ini_get('upload_tmp_dir');
        if ($tmp !== False && file_exists($tmp)) {
            $getTmpDir =  realpath($tmp);
        }else{
            $getTmpDir = realpath(sys_get_temp_dir());
        }
        $config = [
            'path' => $getTmpDir,
        ];
        $this->excel = new \Vtiful\Kernel\Excel($config);
    }

    /**
     * @param array $data 导出数据
     * @param array $header 表头
     * @param string $fileName 表格名称
     */
    public function D(array $data,array $header,string $fileName)
    {
        $filePath = $this->excel->fileName($fileName, 'sheet1')
            ->header($header)
            ->data($data)
            ->output();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');

        if (copy($filePath, 'php://output') === false) {
            // Throw exception
        }
        @unlink($filePath);
    }
}
