<?php
/**
 * Created by PhpStorm.
 * User: mayunfeng
 * Date: 2018/11/29
 * Time: 21:52
 */

namespace yii\aliyunoss;

use Yii;
use OSS\OssClient;
use OSS\Core\OssException;
use yii\base\Component;

class OSS extends Component
{
    /**
     * @var string 访问密钥的id
     */
    public $accessKeyId;
    /**
     * @var string 访问密钥的secret
     */
    public $accessKeySecret;
    /**
     * @var string OSS访问域名
     */
    public $endPoint;
    /**
     * @var string 存储空间名称
     */
    public $bucket;

    /**
     * @var string 绑定的域名别名
     */
    public $cname;
    /**
     * @var \OSS\OssClient OssClient实例
     */
    protected $ossClient;

    /**
     * 返回OssClient实例(单例)
     */
    public function client()
    {
        try {
            if (empty($this->ossClient)) {
                $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endPoint);
            }
        } catch (OssException $exception) {
            $this->ossClient = null;
        }
        return $this->ossClient;
    }


    /**
     * 上传本地文件
     * @param string $filename object文件名
     * @param string $filepath 文件路径
     * @return string|null
     */
    public function uploadFile(string $filename, string $filepath)
    {
        try {
            $result = $this->client()->uploadFile($this->bucket,$filename, $filepath);
            if (isset($result['oss-request-url'])) {
                return $result['oss-request-url'];
            } else {
                return null;
            }
        } catch (OssException $exception) {
            Yii::error(__FUNCTION__ . ": FAILED\n" . $exception->getMessage() . "\n");
            return null;
        }
    }

}
