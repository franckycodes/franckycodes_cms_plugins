<?php 
use franckycodes\database\LightDb;
//return all columns title of a given table in database expect the id
 
function createTableFormsPlugins($tableName,$values=[],$elemTypes=[],$isPrepared=false)
{
	$db=new LightDb();
	$describe=$db->query('DESCRIBE '.$tableName,false,[],true);
	$fields=[];
	$i=0;


	foreach($describe as $p)
	{
		//echo $p['Field'].'<br>';
		//if($i>0) //no need of id
		if($isPrepared)
		{
			$fields[$i]=':p'.$p['Field'];
		}else{
			$fields[$i]=$p['Field']; 
		}
		$i++;
	}
    $allFields='';

    $forms='';
	for($i=1,$c=count($fields);$i<$c;$i++)
	{
		$allFields.=$fields[$i].', ';
        $label=str_replace('_',' ',$fields[$i]);
		$id=$fields[$i];
        $forms.='<label for="'.$id.'">'.$label.'</label>';
		$forms.='<input type="text" id="'.$id.'" name="f'.$fields[$i].'" value="'.(isset($values[$i-1])?$values[$i-1]:'') .'"><br>';
	}
	// $allFields.=$fields[count($fields)-1];
    // $forms.='<label for="">'.$fields[count($fields)-1].'</label><input type="text" name="f'.$fields[count($fields)-1].'"><br>';
	return $forms;  //return all cols name of the given table
}

function showInput($label='', $type='', $name='', $value='')
{
	$name=str_replace(' ', '', $name);

	echo '<label for="'.$name.'">'.$label.'</label>';
	echo '<input type="'.$type.'" id="'.$name.'" name="'.$name.'" value="'.$value.'"><br>';
}