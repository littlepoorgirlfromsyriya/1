<?

/*
=================

Functions

=================
*/

function ProcessTable($name, $data)
{
	global $do_not_export, $srcfolder;
	global $datadir;

	foreach($do_not_export As $item) {
		if (0 === strpos($name, $item)) {
			return;
		}
	}

	if(!file_exists($srcfolder."data"))
		mkdir($srcfolder."data");

	file_put_contents($srcfolder."data/".$name, $data);
	$zcv = $data;

	$tbllen = GetPackedValue($data);
	$tbl = substr($data, 0, $tbllen);
	$data = substr($data, $tbllen);

	while(strlen($tbl))
	{
		$len = substr($tbl,0,1);
		$len = unpack("C", $len);
		$len = $len[1];
		$tbl = substr($tbl,1);

		$chunk = substr($tbl,0,$len);
		$tbl = substr($tbl,$len);

		if($chunk=="data") {
			$datadir[$name]["data"] = $zcv;
			//DexorTable($name, "", $p);
			return;
		}

		$size = GetPackedValue($tbl);
		echo "-- ".$chunk."\n";

		$p = substr($data,0,$size);
		$data = substr($data,$size);

		DexorTable($name, $chunk, $p, 0);
	}
}

function DexorTable($mainname, $fieldname, $data, $need_decode=1)
{
	global $datadir, $srcfolder;

	$copy = $data;

//	if($fieldname=="data") {
//		$datadir[$mainname][$fieldname] = $data;
//		return;
//	}

	if($fieldname!="")
		$datadir[$mainname][$fieldname] = $data;

	$tbllen = GetPackedValue($data);

	if($tbllen==0)
		return "";

	$len = GetPackedValue($data);
	$data = substr($data,$len);
	$len = GetPackedValue($data);

	$start = $tbllen+1;

	for($i=$start; $i<$start+$len; $i++)
	{
		$ch = $copy[$i];
		$ch = unpack("C", $ch);
		$ch = $ch[1];
		if($need_decode==1) {
			$dat .= chr($ch ^ 0xC5);
			$copy[$i]=chr($ch ^ 0xC5);
		} else {
			$dat .= chr($ch);
			$copy[$i]=chr($ch);
		}
	}

	$filename = $srcfolder."data/".$mainname;
	if($fieldname!="") $filename .= "-".$fieldname;
	file_put_contents($filename, $copy);

	return $dat;
}

