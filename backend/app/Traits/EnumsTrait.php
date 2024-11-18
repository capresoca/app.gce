<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/05/2018
 * Time: 8:48 AM
 */

namespace App\Traits;


use Illuminate\Support\Facades\DB;

trait EnumsTrait
{
    public static function getEnumValues($table, $column) {
        $type = DB::select( DB::raw("SHOW COLUMNS FROM ".$table." WHERE Field = '".$column."'") )[0]->Type;
        $dataTypes = explode("(", $type);
        $dataType = $dataTypes[0];
        if ($dataType == 'enum' || $dataType=='set'){
            preg_match('/^'.$dataType.'\((.*)\)$/', $type, $matches);
            $enum = array();
            foreach( explode(',', $matches[1]) as $value )
            {
                $v = trim( $value, "'" );
                $enum = array_add($enum, $v, $v);
            }
            return array_keys($enum);
        }
        return;
    }

    public static function getDistincts($table, $column){
        return DB::table($table)->select($column)->distinct()
            ->whereNotNull($column)
            ->where($column,'<>','')->get()->pluck($column);
    }
}