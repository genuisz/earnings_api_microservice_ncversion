<?php 
namespace App\Repository;
use App\Repository\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Exceptions\DataNotFoundException;
use App\Models\Product;
use Exception;

abstract class AbstractRepository implements  RepositoryInterface{
    protected $model;
    public function __construct()
    {   

        $this->model=  app($this->model());
    }

    abstract function model();
    public function getOneById($id,$selectColumn =['*']): ?Model
    
    {   
        
        try{
            return $this->model->findOrFail($id,$selectColumn);
        }
       catch(ModelNotFoundException $e){
           
           throw new DataNotFoundException($e->getMessage());
       }
    }

    public function getByIds(array $ids,string $order, string $sort): Collection
    {
        return $this->model->whereIn($this->model->getKeyName(), $ids)->orderBy($order,$sort);
    }
    public function getAll(): Collection
    {
        
        return $this->model->all();
    }
    public function allAndSort( string $orderBy = 'id', string $sortBy = 'asc',$offset=0 ,$limit = 15 )
    {   
        return $this->model->orderBy($orderBy, $sortBy)->limit($limit)->offset($offset);
    }

    public function listAll( string $orderBy = 'id', string $sortBy = 'asc',$startDate=null, $endDate = null,$offset=0 ,$limit = 15 )
    {

        if($startDate==null && $endDate==null){
            return $this->allAndSort($orderBy,$sortBy,$offset,$limit)->whereDate('created_at','<=',Carbon::today()->toDateString());
        }
        else if ($startDate==null || $endDate ==null){
            if($startDate==null) return $this->allAndSort($orderBy,$sortBy)->whereDate('created_at','<=',$endDate);
            
            return $this->allAndSort($orderBy,$sortBy)->where('created_at','>=',$startDate);
        }
        else{
            return $this->allAndSort($orderBy,$sortBy)->whereDate('created_at','<=',$endDate)->whereDate('created_at','>=',$startDate);
        }
    }
    public function searchAll($keyword,string $orderBy = 'id', string $sortBy = 'asc',$startDate=null, $endDate = null,$offset=0 ,$limit = 15){
        if($startDate==null && $endDate==null){
            return $this->model->search($keyword)->orderBy($orderBy,$sortBy)->limit($limit)->offset($offset)->whereDate('created_at','<=',Carbon::today()->toDateString());
        }
        else if ($startDate==null || $endDate ==null){
            if($startDate==null) return $this->model->search($keyword)->orderBy($orderBy,$sortBy)->limit($limit)->offset($offset)->whereDate('created_at','<=',$endDate);
            
            return $this->model->search($keyword)->orderBy($orderBy,$sortBy)->limit($limit)->offset($offset)->where('created_at','>=',$startDate);
        }
        else{
            return $this->model->search($keyword)->orderBy($orderBy,$sortBy)->limit($limit)->offset($offset)->whereDate('created_at','<=',$endDate)->whereDate('created_at','>=',$startDate);
        }
    }

    public function saveData(array $data,bool $saveMultipleAttribute){
        if($saveMultipleAttribute){
            return $this->model->create($data);
        }
        else{
            foreach($data as $key=>$value){
                $this->model->$key = $value;
            }
            $result = $this->model->save();
            if($result){
                return $this->model;
            }
            return false;

        }
        
 
     }
    //  public function saveDataConstraint(array $data,$constraint ,$massAssign){
    //      $allow =false;
    //      if($massAssign==true){
    //         return $this->model->create($data);
    //      }
    //      else{
    //          foreach($data as $key=>$value){
    //              $this->model->$key = $value;
    //          }
    //          if($constraint!=null){
    //             //$allow = $this->model->where($constraint);
    //             $allow = $constraint;
    
    //             if($allow){
    //                 $result = $this->model->save();
    //              }
    //          }
    //          else{
    //             $result = $this->model->save();
    //             //var_dump($this->model);
    //          }
             
             

              
    //           if($result===true){
    //             return $this->model;
    //           }
    //           else{
    //               return false;
    //           }
             
    //      }
        
         
    //  }

 

     public function saveDataWithSubquery(array $array , $subquery,$subModel){
         $selectColumn =[];
         $selectData = [];
         foreach($array as $key=>$value){
            $selectColumn[] = $key;
            $selectData[] = $value;
        }
        $column  = implode(',',$selectColumn);
        $data = implode(',',$selectData);
        
        DB::insert("INSERT INTO `{$this->model->getTable()}`  " .$column." SELECT {$data} FROM {$subModel->getTable()} {$subquery}");
     }

    
     public function updateData(array $data)
     {
         if(array_key_exists($this->model->getKeyName(),$data)){
            $this->setModel($this->getOneById($data[$this->model->getKeyName()]));
         }
         return $this->model->update($data);
     }

     public function deleteData():bool{
         return $this->model->delete();
     }

     public function setModel($model) {
        $this->model = $model;
    }