function ExportField($name, $field, $need_decode=1, $pair_decode=0)
{
	global $datadir;

	echo "Export $name $fieldm type=$pair_decode\n";

	$afield = array();

	$dat = $datadir[$name][$field];

	if(strlen($dat)==0)
		die("Error.\n");

	$dat = DexorTable($name, $field, $dat, $need_decode);

	if($pair_decode==10) {
		$i = 1;

		for($k=0; $k<strlen($dat); $k+=4) {
			$temp = substr($dat,$k,4);
			//$afield[$i++] = bin2hex($temp);
			$temp = unpack("L", $temp);
			$afield[$i++] = $temp[1];
		}

		return $afield;
	}

	if($pair_decode==1) {
		$i = 1;

		for($k=0; $k<strlen($dat); $k++) {
			$temp = substr($dat,$k,12);
			$initlen = strlen($temp);
			$repeat = GetPackedValue($temp);
			$value = GetPackedValue($temp);

			for($n=0; $n<$repeat; $n++)
				$afield[$i++] = $value;

			$x = $initlen - strlen($temp);
			$k = $k + $x - 1;
		}

		return $afield;
	}

	if($pair_decode==4) {
		$i = 1;

		for($k=0; $k<strlen($dat); $k++)
		{
			echo "\r $k/".strlen($dat)."   ";

			$temp = substr($dat,$k,22);
			$initlen = strlen($temp);
			$repeat = GetPackedValue($temp);
			$nm = $initlen - strlen($temp);

			$len = GetPackedValue($temp);
			$xlen = GetPackedValue($temp);
/*
			if($len==0) {
				$x = "";
			}
			else if($len>0x7f) {
				$x = substr($dat,$k+$nm,$len+2);
//				$x = UnpackWideString($x);
				$len++;
			}
			else {
				$x = substr($dat,$k+$nm,$len+1);
//				$x = UnpackWideString($x);
			}
*/
//echo $repeat." ".dechex($xlen)."\n";
//			for($n=0; $n<$repeat; $n++)
				$afield[$repeat] = $xlen;
    
            $k = $k + $len + $nm;
		}
//die;
		return $afield;
	}

	if($pair_decode==2) {
		$i = 1;

		for($k=0; $k<strlen($dat); $k++) {
			$temp = substr($dat,$k,12);
			$initlen = strlen($temp);
			$value = GetPackedValue($temp);
			$afield[$i++] = $value;
			$g = $initlen - strlen($temp);
			$k = $k + $g - 1;
		}

		return $afield;
	}

	if($pair_decode==3) {
		$i = 1;

		for($k=0; $k<strlen($dat); $k++)
		{
			echo "\r $k/".strlen($dat)."   ";

			$temp = substr($dat,$k,12);
			$initlen = strlen($temp);
			$repeat = GetPackedValue($temp);
			$nm = $initlen - strlen($temp);

			$len = GetPackedValue($temp);

			if($len==0) {
				$x = "";
			}
			else if($len>0x7f) {
				$x = substr($dat,$k+$nm,$len+2);
				$x = UnpackWideString($x);
				$len++;
			}
			else {
				$x = substr($dat,$k+$nm,$len+1);
				$x = UnpackWideString($x);
			}
    
			for($n=0; $n<$repeat; $n++)
				$afield[$i++] = iconv("utf-16le", "utf-8", $x);
    
            $k = $k + $len + $nm;
		}

		return $afield;
	}

	$i = 1;

	for($k=0; $k<strlen($dat); $k++)
	{
		$temp = substr($dat,$k,5);
		$len = GetPackedValue($temp);

		if($len==0) {
			$x = "";
		}
		else if($len>0x7f) {
			$x = substr($dat,$k,$len+2);
			$x = UnpackWideString($x);
			$len++;
		}
		else {
			$x = substr($dat,$k,$len+1);
			$x = UnpackWideString($x);
		}

		$afield[$i++] = iconv("utf-16le", "utf-8", $x);

        $k = $k + $len;
	}

	return $afield;
}

function GetPackedValue(&$tbl)
{
	$size = substr($tbl,0,1);
	$size = unpack("C", $size);
	$size = $size[1];
//echo "s=".dechex($size)." ";
	$tbl = substr($tbl,1);
	$val = $size;

	if($size >= 0xF0) {
		$size = substr($tbl,0,1);
		$size = unpack("C", $size);
		$size = $size[1];
		$tbl = substr($tbl,1);

		$size2 = substr($tbl,0,1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];
		$tbl = substr($tbl,1);

		$size3 = substr($tbl,0,1);
		$size3 = unpack("C", $size3);
		$size3 = $size3[1];
		$tbl = substr($tbl,1);

		$size4 = substr($tbl,0,1);
		$size4 = unpack("C", $size4);
		$size4 = $size4[1];
		$tbl = substr($tbl,1);

		$val = (($size<<24)|($size2)<<16|($size3<<8)|$size4);
		return $val;
	}

	if($size >= 0xE0) {
		$size2 = substr($tbl,0,1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];
		$tbl = substr($tbl,1);

		$size3 = substr($tbl,0,1);
		$size3 = unpack("C", $size3);
		$size3 = $size3[1];
		$tbl = substr($tbl,1);

		$size4 = substr($tbl,0,1);
		$size4 = unpack("C", $size4);
		$size4 = $size4[1];
		$tbl = substr($tbl,1);

		$val = (($size^0xE0)<<24|($size2)<<16|($size3<<8)|$size4);
		return $val;
	}

	if($size >= 0xC0) {
		$size2 = substr($tbl,0,1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];
		$tbl = substr($tbl,1);

		$size3 = substr($tbl,0,1);
		$size3 = unpack("C", $size3);
		$size3 = $size3[1];
		$tbl = substr($tbl,1);

		$val = (($size^0xC0)<<16|($size2)<<8|$size3);
		return $val;
	}

	if($size & 0x80) {
		$size2 = substr($tbl,0,1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];
		$tbl = substr($tbl,1);
		$val = (($size^0x80)<<8|$size2);
		return $val;
	}

	return $val;
}

