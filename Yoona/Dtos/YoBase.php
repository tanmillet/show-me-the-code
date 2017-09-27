<?php
namespace App\Yoona\Dtos;

use Illuminate\Database\Eloquent\Model;

/**
 * Class YoBase
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoBase extends Model
{
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $connection = '';

    /**
     * @author: promise tan
     * YoBase constructor.
     */
    public function __construct()
    {
        $this->connection = yoconf('env')['dbconnection'];
    }

    /**
     * author: promise tan
     * Date: ${DATE}
     * @var array
     */
    protected $guarded = [
        'id',
        'updated_at',
        'created_at'
    ];

    /**
     * @author: promise tan
     * @return array
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