     public function createDataWithLargeNumber(array $array){

            foreach($array as $key =>$value){
                $value['created_at'] =now()->toDateTimeString();
                $value['updated_at'] =now()->toDateTimeString();
                $array[$key]= $value;
            }
           return $this->model->insert($array);
        //    $test->save([new BillItem(['item_no'=>'test1','bill_id'=>'1','contect'=>'test','qty'=>'1','price'=>'1','status'=>'1']),
        //    new BillItem(['item_no'=>'test2','bill_id'=>'1','contect'=>'test','qty'=>'1','price'=>'1','status'=>'1'])
        //    ]);

            // return $this->model->saveMany([
            //     new BillItem(['item_no'=>'test1','bill_id'=>'1','contect'=>'test','qty'=>'1','price'=>'1','status'=>'1'])
            // ]);
        

     }
     public function updateDataWithLargeNumber(array $array,$compositePrimaryKey =null){
 
        $result = 0;
        $cases = [];
        $ids =[];
        $params = [];
        $paramsKey = [];

        $arrayValue = [];
        $updateQueryIn ="";
        $primaryKeyValueSet = [];
        foreach($array as $i=>$element){

            if(is_array($compositePrimaryKey)){
                $q =" WHEN ";
                
                foreach($compositePrimaryKey as $index=>$primaryKey){

                    $id= "'".$element[$primaryKey]."'";
                    $primaryKeyValueSet[$primaryKey][] = $id; 
                    $ids[] = $id;
                    if($index==0){
                        $q .= " {$primaryKey} = {$id} ";
                        
                    }
                    else{
                        $q .= " AND {$primaryKey} = {$id} ";
                    }


                    unset($array[$i][$primaryKey]);
                    
                   
                }

                
                
                
                //dump($primaryKeyValueSet);
                
                $q .=" then ? ";

                $cases[] = $q;
            
            } 
            else{
                $id = "'".$element[$this->model->getKeyName()]."'";
                $ids[] = $id;
                $cases[] = "WHEN {$id} then ? ";
                unset($array[$i][$this->model->getKeyName()]);
                
            }
            
            
            $arrayValue[] = array_values($array[$i]);
            

            
        }
        //dd($array);
    

        $column = count($arrayValue[0]);
        $row = count($arrayValue);

        foreach($array[0] as $elementKey =>$elementValue){

                $paramsKey[] = $elementKey;
            

        }
        //dump($paramsKey);
        // foreach($array as $key=>$value){
        //     // $value = (object)$value;
        //     $id = $value['id'];
        //     $cases[] = "WHEN {$id} then ? ";
        //     unset($value['id']);
        //     foreach($value as $elementKey=>$elementValue){
        //         $params[]= $elementValue;
        //     }
        //     $ids[] = $id;

        // }
        for($i=0; $i <$column ; $i++){
            for($j=0 ; $j<$row ; $j++){
                $params[] = $arrayValue[$j][$i];
                //var_dump($arrayValue[$j][$i]);
            }
        }

        //dump($params);
        


        // dd($params);

        $ids = implode(',',$ids);
        $cases = implode(' ', $cases);

        $updateQuery = "";
        //dd($array);
        foreach($paramsKey as $param){
            
            if($param==reset($paramsKey)){
                $updateQuery.="SET";
            }
            if(is_array($compositePrimaryKey)){


                $updateQuery .= " `".$param."`= CASE {$cases} END";

            }
            else{
                $updateQuery .= " `".$param."`= CASE `id` {$cases} END";
            }
            
            if($param !=end($paramsKey)){
                $updateQuery.=",";
            }
           
        }

        
        //dd("UPDATE `{$this->model->getTable()}`".$updateQuery." WHERE `id` in ({$ids})");
        if(is_array($compositePrimaryKey)){
           //$updayeWhereQ = " WHERE  "
           $q =" WHERE ";
            $i=0;
            foreach($primaryKeyValueSet as $key =>$value){
                $data = implode(',',$value);
                if($i==0){
                    $q.= " `{$key}` in ({$data}) ";

                }
                else{
                    $q.= " AND `{$key}` in ({$data}) ";
                }
                $i++;
            }
           //dd("UPDATE `{$this->model->getTable()}`".$updateQuery.$q);
           //dump($params);
            //dd("UPDATE `{$this->model->getTable()}`".$updateQuery.$q);
            try{
                $result = DB::update("UPDATE `{$this->model->getTable()}`".$updateQuery.$q, $params);
            }
            catch(Exception $e){
                throw new DataNotFoundException($e->getTraceAsString());
            }

           
           
        }
        else{
            // dump($params);
            // dd("UPDATE `{$this->model->getTable()}`".$updateQuery." WHERE `id` in ({$ids})");
            try{
                $result = DB::update("UPDATE `{$this->model->getTable()}`".$updateQuery." WHERE `id` in ({$ids})", $params);
            }
            catch(Exception $e){
                throw new DataNotFoundException($e->getTraceAsString());
            }
            
        }

        if($result>0){
            return true;
        }
        else{
            throw new DataNotFoundException("");
        }
        
        
        return false;
    }

    public function updateOrInsertData($arraydata,$uniqueArray,$updatedArray){

        return $this->model->upsert($arraydata,$uniqueArray,$updatedArray);
        

    }

    
}