<!DOCTYPE html>
<html>
  <meta charset="utf-8" />
  <head>
    <title>Test view for Matrix framework!</title>
  </head>
  <body>
    <h2>模板文件，直接使用原生php内嵌即可。</h2>
    <h2>普通变量：</h2>
	<div><?php echo $test_var;?></div>
	<h2>数组循环：</h2>
	<div>
	  <?php foreach($test_arr as $key=>$item){
	    echo $key.'==='.$item.'<br />';
	  }?>
	</div>
  </body>
</html>