function UnpackWideString($str)
{
	$x1 = GetPackedValue($str);
	$x2 = GetPackedValue($str);

	$z = "";
	for($i=0; $i<$x2; $i++) {
		$z .= substr($str,0,1);
		$z .= chr(0);
		$str = substr($str,1);
	}

	if(strlen($str))
	{
		$arr = array();

		$count = substr($str,0,1);
		$str = substr($str,1);
		$count = unpack("C", $count);
		$mcount = $count[1];

		for($i=0; $i<$mcount; $i++) {
			$v = substr($str,0,1);
			$str = substr($str,1);
			$v = unpack("C", $v);
			$v = $v[1];
			$arr[] = $v;
		}

		$iter = 0;
		while(strlen($str))
		{
			$v = substr($str,0,1);
			$str = substr($str,1);
			$v = unpack("C", $v);
			$v = $v[1];
			$count=floor($v/$mcount);
			$offset = $v%$mcount;

			if($count==0) {
				$l = strlen($z);
				for($i=$iter;$i<$l;$i+=2) {
					$z[$i+1]=chr($arr[$offset]);
				}
			}
			else {
				for($i=0;$i<$count;$i++) {
					$z[$iter+1]=chr($arr[$offset]);
					$iter+=2;
				}
			}
		}
	}

	return $z;
}

function ReadPackedValue()
{
	global $fp;

	$size = fread($fp, 1);
	$size = unpack("C", $size);
	$size = $size[1];
	$val = $size;

	if($size >= 0xF0) {
		$size = fread($fp, 1);
		$size = unpack("C", $size);
		$size = $size[1];

		$size2 = fread($fp, 1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];

		$size3 = fread($fp, 1);
		$size3 = unpack("C", $size3);
		$size3 = $size3[1];

		$size4 = fread($fp, 1);
		$size4 = unpack("C", $size4);
		$size4 = $size4[1];

		$val = (($size<<24)|($size2<<16)|($size3<<8)|$size4);
		return $val;
	}

	if($size >= 0xE0) {
		$size2 = fread($fp, 1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];

		$size3 = fread($fp, 1);
		$size3 = unpack("C", $size3);
		$size3 = $size3[1];

		$size4 = fread($fp, 1);
		$size4 = unpack("C", $size4);
		$size4 = $size4[1];

		$val = (($size^0xE0)<<24|($size2)<<16|($size3<<8)|$size4);
		return $val;
	}

	if($size >= 0xC0) {
		$size2 = fread($fp, 1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];

		$size3 = fread($fp, 1);
		$size3 = unpack("C", $size3);
		$size3 = $size3[1];

		$val = (($size^0xC0)<<16|($size2)<<8|$size3);
		return $val;
	}

	if($size & 0x80) {
		$size2 = fread($fp, 1);
		$size2 = unpack("C", $size2);
		$size2 = $size2[1];
		$val = (($size^0x80)<<8|$size2);
		return $val;
	}

	return $val;
}

function ReadLong()
{
	global $fp;
	$v = fread($fp, 4);
	$v = unpack("L", $v);
	return $v[1];
}

function ReadByte()
{
	global $fp;
	$v = fread($fp, 1);
	$v = unpack("C", $v);
	return $v[1];
}

function ReadString($len)
{
	global $fp;
	if($len>0)
		return fread($fp, $len);
	return "";
}

